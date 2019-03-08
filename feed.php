<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id=$_GET["id"]; //this checks what comes after id= in the URL
$csv = array_map('str_getcsv', file($_SERVER["DOCUMENT_ROOT"]."/protected/users.csv")); //this is where the CSV is saved relatively to the domain; this makes the CSV an array for easier parsing
$isSubbed=false;
$isActive=false;

foreach($csv as $subber){

if (hash_hmac('sha256', $subber[1], "YOURSECRETGOESHERE")==$id){
//checks if ID from URL is the same as the email field in the CSV
$isSubbed=true;
if ($subber[0]=="Active"){
//checks if the email in the CSV has the "status" field set as "Active"
$isActive=true;
break;
}
}

}


if ($isSubbed){

if ($isActive){
//returns RSS feed if everything checks out
header('Content-Type: text/xml');


function callback($buffer)
{
  return (str_replace(array(".mp3",".wav",".m4a","feed.php"), array(".mp3&amp;id=".$GLOBALS["id"],".wav&amp;id=".$GLOBALS["id"],".m4a&amp;id=".$GLOBALS["id"],"feed.php?id=".$GLOBALS["id"]), $buffer));
}
ob_start("callback");
readfile($_SERVER["DOCUMENT_ROOT"]."/protected/feed.rss");

//include $_SERVER["DOCUMENT_ROOT"]."/protected/feed.rss"; //this is where the RSS file is saved relatively to the domain
ob_end_flush();



}
else{echo "Subscription not ACTIVE";} //Error message if the email exists, but the "status" field is set to something other than "Active"
}
else{echo "Subscription not FOUND";} //Error message if the email doesn't exist in the CSV


?>
