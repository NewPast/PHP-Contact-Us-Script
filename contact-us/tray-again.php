<?php
include 'ad.php';
?>
<!DOCTYPE HTML> 
<html>
<head>
	<title>Try again!</title>
    <style>
        img {
            width: 50%;
            height: 50vh;
            margin-top: 15px;
            margin-left: auto;
            margin-right: auto;            
        }
        body{
            text-align: center;
            font-size: 24px;
            color:red;
        }
    </style>
</head>
<body>
<?php ad_body_open(); ?> 
<br>
<p>
Try again:<br> The captcha code does not match!<br>
<a href="/">[Home]</a> <a href="./">[Contact Us]</a></p>
<?php
ad_body_open();
np_ad();
?>    
</body>
</html>