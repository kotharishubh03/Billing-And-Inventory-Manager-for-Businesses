<?php
// Include the bundled autoload from the Twilio PHP Helper Library
require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'AC67559c488094592947222cfa8a57ed70';
$auth_token = '3bc596c7239f0dec2395610df7e650ae';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
// A Twilio number you own with SMS capabilities
$twilio_number = "+18483052563";
$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+918291236662',
    array(
        'from' => $twilio_number,
        'body' => 'I sent this message in under 10 minutes!'
    )
);