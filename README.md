banes-police-crimes
===================

Script for importing street level crime data for Bath & North East Somerset into Socrata, pulled from data.police.uk

## Accessing the data

Drop in your credentials for the following at the top of the index.php file: 

* root_url - Your Socrata datastore root URL
* app_token - You can get this by creating a new app in your Socrata profile
* database_id - This is the 8-character code at the end of your dataset URL. It should be in the format XXXX-XXXX
* username - This is your Socrata username, mostly likely an email address
* password - Your Socrata password

Now run the file. An output of all months imported into Socrata will show along with a 'Done!' message when completed.