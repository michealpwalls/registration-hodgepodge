<?php
/* 
 * mails_students.php   -   conference-registration
 */

// Email the token to the student
function sendWelcomeEmail($emailAddress, $firstName, $studentToken, $str_supportEmail, $bln_useSendGrid) {
    global $str_appLocation, $str_appURL, $str_appName;
    
    require "{$str_appLocation}views/mailMessages_en_welcomeWithToken.php";

    if ($bln_useSendGrid) {
        require "{$str_appLocation}sendgrid-php/sendgrid-php.php";

        $obj_SendGrid = new SendGrid("azure_0a576068dd9b0e215730e46cc0d95746@azure.com", "0227XQSg5HEYVIK", array("turn_off_ssl_verification" => true));
        $obj_Email = new SendGrid\Email();

        $obj_Email->addTo($emailAddress)->
            setFrom($str_supportEmail)->
            setReplyTo($str_supportEmail)->
            setSubject($str_appName)->
            setHtml($emailMessage)->
            addHeader("MIME-Version", "1.0")->
            addHeader("Content-Type","text/html; charset=iso-8859-1")->
            addHeader("X-Transport", "web");
        $SendGridResultObject = $obj_SendGrid->send($obj_Email);

        $deliveryResult = ($SendGridResultObject->code == 200 ? true : false);
    } else {
        $mailHeaders = "MIME-Version: 1.0" . "\r\n"; 
        $mailHeaders .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
        $mailHeaders .= "From: {$str_supportEmail}\r\n";
        $mailHeaders .= "Reply-To: {$str_supportEmail}\r\n";
        $mailSubject = "{$str_appName}";

        $deliveryResult = mail($emailAddress, $mailSubject, $emailMessage, $mailHeaders);
    }
    
    return $deliveryResult;
}