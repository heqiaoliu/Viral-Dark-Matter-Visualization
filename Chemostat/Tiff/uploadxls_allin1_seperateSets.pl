##################################################################
#Description: To convert input xls file to tab deliminated file
#	for MySQL input, especially LOAD_FILE
#INPUT: xls file, look for "label", "file id"
#OUTPUT: *.MSdata; *.MSxls; *.SampleInfo

##################################################################

use strict;
use warnings;
use DBI;
use DBD::mysql;

my $input = $ARGV[0]; #file: miniX85780_mod.txt
my $flag = 0;
my @reactor = ();
my @sample = ();
my @edt = ();
my @vcid = ();
my @date = ();
my @file_id = ();
my @miniX = ();
my %mini = ();
my $specie = '';
my %abundance = ();
my $datastart = 0;
my @ev = ();
my %bbName = ();
my %retIndex = ();
my %quantmz = ();
my %massSpec = ();
my %keggId = ();
my %pubchemId = ();
my @genotype = ();
my @vector = ();

open (IN, $input) or die "Can't open file $input:$!";
while (<IN>) {
	chomp;
	my @col = split(/\t/,$_);
	my $sizeCol = $#col;
	if ($datastart == 0 and $col[0] ne '') {
#		foreach my $s6 (0..$sizeCol) {
			#dataset starts	
			$datastart = 1;
			my $nc = 0;
			foreach my $n ('Name','ret','quant','BinBase','mass','kegg','pubchem') {
				if ($col[$nc] !~ /$n/i) {
					print "ERROR:Format error - Column name is". $col[$nc].", which should be $n\n";
				}
				$nc++;
			}

			next;
#		}
	}	
	
	if ($flag == 0 ) {
		my $i = 0;
		foreach my $v (@col) {
			if ($flag ==0 and $v ne "") {
				$flag = $i;
			}
			$i++;
		}
	}
	my $title = $col[$flag];
	my $f = $flag+1;

	#Store abundance data
	if ($datastart == 1) {
		my $bbid = $col[3]; 
		foreach my $s5 ($f..$sizeCol) {
			my $i = $s5 - $f;
			
			#By BinBase ID
			if ((!$col[$s5]) or ($col[$s5] eq '') or ($col[$s5] eq ' ')) {
				$abundance{$bbid}[$i] = 0;
			}else {
				$abundance{$bbid}[$i] = $col[$s5];
			}
		}
		$bbName{$bbid} = $col[0];
		$retIndex{$bbid} = $col[1];
		$quantmz{$bbid} = $col[2];
		$massSpec{$bbid} = $col[4];
		$keggId{$bbid} = $col[5];
		$pubchemId{$bbid} = $col[6];
	} else {


	#Store Sample data
	if (!$title) {
	} 
	elsif ($title eq 'label') {
		foreach my $s1 ($f..$sizeCol) {
			my $i = $s1 - $f;
			my $edtvcid = '';
			my $l1 = $col[$s1];
			($reactor[$i], $sample[$i], $edtvcid, $date[$i]) = &label_row($l1);
			$ev[$i] = $edtvcid;
			if ($edtvcid =~ /^EDT/) {
				$edt[$i] = $edtvcid;
			}elsif ($edtvcid =~ /^VCID/) {
				$vcid[$i] = $edtvcid;
			}else {
				$edt[$i] = $edtvcid;
				print "$edtvcid\tEDT/VCID, not exist\n";
			}
		}
	}elsif ($title eq 'file id') {
		foreach my $s2 ($f..$sizeCol) {
			my $i = $s2 - $f;
			$file_id[$i] = $col[$s2];
		}
	}elsif ($title =~ /^\d+$/) { #Done with title, input abundance
		print "Start abundance:?\n";
	}elsif ($title eq 'treatment') {
		foreach my $s3 ($f..$sizeCol) {
			my $i = $s3 - $f;
			my $l2 = $col[$s3];
			($genotype[$i], $vector[$i]) = &treatment($l2);
		}
	}elsif ($title eq 'mx class id') {
		foreach my $s2 ($f..$sizeCol) {
			my $i = $s2 - $f;
			my $tmpc = $col[$s2];
			$miniX[$i] = $tmpc;
			$mini{$tmpc} = 1;
		}		
	} else {		
			print "$title\n";
	}
	}
}

close(IN);

$specie = 'E.Coli';

#Database section

my $database = "viral_dark_matter";
my $user = "nturner";
my $password = "LOB4steR";
my $host = "localhost";
my $port = "3306";

# open the database connection
my $dbh = DBI->connect("DBI:mysql:database=$database;host=$host",
                      $user, $password ) or die $DBI::errstr;

#Insert Experiment information to Experiment_info table
my $sizeFileId = $#file_id;
my $file_comment = "Combine set 103471 Normalized by alignment ref Jeremy Frank";
my $ei_query = "INSERT INTO experiment_info (Exp_name, comment) VALUES (?,?)";
my $ei_statment = $dbh->prepare($ei_query);
foreach my $i (sort {$a<=>$b} keys %mini) {
	my $xx = 'miniX'.$i;
	$ei_statment -> execute($xx,$file_comment);
}
#$ei_statment -> execute($input,$file_comment);

