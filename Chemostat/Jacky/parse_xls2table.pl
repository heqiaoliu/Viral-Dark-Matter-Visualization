##################################################################
#Description: To convert input xls file to tab deliminated file
#	for MySQL input
#INPUT: xls file, look for "label", "file id"
#OUTPUT: *.chemo_data; *.binBase; *.sample_info

##################################################################

use strict;
use warnings;

my $input = $ARGV[0]; #file: miniX85780
my $flag = 0;
my @reactor = ();
my @sample = ();
my @edt = ();
my @vcid = ();
my @date = ();
my @file_id = ();
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
my $flagFormat = 0;

open (IN, $input) or die "Can't open file $input:$!";
while (<IN>) {
	chomp;
	my @col = split(/\t/,$_);
	my $sizeCol = $#col;
	if ($datastart == 0 and $col[0] ne '') {
			#dataset starts	
			$datastart = 1;
                        my $nc = 0;
                        foreach my $n ('Name','ret','quant','BinBase','mass','kegg','pubchem') {
                                if ($col[$nc] !~ /$n/i) {
                                        print "ERROR:Format error - Column name is". $col[$nc].", which should be $n\n";
                                        die "ERROR: Format error - CLoumn name:$!";
                                }
                                $nc++;
                        }
			next;
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
			}
		}
=cut	
	}elsif ($title eq 'organ') {
		if ($col[$f] =~ /cell/i) {
		       $organ = 'cells';
		}else {
			print 'new organ';
		}
	}elsif ($title eq 'species') {
		my $c1 = $col[$f];
		if ($c1 =~ /coli/i) {
			$specie = 'E.Coli';
		}
=cut
		$flagFormat++;
	}elsif ($title eq 'file id') {
		foreach my $s2 ($f..$sizeCol) {
			my $i = $s2 - $f;
			$file_id[$i] = $col[$s2];
		}
		$flagFormat++;
	}elsif ($title eq 'treatment') {
                foreach my $s3 ($f..$sizeCol) {
                        my $i = $s3 - $f;
                        my $l2 = $col[$s3];
                        ($genotype[$i], $vector[$i]) = &treatment($l2);
                }
	}else {
		#print "$title\n";
	}
	}	
}

close(IN);

if ($flagFormat < 2) {
        print "ERROR: Format error\n";
        die "ERROR: Format error - missing label, or file id, or abundance data.\n";
}


#Print Sample information to SampleInfo file
$specie = 'E.Coli';
my $comment = $specie;
my %countev = ();
my $sizeFileId = $#file_id;
open (SAMIN, ">$input.sample_info") or die "can't output $input.sample_info";
print SAMIN "Reactor_ID\tSample_ID\tbact_external_id\tvd_id\tRep\tGenotype\tVector\tSpecie\tMs_date\tFile_ID\tComment\n";
foreach my $i (0..$sizeFileId) {
	print SAMIN $reactor[$i],"\t".$sample[$i]."\t";
	if ($edt[$i]) {
		print SAMIN $edt[$i]."\t";
	} else {
	       print SAMIN "NULL\t";
       	}	       
	
	if ($vcid[$i]) {
		my ($d1, $d2) = split(/ID/,$vcid[$i]);
		print SAMIN $d2."\t";
	} else {
		print SAMIN "NULL\t";
	}
        
	my $e = $ev[$i];
        if ($countev{$e}) {
                $countev{$e} += 1;
        }else {
                $countev{$e} = 1;
        }
	print SAMIN $countev{$e}."\t";
	if ($genotype[$i]){
		print SAMIN $genotype[$i]."\t".$vector[$i]."\t";
	} else {
		print SAMIN "NULL\tNULL\t";
	}
	print SAMIN $specie."\t".$date[$i]."\t";
	print SAMIN $file_id[$i]."\t$comment"."\n";
}
close(SAMIN);

#For database LOAD_FILE format
#Print abundance to chemo_data file 
open (MSIN, ">$input.chemo_data") or die "can't output";
print MSIN "File_ID\tBB_ID\tExp_name\tAbundance\n";
foreach my $i (0..$sizeFileId) {
	foreach my $k (sort {$a <=> $b} keys %abundance ) {
		print MSIN $file_id[$i]."\t"."$k\t".$input."\t".$abundance{$k}[$i]."\n";
	}
}
print MSIN "\n";
close(MSIN);

#Print BinBase information to Binbase file
open (BB, ">$input.binBase") or die "can't open output:$!";
print BB "BB_ID\tBB_Name\tKegg_ID\tPubChem_ID\tRet_Index\tQuant_mz\tMass_spec\n";
foreach my $i (sort {$a <=> $b} keys %bbName) {
	print BB "$i\t".$bbName{$i}."\t".$keggId{$i}."\t".$pubchemId{$i}."\t".$retIndex{$i}."\t".$quantmz{$i}."\t".$massSpec{$i}."\n";
}
close(BB);


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
		$date = '0000-00-00';
	}
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


