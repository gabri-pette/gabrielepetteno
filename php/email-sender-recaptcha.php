<?php
require_once "recaptcha/autoload.php";
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

$Recipient = 'gabrielepetteno.site@gmail.com'; // <-- Set your email here

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = "6LdiVo0UAAAAAFoXBUxgzGnL25I9IW4_-mhnInOO";
$secret = "6LdiVo0UAAAAAEeSNiqih8wQY6Rr-MQCeIXmAD29";

// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = "it";

// Was there a reCAPTCHA response?
if (isset($_POST['g-recaptcha-response'])) {
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
}

if($Recipient && $resp->isSuccess()) {

	$Name = $_POST['name'];
	$Email = $_POST['email'];
	$Subject = $_POST['subject'];
	$Message = $_POST['message'];
	if (isset($_POST['guests'])) {
		$Guests = $_POST['guests'];
	} else {
		$Guests = "";
	}
	if (isset($_POST['events'])) {
		$Events = $_POST['events'];
	} else {
		$Events = "";
	}
	if (isset($_POST['category'])) {
		$Category = $_POST['category'];
	} else {
		$Category = "";
	}

	$Email_body = "";
	$Email_body .= "Da: " . $Name . "\n" .
				   "Email: " . $Email . "\n" .
				   "Oggetto: " . $Subject . "\n" .
				   "Messagggio: " . $Message . "\n" .
				   //"No Of Guests: " . $Guests . "\n" .
				   //"Event: " . $Events . "\n" .
				   //"Category: " . $Category . "\n";

	$Email_headers = "";
	$Email_headers .= 'From: ' . $Name . ' <' . $Email . '>' . "\r\n".
					  "Reply-To: " .  $Email . "\r\n";

	$sent = mail($Recipient, $Subject, $Email_body, $Email_headers);

	if ($sent){
		$emailResult = array ('sent'=>'yes');
	} else{
		$emailResult = array ('sent'=>'no');
	}

	echo json_encode($emailResult);

} else {

	$emailResult = array ('sent'=>'no');
	echo json_encode($emailResult);

}
?>