#Insert BinBase information to Binbase table
my $bb_query = "INSERT INTO binBase (BB_ID, BB_Name, Kegg_id, Pubchem_id, ret_index, quant_mz, mass_spec) VALUES (?,?,?,?,?,?,?)";
foreach my $i (sort {$a <=> $b} keys %bbName) {
	my $bb_statment = $dbh->prepare($bb_query);
	$bb_statment -> execute($i,$bbName{$i},$keggId{$i},$pubchemId{$i},$retIndex{$i},$quantmz{$i},$massSpec{$i});
}

#Insert Sample information to Sample_Info file
my $si_query = "INSERT INTO sample_info (Reactor_ID, Sample_ID, bact_external_id, vc_id, rep, genotype, vector, specie, ms_date, File_ID, Comment) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
my $comment = $specie;
my %countev = ();

foreach my $i (0..$sizeFileId) {
	my $e1 = 'NULL';
	if ($edt[$i]) {
		$e1 = $edt[$i];
	} 

	my $e2 = 'NULL';
	if ($vcid[$i]) {
		my ($d1, $d2) = split(/ID/,$vcid[$i]);
		$e2 = $d2;
	} 

        my $e = $ev[$i];
	if ($countev{$e}) {
		$countev{$e} += 1;
	}else {
		$countev{$e} = 1;
	}

	my $si_statment = $dbh->prepare($si_query);
	$si_statment -> execute($reactor[$i],$sample[$i],$e1, $e2, $countev{$e}, $genotype[$i], $vector[$i], $specie, $date[$i], $file_id[$i], $comment);
}

#Insert Sample information to Sample_Info file
my $cd_query = "INSERT INTO chemo_data (Samp_id, bin_id, exp_id, abundance) VALUES (?,?,?,?)";

my $samp_id = 'NUL';
my $bin_id = "NUL";
my $exp_id = "NUL";


#For database LOAD_FILE format
#Print abundancy to MSdata file 

print "BB_ID\tFile_ID\tAbundancy\n";
foreach my $i (0..$sizeFileId) {
	foreach my $k (sort {$a <=> $b} keys %abundance ) {
		if ($abundance{$k}[$i]) {
			#print "$k\t".$file_id[$i]."\t".$abundance{$k}[$i]."\n";
			my $query1 = "SELECT samp_ID FROM sample_info WHERE File_ID = '".$file_id[$i]."'";
			my $statment1 = $dbh->prepare($query1);
			$statment1->execute();
			while(my @data = $statment1->fetchrow_array()) {
				$samp_id = $data[0];	
			}
			#print "$query1\n$samp_id"."^^\n";

			my $query2 = "SELECT bin_ID FROM binBase WHERE BB_ID = $k";
			my $statment2 = $dbh->prepare($query2);
			$statment2->execute();
			while(my @data = $statment2->fetchrow_array()) {
				$bin_id = $data[0];	
			}
			#print "$query2\n$bin_id"."^^\n";

			my $query3 = "SELECT Exp_ID FROM experiment_info WHERE Exp_name = 'miniX".$miniX[$i]."'";
			my $statment3 = $dbh->prepare($query3);
			$statment3->execute();
			while(my @data = $statment3->fetchrow_array()) {
				$exp_id = $data[0];	
			}
			#print "$query3\n$exp_id"."^^\n";

			my $cd_statment = $dbh->prepare($cd_query);
			$cd_statment -> execute($samp_id, $bin_id, $exp_id, $abundance{$k}[$i]);

		} else {
		#print "$k\t".$file_id[$i]."\t0**\n";
		}
	}
}



sub label_row {
	my $cel = $_[0];
	my $reactor = '';
	my $sample = '';
	my $edtvcid = '';
	my $date = '';
	if ($cel =~ /(\w)-(\d)\s/) {
		$reactor = $1;
		$sample = $2;
	} 
	if ($cel =~ /(EDT\d\d\d\d|VCID\d\d\d\d|phoH)/) {
		$edtvcid = $1;
	}
	if ($cel =~ /(\d{1,2}\/\d{1,2}\/\d{2,4})/) {
		my $dat = $1;
		my ($day, $mon, $year) = split (/\//, $dat);
		$date = $year."-$mon-$day";
	} else {
		#print "no date?!: $cel\n";
		$date = '0000-00-00';
	}
	#print "^^^^^^^^^^$cel\t*****$edtvcid\n";
	return ($reactor, $sample, $edtvcid, $date);
}	
	
sub treatment {
	my $cel = $_[0];
	my $gt = 'NULL';
	my $vt = 'NULL';
	if ($cel =~ /^(G\w+)\((p\w+)\)::/) {
		$gt = $1;
		$vt = $2;
	}
	return ($gt, $vt);
}

