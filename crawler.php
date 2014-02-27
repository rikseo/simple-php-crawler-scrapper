<?php 
require_once("config.php");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>title</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta http-equiv="refresh" content="5">
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
<a target="_blank" href="index.php">Import Data</a></br>
<?
/* 
List of Functions:
WriteToCSV($filename, $text)
ExecuteSQL($sql)
GetSingleSQL($sql)
GetMultipleSQL($sql)
DateDiff($date1, $date2)
ShowNum($number) 
ShowThousand($number)
getURL($url)
IsChecked($chkname,$value)
LastDayOfMonth($date="today")
array_cartesian_product($arrays) // get unique pairs
valid_date($date, $format = 'YYYY-MM-DD')
left($str, $length)
right($str, $length) 
 */

 // Run the crawler 20 time each page load
$i = 1;
while ($i <= 25) {
$last_id = getLastID();	
$current_id = $last_id + 1;	 
 
$flag = getData($current_id);
 
echo $current_id." <br/>";
$i++ ;
} 
?>

</body>
</html>
