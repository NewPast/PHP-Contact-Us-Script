PHP contact-us script runs without modification. It detects the domain and emails all data of the contact-us form.
This PHP script is used to send contact-us form data to the webmaster, It is simple and runs without modification.

Run out-of-box PHP contact-us script, it does not need modification, it will detect the domain and send an email containing the contact message to info@exmple.com whatever fields are in your form; it will detect them and send the form data with email.

PHP Contact Us Script Links and Downloads
-------------------------------
### ♦ [Read PHP Contact Us Script on our blog](https://www.miniindustry.com/d/newpast/post/simple-php-contact-form-script)
### ♦ [Arabic version of this article](https://www.miniindustry.com/d/ar/newpast/post/كود-اتصل-بنا-php-html)
### ♦ [PHP Contact Us Script on CodeProject](https://www.codeproject.com/KB/PHP/1139299.aspx)
### ♦ [GitHub Link](https://github.com/NewPast/PHP-Contact-Us-Script)

What is New in PHP Contact Us Script
------------------------------------
* User must enter data in the name field, email, subject, and message before submitting.
* The new version contains more user input filtering. So it keeps the script safe. We sanitize each input key and value using the function <code>htmlspecialchars()</code> and the filter  <code>FILTER_SANITIZE_STRING</code>. And It strip any HTML code or invalid characters.

Introduction
------------
Run out-of-box PHP contact-us script, it does not need modification, it will detect the domain and send an email containing the contact message
to info@exmple.com whatever fields are in your form; it will detect them and send the form data by email.

System requirements
-------------------
* A website with hosting support PHP; Almost all hosts do support it.
* You could use it for any website regardless of what it uses: pure HTML/PHP, WordPress, Joomla, Drupal, or any other system 
### PHP Version
PHP 5.6,  PHP 7.0, PHP 7.1, PHP 7.2, PHP 7.3, PHP 7.4, PHP 8.0, PHP 8.1 or PHP 8.2
### Keywords
HTML, PHP, web, web hosting, website, script, code, contact us

Background
----------
Lots of Contact Us scripts are available over the Internet. Other scripts need modification of the PHP file before use while this script
will run directly out of the box. This script is very useful to those who do not know PHP and to beginners of PHP.

Using the Code
--------------
* Unzip the downloaded zip file
* Create the contact-us folder in the www directory of your website
* Upload the files to the contact-us folder
* That is all
* The contact-us URL is like example.com/contact-us replace example.com with your domain
### Modifying contact-us form design
* You could modify the Contact Us page design as you want,
* Add or omit fields as needed
* Use from_email, from_name, subject, message, and captcha as field names
* Put your Ads or make your form free of ads
* You are free to put a link to us or not. 
## About the contact-us code
### From Action
```html
<form action="send.php" method="POST">
```
### Fields Names
Use `from_email`, `from_name`, `subject`, `message`, and `captcha` as
main fields' names in your form.
### Captcha
If you don’t wish to use a captcha, then change the 1st line of the ‘config.php’ code to be as follows:
```php
$captcha = false;
```
If you wish to use a captcha, then no change is needed and the 1st line of the ‘config.php’ code will be:
```php
$captcha = true;
```
If you need to modify the form; please note that we use a captcha, include the following in your form:
```html
<img src="captcha_code_file.php?rand=<?php echo rand(); 
?>" id='captchaimg' ><br>
Enter the code above here: <input id="captcha" 
name="captcha" type="text"><br>
```
### Thank you URL
Put your own `$thank_you_url` in the 2^nd^ line of the code.

What Does This Script Do?
-------------------------
*   Check the referrer page and stop the script if it is called
    directly:

    ```php
    $REFERER = $_SERVER['HTTP_REFERER'];
    if(!preg_match("@^http:\/\/(www\.)?$domain\/@",$REFERER)){
                    die("This page can't be call directly");
    }
    ```
*   Validate user email and user name to prevent injecting the wrong
    command in the header parameter of the mail() function:

    ```php
    if(!$from_email) $from_email = "web_page@$domain";
    if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
                    $Err .= 'Invalid email format<br>';
                    $from_email = "web_page@$domain";
    }
    ```
*   Validate the subject and encode it if needed to prevent send failure:
    ```php
    if ($subject && !preg_match('/^[A-Za-z ]+$/',$subject)){
                    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    }
    ```
*   Store the captcha in session and compare it with the variable
*   Seek all posted variables
    ```php
    foreach ($_POST as $key => $value) {
        if ( strpos( strtolower( $key ), 'email' ) !== false ) {
            $value = filter_var( $value, FILTER_SANITIZE_EMAIL );
        } else {
            $value = filter_var( $value, FILTER_SANITIZE_STRING );
        }
        $value = htmlspecialchars( $value );
        $key = filter_var( $key, FILTER_SANITIZE_STRING );
        $key = htmlspecialchars( $key );
        $value = htmlspecialchars($value);
        $message_html .= "<h2>$key</h2><p>$value</p>";
    }
    ```
*   Send the message in Html UTF-8 format to be compatible with most
    languages
*   Redirect to thank you URL
    ```php
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

```php
$from_email = filter_var($from_email, FILTER_SANITIZE_EMAIL);
```
### PHP FILTER\_VALIDATE\_EMAIL Filter
Check if the variable \$email is a valid email address

```php
if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {                    
    $Err .= 'Invalid email format<br>';               
    $from_email = "web_page@$domain";
}
```
### Validate Email in PHP using a regular expression:
```php
$pattern = '/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/';
if(!preg_match($pattern, $from_email)){ 
    $Err .= 'Invalid email format<br>';               
    $from_email = "web_page@$domain";
}
```
