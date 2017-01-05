<?php
/*
Run out of box PHP contact us script 

Introduction
------------
Run out of box PHP contact us script, It does not need modification, it will detect the domain and send email containing the contact message to info@yourdomain
Whatever fields in your form it will be detected and be send with email

Background
----------
Lots of contact us scripts are available over the internet. to use any other script the modification of the PHP file is needed. 
This script is very useful to who do not know PHP and to the beginner of PHP

Using the code
--------------
Upload the script folder to your www root directory 

From Action
-----------
<form action="contact2us/send.php" method="POST">

Fields Names
------------
Use from_email, from_name, subject, message and captcha as main fields' names in your form.

Captcha
-------
If you don't wish to use captcha then no change is needed and the 1st line of code will be:
$captcha = false;
If you wish to use captcha then change the 1st line of code to be:
$captcha = true;
to use captcha include the following in your form
<img src="contact2us/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
Enter the above text: <input id="captcha" name="captcha" type="text"><br>

Thank you url
-------------
Put your own $thank_you_url in the 2nd line of the code.

Download
--------
http://www.codeproject.com/Articles/NewPast#Article
*/
$captcha = false;
$thank_you_url = "thank-you.html";

// No more settings
$domain = '';
if(isset($_SERVER['HTTP_HOST'])){
	$domain = str_ireplace('www.','',$_SERVER['HTTP_HOST']);	
}else{
	$domain = str_ireplace('www.','',$_SERVER['SERVER_NAME']);	
}
$to='info@'.$domain;
$from_email	= '';
$from_name 	= '';
$subject	= '';
$message	= '';

session_start();

@$from_email= $_POST['from_email'];
@$from_name = $_POST['from_name'];;
@$subject	= $_POST['subject'];
@$message	= $_POST['message'];
$cc			= '';
$bcc		= '';

//Checking the referrer page and stop the script if it called directly
$REFERER = $_SERVER['HTTP_REFERER'];
if(!preg_match("@^http:\/\/(www\.)?$domain\/@",$REFERER)){
	die("This page can't be call directly");
}

$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$Err  = '';

//Validate user email and user name to prevent injecting wrong command in 
//the header parameter of the mail function
if(!$from_email) $from_email = "web_page@$domain";
if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
	$Err .= 'Invalid email format<br>'; 
	$from_email = "web_page@$domain";
}

if ($cc && !filter_var($cc, FILTER_VALIDATE_EMAIL)) {
	$Err .= 'Invalid cc email format<br>'; 
	$cc = '';
}	
if ($bcc && !filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
	$Err .= 'Invalid bcc email format<br>'; 
	$bcc = '';
}	
if ($from_name && preg_match('/(\W|\S)/',$from_name)){
	$Err .= 'Only letters and white space allowed<br>';
	$from_name = 'web_page';
}
if ($from_name && !preg_match('/^[A-Za-z ]+$/',$from_name)){	
	$from_name = "=?UTF-8?B?".base64_encode($from_name)."?=";
}
//Validate subject and encode it if needed to prevent send frailer
if ($subject && !preg_match('/^[A-Za-z ]+$/',$subject)){
	$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
}
if($captcha){
	
	if(empty($_SESSION['captcha'] ) || strcasecmp($_SESSION['captcha'], $_POST['captcha']) != 0){
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		die("The captcha code does not match!<br>");
	}
}

$headers='';
if(true){//html
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type:text/html;charset=utf-8\r\n";
}else{
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type:text/plain;charset=utf-8\r\n";	
}
$headers .= "From: $from_name<$from_email>\r\n";
if($cc ) $headers .= "Cc: $cc\r\n";
if($bcc) $headers .= "Bcc: $bcc\r\n";

$message_html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
$message_html .= "
<style>
p, body 
{
	font-size : 12px; 
}
h2
{
	font-size : 14px;
}
</style>
</head><body>";

//Seek all posted variables
foreach ($_POST as $key => $value)
{
	$value = htmlspecialchars($value);
	$message_html .= "<h2>$key</h2><p>$value</p>";
}
$message_html .= "<h2>Errors</h2><p>$Err</p>";
$REFERER = htmlspecialchars($REFERER);
$message_html .= "<h2>REFERER</h2><p>$REFERER</p>";
$message_html .= "<h2>IP</h2><p>$ip</p>";
$message=htmlspecialchars($message);
$message_html .= "<h2>message</h2><p>$message</p>";
$message_html .= '</body></html>';
mail($to, $subject, $message_html,$headers);
//Redirect to thank you URL
header('Location: '. $thank_you_url);