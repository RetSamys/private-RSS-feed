# private-RSS-feed
a PHP based attempt at making user-specific access to an RSS podcast feed and podcast episodes

## How to set up the files
Before you can use these files, you'll want to change a couple of things:
* There is a "secret" to generate hash values that you'll need to replace. Look for "YOURSECRETGOESHERE" in download.php, index.php, feed.php and achilles/feeder.php and replace it with a new secret. Ideally, with randomly generated data, 128 bits should be fine (16 bytes).
* Additionally to the above, index.php might need some changes. For one, of course, you can change the content of the email message (which you'll have to do twice: once for the HTML version and once for the plain text version), but more importantly, check the $from email address. This is the most bothersome part of sending mails via PHP: email providers are quick to mark it as spam, so you'll probably need to test if the address you put in works OK.
* The directory "protected" needs to be... protected. Use chmod 700
* protected/feed.rss needs to be adapted to your preferences a lot (but only once - the rest should be doable with achilles/upload.html)
* After you saved protected/feed.rss, I recommend saving a copy under protexted/audio just in case you have to re-do the feed
* The directory "achilles" is where admins can easily add new episodes, update the list of users and generate RSS links, so make sure you pick a new password in achilles/.htpasswd (if this is your first time working with .htpasswd, I recommend looking up a htpasswd generator online)
* There is a default title and a default description in achilles/upload.php if you want to change that

## Features
* Users in the user list can request an automatically sent email to receive a link to the RSS feed.
* Each user has their own secret link to the RSS feed. The link is generated with a hash value out of the user's email address, so if they are removed from the list and then added again at a later point, the link stays the same as long as the email address stays the same.
* The RSS feed and the episodes are only available for users in the user list
* Admins can access a password protected directory with the following tools:
  * `/achilles/upload.html` Admins can upload new episodes, which are automatically added to the RSS feed. Optional: Add your own title and description when uploading (otherwise, there is a default title and a default description - title and description need to have 3 characters or more). All podcast titles will have an indication of what episode number it is (#1, #2, #3 etc.) Note: This should actually be done with tags (not a feature), but given the default title, I added it to the title anyway.
  * `/achilles/uploadcsv.html` Admins can update the list of users. Works only with CSV files. Current format: `status,email` - Status has to be `Active` and email has to be the user's email address
  * `/achilles/feeder.php` Admins can generate a user's link to the RSS feed manually, for example if the mail didn't send.


## Known bugs
* The native Apple podcast app is a terrible, terrible thing and might stop the episode from playing after closing the app.
