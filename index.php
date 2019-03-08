<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])){
	$to = $_POST['email']; // this is the sender's Email address
	$csv = array_map('str_getcsv', file($_SERVER["DOCUMENT_ROOT"]."/protected/users.csv")); //this is where the CSV is saved relatively to the domain; this makes the CSV an array for easier parsing
	$isSubbed=false;
	$isActive=false;
	$boundary = uniqid('np');
	
	foreach($csv as $subber){
		
		if ($subber[1]==$to){
			//checks if ID from URL is the same as the email field in the CSV
			$isSubbed=true;
			if ($subber[0]=="Active"){
				//checks if the email in the CSV has the "status" field set as "Active"
				$isActive=true;
			}
		}

	}


	if ($isSubbed){
		if ($isActive){
			//sends mail with link to RSS feed if everything checks out
			$feed=$_SERVER["HTTP_REFERER"]."/feed.php?id=".hash_hmac('sha256', $to, "YOURSECRETGOESHERE"); //"YOURSECRETGOESHERE" should be replaced with randomly generated data, 128 bits should be fine (16 bytes)
		    $from = "noreply@".$_SERVER["SERVER_NAME"]; // this is your Email address
			$subject = "Private Podcast Feed";
			$message_html = "<html>
<head>
<title>Private Podcast Feed</title>
</head>
<body>
	<div>
		<h1>Private Podcast Feed</h1>
		<p>
			Here is your very own and <em>very secret</em> link to the podcast RSS feed! You can use this feed URL to add the podcast to most of your favourite podcast services or other RSS readers.</p>
		<p>
			<a class='button' style='background: #000000; padding: 10px 20px; cursor: pointer; color: #FFFFFF; font-size: 24px; text-decoration:none; display:inline-block;' href='".$feed."'>RSS Feed</a>
		<p>
			Or copy this URL: <code>".$feed."</code></p>
	</div>
</body>
</html>";
			$message_plain = "Private Podcast Feed

Here is your very own and very secret link to the podcast RSS feed! You can use this feed URL to add the podcast to most of your favourite podcast services or other RSS readers.

".$feed."

";
			$message = "This is a MIME encoded message.";
			$message .= "\r\n\r\n--" . $boundary . "\r\n";
			$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
			$message .= $message_plain;
			$message .= "\r\n\r\n--" . $boundary . "\r\n";
			$message .= "Content-type: text/html;charset=utf-8\r\n\r\n";
			$message .= $message_html;
			$message .= "\r\n\r\n--" . $boundary . "--";
			// Always set content-type when sending HTML email

			$headers = "Reply-To: ".$from."\n";	
			$headers .= "Return-Path: ".$from."\n";	
			$headers .= "From:" . $from."\n";
			$headers .= "To:" . $to."\n";
			$headers .= "Date:" .date("r")."\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n";
			$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
			mail($to,$subject,$message,$headers,"-f".$from);
		}
	}
    echo "
	<div>
		<p>Thank you. If you have an active subscription, you should receive an email - otherwise please contact us (and check your spam folder).</p>
	</div>";
    }
?>

<!DOCTYPE html>
<head>
	<title>Private Podcast Feed</title>
</head>
<body>

			<form action="" method="post" style="display:table;">
				<p><label for="email">To receive a link to the podcast RSS feed, please enter your email below:</p>
				<p><input type="text" name="email"></label></p>
				<p><input type="submit" name="submit" value="Submit"></p>
			</form>
</body>
