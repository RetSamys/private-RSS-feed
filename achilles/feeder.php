<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])){
	$mail = strtolower($_POST['email']); // this is the sender's Email address
	$csv = array_map('str_getcsv', file($_SERVER["DOCUMENT_ROOT"]."/protected/users.csv")); //this is where the CSV is saved relatively to the domain; this makes the CSV an array for easier parsing
	$isSubbed=false;
	$isActive=false;
	$all=false;
	if (isset($_POST['all'])){
		if ($_POST['all']=='yes'){
			$all=true;
		}
	}
	
	foreach($csv as $subber){
		if ($all){
			$feed=$_SERVER["HTTP_REFERER"]."/feed.php?id=".hash_hmac('sha256', strtolower($subber[1]), "YOURSECRETGOESHERE"); //"YOURSECRETGOESHERE" should be replaced with randomly generated data, 128 bits should be fine (16 bytes)
			echo "<p><a class='button' style='background: #000000;
padding: 10px 20px;
cursor: pointer;
color: #FFFFFF;
font-size: 18px;
text-decoration:none;
display:inline-block;' href='".$feed."'>RSS feed &#x25B8;</a>
<p><code>".$feed."</code></p>";
		}
		else{
			if (strtolower($subber[1])==$mail){
				//checks if ID from URL is the same as the email field in the CSV
				$isSubbed=true;
				if (strtolower($subber[0])=="active"){
					//checks if the email in the CSV has the "status" field set as "Active"
					$isActive=true;
				}
			}
		}

	}


	if ($isSubbed){
		if ($isActive){
			//sends mail with link to RSS feed if everything checks out
			$feed=$_SERVER["HTTP_REFERER"]."/feed.php?id=".hash_hmac('sha256', $mail, "YOURSECRETGOESHERE"); //"YOURSECRETGOESHERE" should be replaced with randomly generated data, 128 bits should be fine (16 bytes)
		    echo "<p><a class='button' style='background: #000000;
padding: 10px 20px;
cursor: pointer;
color: #FFFFFF;
font-size: 18px;
text-decoration:none;
display:inline-block;' href='".$feed."'>RSS feed &#x25B8;</a>
<p><code>".$feed."</code></p>";
		}
		else{
			echo "Subscription not ACTIVE";
		}
	}
	else{
		echo "Subscription not FOUND";
	}
}
?>

<!DOCTYPE html>
<body>

<form action="" method="post" style="margin:auto;display:table;margin-top:10vh;"><p>Enter email address:</p>
<input type="text" name="email"><br>
<p>Or get a full list instead:</p>
<input type="checkbox" name="all" value="yes"><br>
<input type="submit" name="submit" value="Submit">
</form>

</body>
</html> 
