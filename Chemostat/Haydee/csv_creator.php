

<?php
$results = $_POST['results'];
download_csv_results($results, 'result.csv');
exit();

function download_csv_results($results, $name = NULL)
{
    if( ! $name)
    {
        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "w");
	$array = explode("\n", $results);
    foreach($array as $result)
    {
        fputcsv($outstream, explode("\t",$result));
    }

    fclose($outstream);
}
?>
