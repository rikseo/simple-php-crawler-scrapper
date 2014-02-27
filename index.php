<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>title</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
 <meta http-equiv="refresh" content="60">
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
<a target="_blank" href="crawler.php">launch crawler</a>
<?php 
require_once("config.php");
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

 
$last_id = getLastID();

echo "<h3>Crawler has completed ".$last_id." pages </h3>";

$sql = "SELECT source.id , source.url, source.html FROM source LEFT JOIN DATA ON data.id = source.id WHERE ((data.completed IS NULL) OR (data.completed = 0) ) AND (source.completed = 1)" ;
$result = GetMultipleSQL($sql);
while ($row = mysql_fetch_array($result)) {


$data = $row['html'];
//improve efficiency of this algorithm
$data = stripslashes($data);
$source_id = $row['id'];
$url = $row['url'];
 
//Company Name
preg_match('/100%"><h1>(.*)<\/h1>/i', $data, $match);
$company_name = $match[1];
 
//Contact information
preg_match('/h1>[\r\n]+<p>(.*)<\/p><p>/i', $data, $match2);
$contact_dump = $match2[1];
 
//Discounts Available
preg_match('/p><strong>Discounts Available<\/strong>(.*)<\/p>/i', $data, $match3);
$discount_text = $match3[1];
 
// Get GEO LatLong
preg_match('/GLatLng\(([0-9\.\-]*)\,([0-9\.\-]*)/i', $data, $match4);
$lat = $match4[1];
$long = $match4[2];
 
// Working with Contact information
//Contact information
preg_match('/<p>([\w\s#\.\,\-\'\&\/]+)?<br\/>([\w\s#\.\,\-\'\&]+),\s+([A-Z]+)\s+([\w]+)<br\/>(\(([0-9]+)\)\s+([0-9\-]+))?<\/p><p>/i', $data, $output);
$flag=1;
if (isset($output[1])) {$address = $output[1];} else {$address = "";$flag = 0;}
if (isset($output[2])) {$city  = $output[2];} else {$city = "";$flag = 0;}
if (isset($output[3])) {$state   = $output[3];} else {$state  = "";$flag = 0;}
if (isset($output[4])) {$zip   = $output[4];} else {$zip  = "";$flag = 0;}
if (isset($output[5])) {$phone_no  = $output[5];} else {$phone_no  = ""; }
if (isset($output[6])) {$area_code   = $output[6];} else {$area_code   = ""; }
if (isset($output[7])){$phone = $output[7];} else {$phone = ""; }
 
 
//check if record exists already
$sql = "SELECT id, completed FROM data WHERE source_id = $source_id   LIMIT 1";
$rst = GetSingleSQL($sql);

IF ($rst) {

	IF ($rst['completed'] == '1') {
	
		$message = "Record already Exists and marked completed";
	
		} else {
$url = mysql_real_escape_string($url);
$company_name = mysql_real_escape_string($company_name);
$contact_dump = mysql_real_escape_string($contact_dump);
$address = mysql_real_escape_string($address);
$discount_text = mysql_real_escape_string($discount_text);
$city = mysql_real_escape_string($city);
				//Update the record
			$sql = "UPDATE data "; 
			$sql .=	"SET	 url =	 '$url'  , ";
			$sql .=	" 	`company_name` =  '$company_name'  ,  ";
			 $sql .=	" 	`contact_dump` =  '$contact_dump'  , ";
			 $sql .=	"   `address` =	'$address' , ";
			$sql .=	" 	 city  =	'$city' , ";
			$sql .=	" 	 state  =	'$state' , ";
			$sql .=	"  	 zip  =	'$zip' , ";
			$sql .=	"  	 area_code  =	'$area_code' , ";
			$sql .=	" 	 phone_no  =	'$phone_no' , ";
			$sql .=	" 	 discount_text  =	'$discount_text' , ";
			$sql .=	" 	`long` =	'$long' , ";
			$sql .=	" 	`lat` =	'$lat' , ";
			$sql .=	" 	`completed` = 	'$flag'  "; 
			$sql .=	"WHERE	`source_id` =	$source_id ";
		if ($flag==0){ echo $sql;}
			ExecuteSQL($sql);
			$message = "Record was updated";
	
	}


} else {
$url = mysql_real_escape_string($url);
$company_name = mysql_real_escape_string($company_name);
$contact_dump = mysql_real_escape_string($contact_dump);
$address = mysql_real_escape_string($address);
$discount_text = mysql_real_escape_string($discount_text);
$city = mysql_real_escape_string($city);

//if record not found
$sql = "INSERT INTO `data` (
   `source_id`,
  `url`,
  `company_name`,
  `contact_dump`,
  `address`,
  `city`,
  `state`,
  `zip`,
  `area_code`,
  `phone_no`,
  `discount_text`,
  `long`,
  `lat`,
  `completed` 
) 
VALUES
  (
    $source_id,
    '$url',
    '$company_name',
    '$contact_dump',
    '$address',
    '$city',
    '$state',
    '$zip',
    '$area_code',
    '$phone_no',
    '$discount_text',
    '$long',
    '$lat',
    '$flag' ) ";
if ($flag==0){ echo $sql;}
ExecuteSQL($sql);
$message = "New Data Added";
}
echo $row['id']." : ".$message."<br/>";
 
}  
?>
</body>
</html>
