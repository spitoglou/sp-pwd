![sp-pwd logo](http://agile-greece.cssa.tk/wp-content/uploads/2013/01/sp_pwd_Logo.png)

**_This Project is no longer under active development and stays here for historical and reference purposes._** 

*(Yet Another)* **Simple PHP password strength test** (REST web service)

##Description

The script is a web service, developed by the REST standards, which takes as a URI argument the password in question and gives back a JSON formatted response with a score (scale 0 to 100) or an error message (if something goes wrong).

###Algorithm

The processing of the given password takes place in three steps. 
#####1st step
The initial score is calculated based on the length of the password (maximum 60/100 for passwords longer than 12 characters) and the existence of both uppercase and lowercase letters (10/100), numbers (10/100) and special characters (20/100).
#####2nd Step
The result of the previous step is leveled based on the concurrent existence of the three character sets of the previous step.
#####3rd step
Finally, the result is once again leveled based on the rating of the (unofficial) Google password rating service (whose algorithm I do not know exactly but surely there is some kind of dictionary dependency eg. the password "P@ssw0rd" although it is 8 characters long and contains uppercase, number and special rates "1" -the weakest possible). 

##Usage
Once installed in a file system of a PHP compatible web server, it can be called:   
    `GET http://your.server.address/folder/index.php/password/{your_password}`


If you do something wrong (use other http verb than GET, provide too may or too few arguments, forget to use the word "password" as the first argument), you will get an HTTP ERROR STATUS 400 (Bad request) and a message: `"{"error":"Not a supported request by this web service"}"`.

If everything is OK, you will get a JSON Response like this (example used "P@ssw0rd"):  
`{  
    "checked-password": "P@ssw0rd",  
    "contains-uppercase-lowercase": "Yes",  
    "contains-numbers": "Yes",  
    "contains-special-chars": "Yes",  
    "google-rate": "1",  
    "sp-score": "22.50"  
}   `

**"sp-score"** is the final "verdict" of the script (scale weak 0-100 strong).

##Test it on-line

~~You can test this script and it's results on-line~~, using:  
 ~~`http://cscloud.netne.net/api-test/index.php/password/{your_password}`~~

##Official Web Page
[![agile-greece](http://agile-greece.cssa.tk/wp-content/uploads/2013/01/ag_Logo3.png)](http://agile-greece.cssa.tk/?page_id=208)
