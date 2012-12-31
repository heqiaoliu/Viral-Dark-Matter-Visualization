##################################################################
#Description: To convert input xls file to tab deliminated file
#	for MySQL input, especially LOAD_FILE
#INPUT: xls file, look for "label", "file id"
#OUTPUT: *.MSdata; *.MSxls; *.SampleInfo

##################################################################

use strict;
use warnings;

my $input = $ARGV[0]; #file: miniX85780_mod.txt
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
#		foreach my $s6 (0..$sizeCol) {
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
		$flagFormat++;
	}elsif ($title eq 'file id') {
		foreach my $s2 ($f..$sizeCol) {
			my $i = $s2 - $f;
			$file_id[$i] = $col[$s2];
		}
		$flagFormat++;
	}elsif ($title =~ /^\d+$/) { #Done with title, input abundance
		print "Start abundance:?\n";
		$flagFormat++;
	}elsif ($title eq 'treatment') {
		foreach my $s3 ($f..$sizeCol) {
			my $i = $s3 - $f;
			my $l2 = $col[$s3];
			($genotype[$i], $vector[$i]) = &treatment($l2);
		}
	} else {		
			print "$title\n";
	}
	}
}
close(IN);

if ($flagFormat < 3) {
	print "ERROR: Format error\n";
	die "ERROR: Format error - missing label, or file id, or abundance data.\n";
}

#Print Sample information to SampleInfo file
$specie = 'E.Coli';
my $comment = $specie;

my $sizeFileId = $#file_id;
open (SAMIN, ">$input.SampleInfo") or die "can't output";
print SAMIN "Reactor_ID\tSample_ID\tDate\tVc_id\tFile_ID\tComment\n";
foreach my $i (0..$sizeFileId) {
	print SAMIN $reactor[$i],"\t".$sample[$i]."\t".$date[$i]."\t";
	if ($edt[$i]) {
		print SAMIN $edt[$i]."\t";
	} elsif ($vcid[$i]) {
		print SAMIN $vcid[$i]."\t";
	} else {
		print SAMIN $ev[$i];
	}
	print SAMIN $file_id[$i]."\t$comment"."\n";
}
close(SAMIN);

#For database LOAD_FILE format
#Print abundancy to MSdata file 
open (MSIN, ">$input.ChemoData") or die "can't output";
print MSIN "BB_ID\tFile_ID\tAbundance\n";
foreach my $i (0..$sizeFileId) {
	foreach my $k (sort {$a <=> $b} keys %abundance ) {
		print MSIN "$k\t".$file_id[$i]."\t".$abundance{$k}[$i]."\n";
	}
}
print MSIN "\n";
close(MSIN);


#For combine to excel sheet format
=cut
my $c = 0;
my %countev = ();
open (XLS, ">$input.MSxls") or die "can't output";
print XLS "BB_ID";
foreach my $i (0..$sizeFileId) {
	print XLS "\t".$ev[$i];
	my $e = $ev[$i];
	if ($countev{$e}) {
		$countev{$e} += 1;
	}else {
		$countev{$e} = 1;
	}
	print XLS "_".$countev{$e};
}
print XLS "\n";

foreach my $k (sort {$a <=> $b} keys %abundance ) {
	print XLS "$k"."\t";
	foreach my $i (0..$sizeFileId) {
		print XLS $abundance{$k}[$i]."\t";
	}
	print XLS "\n";
}
print XLS "\n";
close(XLS);
=cut


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
		$date = $1;
	} else {
		print "no date?!\n";
	}
	#print "^^^^^^^^^^$cel\t*****$edtvcid\n";
	return ($reactor, $sample, $edtvcid, $date);
}	
	
		


