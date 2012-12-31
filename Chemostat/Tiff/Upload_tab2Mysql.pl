use strict;
use warnings;
use DBI;
#use TEXT::CSV;

my $dbname = "viral_dark_matter";
my $user = "nturner";
my $password = "LOB4steR";

# open the database connection
my $db = DBI->connect("DBI:mysql:$dbname", $user, $password) or die "Cannot open database\n";

my $terminator = "\t";
my $input = $ARGV[0]; #combine_10846.Sample_info
$input = lc($input);
my ($exp_name,$table) = split(/./,$input); 
open (IN, "$input") or die "can't open file $input:$!";
my $row1 = <IN>;
my @attr = split(/\t/,$row1);
my $sizeCol = $#attr;
#print "$sizeCol\n";
my $q1 = '';
my $q2 = '';
#foreach my $a (0..$sizeCol) {
foreach my $a (0..3) {
	$q1 .= "$attr[$a]".",";
	$q2 .= "?,";
}		
chop($q1);

my $query = "INSERT INTO $table ($q1) VALUES ($q2)";
my $pquery = $db->prepare($query);
my $rv = '';

while(<IN>) {
	chomp;
	my @line = split(/\t/,$_);
	#foreach my $i (0..$sizeCol) {
	foreach my $i (0..3) {
		#print $line[$i]."*\t";
		my $j = $i+1;
		$pquery->bind_param($j,$line[$i]);
	}
	$rv = $pquery->execute or die $pquery->errstr;	
	print "**$rv\n";
exit;
}
$pquery->finish;


exit;
	
=cut
#main loop for each of the passed files
foreach my $argnum (0..$#ARGV) {
	if (!open($fh, '<', $ARGV[$argnum])) {
		print "Warning: Could not open " . $ARGV[$argnum] . " so file skipped\n";
	} else {
	# if there is an ignore setting, loop around discarding those lines
	for(my $i = 0; $i < $ignore; $i++) {
		my $row = $csv->getline($fh);
		print "$row\n";
	}
	}
}
exit;
	#new perform the database write
	while (my $row = $csv->getline($fh)) {
		$prep = $db->prepare("INSERT INTO $table VALUES (\"" . join('","', $csv->fields()) . "\")")
			or die "Cannot prepare database " . $db->errstr() . "\n";
		if (!$prep->execute()) { die "Failed to write row " . $db->errstr() . "\n"; }
	}




	$csv->eoff or $csv->error_diag();
	print "Successfully written $dbname into $table using " . $ARGV[$argnum]. "\n";
	}
}


