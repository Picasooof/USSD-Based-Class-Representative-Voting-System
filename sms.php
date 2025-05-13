<?php
require 'vendor/autoload.php';

use AfricasTalking\SDK\AfricasTalking;

class SMS {
    protected $phone;
    protected $username;
    protected $apiKey;
    protected $AT;

    function __construct($phone, $username = 'sandbox', $apiKey = 'atsk_804042c2f900150145ea20e7b9cc2213240230848096dca0fe122a76cc17d85c126d5031') {
        // Initialize Africa's Talking gateway with credentials
        $this->phone = $phone;
        $this->username = $username;
        $this->apiKey = $apiKey;
        
        try {
            $this->AT = new AfricasTalking($this->username, $this->apiKey);
        } catch (Exception $e) {
            error_log("Africa's Talking Initialization Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendSMS($message, $recipients) {
        try {
            // Ensure recipients is an array
            if (!is_array($recipients)) {
                $recipients = [$recipients];
            }

            $sms = $this->AT->sms();

            // Don't include 'from' in sandbox
            $result = $sms->send([
                'to'      => $recipients,
                'message' => $message
            ]);
            
            return $result;
        } catch (Exception $e) {
            error_log("SMS Sending Error: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    }
}

// Debugging and usage example
// Uncomment and modify as needed

try {
    $phone = "‪+25075419324‬"; // Use verified sandbox number
    $message = "Test SMS from Africa's Talking Sandbox.";
    $recipients = "‪+25075419324"; // Must be sandbox-registered number

    $smsInstance = new SMS($phone);
    $result = $smsInstance->sendSMS($message, $recipients);

    print_r($result);
} catch (Exception $e) {
    echo "Initialization Error: " . $e->getMessage();
}

?>
