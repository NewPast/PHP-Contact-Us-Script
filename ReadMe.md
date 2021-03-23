A PHP script uses to send contact-us form data to the webmaster, It is
simple and runs without modification

PHP Contact Us Script Downloads
-------------------------------
### ♦ [Download of PHP Contact Us Script](https://www.miniindustry.com/d/php-contact-us-script)
### ♦ [PHP Contact Us Script on CodeProject](https://www.codeproject.com/KB/PHP/1139299.aspx)
### ♦ [Get it from GitHub](https://github.com/NewPast/PHP-Contact-Us-Script)

Introduction
------------
Run out of box PHP contact us script, it does not need modification, it
will detect the domain and send an email containing the contact message
to info@exmple.com whatever fields are in your form; it will detect them
and send the form data with email.

System requirements
-------------------
A website with hosting with PHP
### PHP Version
PHP 5.6, PHP 7.0, PHP 7.1, PHP 7.2, PHP 7.3, PHP 7.4 or PHP 8.0
### Keywords
HTML, PHP, web, web-hosting, website, script, code, contact us

Background
----------
Lots of contact us scripts are available over the internet. Other
scripts need modification of the PHP file before use while this script
will run directly out of the box. This script is very useful to those
who do not know PHP and to the beginners of PHP.

Using the Code
--------------
Upload the *script* folder to your www root directory.
### From Action
``` {lang="html" data-lang-orig="html"}
<form action="contact2us/send.php" method="POST">
```
### Fields Names
Use `from_email`, `from_name`, `subject`, `message` and `captcha` as
main fields' names in your form.
### Captcha
If you don't wish to use captcha, then no change is needed and the 1^st^
line of code will be:
``` {lang="php" data-lang-orig="php"}
$captcha = false;
```
If you wish to use captcha, then change the 1^st^ line of code to be:
``` {lang="php" data-lang-orig="php"}
$captcha = true;
```
To use captcha, include the following in your form:
``` {lang="php" data-lang-orig="php"}
<img src="contact2us/captcha_code_file.php?rand=<?php echo rand(); 
?>" id='captchaimg' ><br>
Enter the code above here : <input id="captcha" 
name="captcha" type="text"><br>
```
### Thank you URL
Put your own `$thank_you_url` in the 2^nd^ line of the code.

What Does This Script Do?
-------------------------
*   Check the referrer page and stop the script if it is called
    directly:

    ``` {lang="php" data-lang-orig="php"}
    $REFERER = $_SERVER['HTTP_REFERER'];
    if(!preg_match("@^http:\/\/(www\.)?$domain\/@",$REFERER)){
                    die("This page can't be call directly");
    }
    ```
*   Validate user email and user name to prevent injecting the wrong
    command in the header parameter of the mail function:

    ``` {lang="php" data-lang-orig="php"}
    if(!$from_email) $from_email = "web_page@$domain";
    if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
                    $Err .= 'Invalid email format<br>';
                    $from_email = "web_page@$domain";
    }
    ```
*   Validate subject and encode it if needed to prevent send failure:
    ``` {lang="php" data-lang-orig="php"}
    if ($subject && !preg_match('/^[A-Za-z ]+$/',$subject)){
                    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    }
    ```
*   Store captcha in session and compare it with variable
*   Seek all posted variables
    ``` {lang="php" data-lang-orig="php"}
    foreach ($_POST as $key => $value)
    {
        $value = htmlspecialchars($value);
        $message_html .= "<h2>$key</h2><p>$value</p>";
    }
    ```
*   Send the message in Html UTF-8 format to be compatible with most
    languages
*   Redirect to thank you URL
    ``` {lang="php" data-lang-orig="php"}
    header('Location: '. $thank_you_url);
    ```
 
PHP Mailing Technique
---------------------
There are lots of mailing techniques in PHP; PEAR Mail, PHP Mailer, and
a mail function. However, we just use the mail function as it is common
and simple.

PHP Email Validation
--------------------
### PHP FILTER\_SANITIZE\_EMAIL Filter
Remove all illegal characters from an email address

``` {lang="php" data-lang-orig="php"}
$from_email = filter_var($from_email, FILTER_SANITIZE_EMAIL);
```
### PHP FILTER\_VALIDATE\_EMAIL Filter
Check if the variable \$email is a valid email address

``` {lang="php" data-lang-orig="php"}
if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {                    
    $Err .= 'Invalid email format<br>';               
    $from_email = "web_page@$domain";
}
```
### Validate Email in PHP using a regular expression:
``` {lang="php" data-lang-orig="php"}
$pattern = '/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/';
if(!preg_match($pattern, $from_email)){ 
    $Err .= 'Invalid email format<br>';               
    $from_email = "web_page@$domain";
}
```
What is the Next Step?
----------------------
Setting the max email could be sent for a single IP per hour. If you
have any suggestions for this section or to improve the script; please
write it in the comments to be included in the next version.
