<?php
$FileName=basename($_FILES["fileToUpload"]["name"]);
$target_file = $_SERVER["DOCUMENT_ROOT"]."/protected/users.csv";
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


$feedURL=$_SERVER["DOCUMENT_ROOT"]."/protected/feed.rss";
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($FileType != "csv") {
    echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0;
}

$mailsExists=false;
$statusExists=false;
$csv = array_map('str_getcsv', file($_FILES["fileToUpload"]["tmp_name"])); //this makes the CSV an array for easier parsing
foreach($csv as $subber){
	if (strtolower($subber[0])=="active"){
		$statusExists=true;
		break;
	}
}
foreach($csv as $subber){
	if (strpos($subber[1], '@') !== false){
		$mailsExists=true;
		break;
	}
}

if ($mailsExists==false){
	echo "Sorry, no emails in the 2nd column found.";
	$uploadOk = 0;
}
if ($statusExists==false){
	echo "Sorry, first column has no entries with status 'Active'.";
	$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>
