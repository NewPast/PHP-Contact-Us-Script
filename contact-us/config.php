<?php
$captcha = true;
$thank_you_url = "thank-you.php";
$to = '';
/*
Run out of box PHP contact us script 
Download
--------
https://www.miniindustry.com/d/php-contact-us-script
*/
if(!function_exists('imagecreate')){
    $captcha = false;
    // to use captcha you need Image Processing and GD Library
    // @see https://www.php.net/manual/en/book.image.php
}