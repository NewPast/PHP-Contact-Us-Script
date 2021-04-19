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
function np_filter_input( string $key ) {
    if ( !$key ) return '';

    $key = filter_var( $key, FILTER_SANITIZE_STRING );
    $key = str_replace( ' ', '', $key );
    if ( !$key ) return '';
    if ( strpos( strtolower( $key ), 'eamil' ) !== false ) {
        $FILTER = FILTER_SANITIZE_EMAIL;
    } else {
        $FILTER = FILTER_SANITIZE_STRING;
    }
    $value = filter_input( INPUT_POST, $key, $FILTER );
    if ( $value ) {
        if ($FILTER == FILTER_SANITIZE_EMAIL
           && !filter_var( $value, FILTER_VALIDATE_EMAIL ))
            return '';        
        return $value;
    }
    if(!array_key_exists($key, $_POST)) return '';
    $value = $_POST[$key];
    return filter_var( $value, $FILTER );    
}
//#####################################
@ ini_set( 'log_errors', 'On' );
$log_dir = __DIR__;
if ( basename( $log_dir ) != 'public_html' ) {
    $log_dir = dirname( $log_dir );
}
if ( basename( $log_dir ) != 'public_html' ) {
    $log_dir = dirname( $log_dir );
}
@ ini_set( 'error_log', "$log_dir/error_log" );

// No more settings
$domain = '';
@ $domain = str_replace( 'www.', '', $_SERVER[ 'HTTP_HOST' ] );
if ( !$domain ) {
    $domain
        = str_replace( 'www.', '', $_SERVER[ 'SERVER_NAME' ] );
}
if ( !$domain )die( 'Can not find domains' );

if ( !$to )$to = 'info@' . $domain;

session_start();
$from_email = np_filter_input( 'from_email' );
if ( !$from_email )$from_email = np_filter_input( 'email' );

$from_name = np_filter_input( 'from_name' );
if ( !$from_name )$from_name = np_filter_input( 'name' );
if ( !$from_name )$from_name =
    np_filter_input( 'first_name' ) . ' ' . np_filter_input( 'last_name' );

$subject = np_filter_input( 'subject' );
$message = np_filter_input( 'message' );
$cc = '';
$bcc = '';

//Checking the referrer page and stop the script if it called directly
$REFERER = '';
@ $REFERER = $_SERVER[ 'HTTP_REFERER' ];
if ( !preg_match( "@^https?:\/\/(www\.)?$domain\/@", $REFERER ) ) {
    die( "This page can't be call directly" );
}
if ( !$from_email && !$from_name && !$subject && !$message ) {
    die( "This page can't be call directly" );
}
$ip = isset( $_SERVER[ 'REMOTE_ADDR' ] ) ? $_SERVER[ 'REMOTE_ADDR' ] : '';
$Err = '';

//Validate user email and user name to prevent injecting wrong command in 
//the header parameter of the mail function
if ( !$from_email )$from_email = "web_page@$domain";
if ( !filter_var( $from_email, FILTER_VALIDATE_EMAIL ) ) {
    $Err .= 'Invalid email format<br>';
    $from_email = "web_page@$domain";
}

if ( $cc && !filter_var( $cc, FILTER_VALIDATE_EMAIL ) ) {
    $Err .= 'Invalid cc email format<br>';
    $cc = '';
}
if ( $bcc && !filter_var( $bcc, FILTER_VALIDATE_EMAIL ) ) {
    $Err .= 'Invalid bcc email format<br>';
    $bcc = '';
}

if ( $captcha ) {

    if ( empty( $_SESSION[ 'captcha' ] ) || strcasecmp( $_SESSION[ 'captcha' ], $_POST[ 'captcha' ] ) != 0 ) {
        //Note: the captcha code is compared case insensitively.
        //if you want case sensitive match, update the check above to
        // strcmp()
        die( "The captcha code does not match!<br>" );
    }
}
$headers = '';
if ( true ) { //html
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
} else {
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/plain;charset=utf-8\r\n";
}
$headers .= "From: $from_name<$from_email>\r\n";
if ( $cc )$headers .= "Cc: $cc\r\n";
if ( $bcc )$headers .= "Bcc: $bcc\r\n";

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
</head><body>
";

$message_html .= "<h2>From Email</h2><p>$from_email</p>";
$message_html .= "<h2>From Name</h2><p>$from_name</p>";
$message_html .= "<h2>Subject</h2><p>$subject</p>";
$message = htmlspecialchars( $message );
$message_html .= "<h2>message</h2><p>$message</p>";
$REFERER = htmlspecialchars( $REFERER );
$message_html .= "<h2>REFERER</h2><p>$REFERER</p>";
$message_html .= "<h2>IP</h2><p>$ip</p>";
//Seek all posted variables
foreach ( $_POST as $key => $value ) {
    switch ( $key ) {
        case 'from_email':
        case 'from-email':
        case 'email':
        case 'from_name':
        case 'from-name':
        case 'name':
        case 'submit':
        case 'captcha':
            break;
        default:

            if ( strpos( strtolower( $key ), 'email' ) !== false ) {
                $value = filter_var( $value, FILTER_SANITIZE_EMAIL );
            } else {
                $value = filter_var( $value, FILTER_SANITIZE_STRING );
            }
            $value = htmlspecialchars( $value );
            $key = filter_var( $key, FILTER_SANITIZE_STRING );
            $key = htmlspecialchars( $key );
            $message_html .= "<h2>$key</h2><p>$value</p>";
    }
}
if ( $Err )$message_html .= "<h2>Errors</h2><p>$Err</p>";
$message_html .= '</body></html>';

if ( $from_name && !preg_match( '/^[A-Za-z ]+$/', $from_name ) ) {
    $from_name = "=?UTF-8?B?" . base64_encode( $from_name ) . "?=";
}
//Validate subject and encode it if needed to prevent send frailer
if ( $subject && !preg_match( '/^[A-Za-z ]+$/', $subject ) ) {
    $subject = "=?UTF-8?B?" . base64_encode( $subject ) . "?=";
}

$de = '0';
//@ $de = $_GET['de'];
if($de){
 echo $message_html;
    exit;
}
 

mail( $to, $subject, $message_html, $headers );
//Redirect to thank you URL
header( 'Location: ' . $thank_you_url );
