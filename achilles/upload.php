<?php
$target_dir = $_SERVER["DOCUMENT_ROOT"]."/protected/audio/";
$FileName=basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $FileName;
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$title=$_POST["title"];
$desc=$_POST["description"];

if (strlen($title)<3){
	$title="New Podcast!"; //This is a default title
	echo "Title replaced with default text (3 characters or more required). <br>";
}else{echo "Title accepted! <br>";}
if (strlen($desc)<3){
	$desc="Get a brand-new episode right here!"; //This is a default description
	echo "Description replaced with default text (3 characters or more required). <br>";
}else{echo "Description accepted! <br>";}

$feedURL=$_SERVER["DOCUMENT_ROOT"]."/protected/feed.rss";
$episode=$_SERVER["HTTP_REFERER"].'/podcast.php?file='.$FileName;
$dom = new DOMDocument;
$dom->load($feedURL);
$counted=$dom->getElementsByTagName('item')->length;
$dom->getElementsByTagName("lastBuildDate")->item(0)->nodeValue = date('D, d M Y H:i:s T', time());
$dom->getElementsByTagName("pubDate")->item(0)->nodeValue = date('D, d M Y H:i:s T', time());

$xpath = new DOMXPath($dom);
$channelNode = $xpath->query('/rss/channel')->item(0);
$first = $channelNode->getElementsByTagName( 'item' )->item( 0 );
if ($channelNode instanceof DOMNode) {
    // create example item node (this could be in a loop or something)
    $item = $channelNode->insertBefore($dom->createElement('item'),$first); //order from newest to oldest

    // the title
    $item->appendChild(
        $dom->createElement('title', '#'.($counted+1).' '.$title)
    );

    // the link
    $item->appendChild(
        $dom->createElement('link', $episode)
    );

    // the date
    $item->appendChild(
        $dom->createElement('pubDate',date('D, d M Y H:i:s T', time()))
    );


    // the description
    $item->appendChild(
        $dom->createElement('description', $desc)
    );

    // the file
    $encl=$dom->createElement('enclosure', '');
    $encl->setAttribute("url",$episode);
    $encl->setAttribute("type",'audio/mpeg');
    $item->appendChild($encl);
    $encl=$dom->createElement('guid', $episode);
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 524288000) { //this is what a 500 MB upload limit looks like
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($FileType != "mp3" && $FileType != "wav" && $FileType != "m4a") {
    echo "Sorry, only MP3, WAV & M4A files are allowed.<br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
        $dom->save($feedURL);
    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }
}

?>
