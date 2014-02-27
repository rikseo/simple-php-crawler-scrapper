<?php
ini_set("display_errors","1");
ini_set("memory_limit", "200M");
define("DB_HOST", "localhost");
define("DB_NAME", "your-db-name-here");  // put mysql database name here 
define("DB_USER", "mysql-user-name"); // put mysql username here 
define("DB_PASS", "mysql-password"); // put mysql password here 
$username = DB_USER;
$password = DB_PASS;
$hostname = DB_HOST; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");

$selected = mysql_select_db(DB_NAME,$dbhandle) 
  or die("Could not select DATABASE");

	






function WriteToCSV($filename, $text)

{

	if (file_exists($filename)) { unlink ($filename); }

	$fh = fopen($filename, 'w') or die("can't open file"); 

	fwrite($fh, $text); 

	fclose($fh); 

}

	
function ExecuteSQL($sql)

{

$result = mysql_query($sql);

if (!$result) die('Could not connect: ' . mysql_error());

}



function GetSingleSQL($sql)

{

$result = mysql_query($sql);

if (!$result) die('Could not connect: ' . mysql_error());

 $row = mysql_fetch_array($result);

 return $row;

}



function GetMultipleSQL($sql)

{

$result = mysql_query($sql);

if (!$result) die('Could not connect: ' . mysql_error());

return $result;

}



function DateDiff($date1, $date2)

{



	$diff =  strtotime($date1) - strtotime($date2);

	$years = floor($diff / (365*60*60*24)); 

	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 

	return ($years."y".$months."m");



}



function ShowNum($number) {

$new_number = number_format($number, 2, '.', ',');

return $new_number;

}



function ShowThousand($number)

{

	$new_number = number_format($number/1000,0);

	return $new_number;

}

function getURL($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);


    return $ret;
}

	
	
function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }






            }
        }
        return false;
    }
	

function LastDayOfMonth($date="today") {

$timestamp = strtotime($date);
$lastDate = date("Y-m-t", $timestamp);
								
return $lastDate;				
				
	}


function array_cartesian_product($arrays)
{
    $result = array();
    $arrays = array_values($arrays);
    $sizeIn = sizeof($arrays);
    $size = $sizeIn > 0 ? 1 : 0;
    foreach ($arrays as $array)
        $size = $size * sizeof($array);
    for ($i = 0; $i < $size; $i ++)
    {
        $result[$i] = array();
        for ($j = 0; $j < $sizeIn; $j ++)
            array_push($result[$i], current($arrays[$j]));
        for ($j = ($sizeIn -1); $j >= 0; $j --)
        {
            if (next($arrays[$j]))
                break;
            elseif (isset ($arrays[$j]))
                reset($arrays[$j]);
        }
    }
    return $result;
}

function valid_date($date, $format = 'YYYY-MM-DD'){
    if(strlen($date) >= 8 && strlen($date) <= 10){
        $separator_only = str_replace(array('M','D','Y'),'', $format);
        $separator = $separator_only[0];
        if($separator){
            $regexp = str_replace($separator, "\\" . $separator, $format);
            $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('YYYY', '\d{4}', $regexp);
            $regexp = str_replace('YY', '\d{2}', $regexp);
            if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
                foreach (array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value) {
                    if ($key == 'YY') $year = '20'.$value;
                    if ($key == 'YYYY') $year = $value;
                    if ($key[0] == 'M') $month = $value;
                    if ($key[0] == 'D') $day = $value;
                }
                if (checkdate($month,$day,$year)) return true;
            }
        }
    }
    return false;
}

function left($str, $length) {
     return substr($str, 0, $length);
	}

function right($str, $length) {
		 return substr($str, -$length);
	}
function getTextBetweenTags($tag, $html, $strict=0)
{
    /*** a new dom object ***/
    $dom = new domDocument;

    /*** load the html into the object ***/
    if($strict==1)
    {
        $dom->loadXML($html);
    }
    else
    {
        $dom->loadHTML($html);
    }

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false;

    /*** the tag by its tag name ***/
    $content = $dom->getElementsByTagname($tag);

    /*** the array to return ***/
    $out = array();
    foreach ($content as $item)
    {
        /*** add node value to the out array ***/
        $out[] = $item->nodeValue;
    }
    /*** return the results ***/
    return $out;
}
function getData($current_id) {
$sql = "select url from source where id=$current_id" ;
$row = GetSingleSQL($sql);
$data = getURL ($row['url']);
	if ($data!="") {
	$data = mysql_real_escape_string($data);
	$sql = "update source set html='$data' , completed='1' WHERE id = $current_id"; 
	ExecuteSQL($sql);
	return true;
	}
	else {
	return false;
	}
}

function getLastID() {
$sql = "SELECT 
    `id`
    , `completed`
	FROM
    `source`
	WHERE (`completed` = 1)
	ORDER BY `id` DESC LIMIT 1";
$row = GetSingleSQL($sql);
$last_id = $row['id']; 
 return $last_id;
 }

?>