<?php
/*
 * environment.php	-	conference-registration
 */

// App Info
$bln_isBeta         = (bool) true;
$bln_isStudentReg   = (bool) true;
$bln_sendWelcomeMail = (bool) false;
$bln_isConference   = (bool) false;
$str_appName        = (string) "International Student Orientation Registration";
$str_appVersion     = (string) '1.0.0 RC3';
$str_appURL         = (string) "https://intlstudentregistration.azurewebsites.net/";
$str_appLocation    = (string) "D:\\home\\site\\wwwroot\\";

// Email Information
$bln_isAzure        = (bool) true;
$bln_useSendGrid    = (bool) true;
$str_emailReplyTo   = (string) 'IntlStudentAdvisor@georgiancollege.ca';
$str_supportEmail   = (string) $str_emailReplyTo;
$str_emailSender    = (string) $str_emailReplyTo;

/*
 * Set the output levels for PHP. If app is in beta mode, enable
 * heavy output from PHP. Otherwise silence all but fatal errors that
 * prevent the app from properly running.
 */
if($bln_isBeta) {
    // Verbose output from PHP (All ouput including Errors and Warnings
    // and relevant html links to documentation).
    ini_set("display_errors", "on");
    ini_set("html_errors", "on");
    ini_set("error_reporting", "E_ALL");
    error_reporting(E_ALL);
} else {
    // Verbose output from PHP (Only Errors)
    ini_set("display_errors", "on");
    ini_set("html_errors", "off");
    ini_set("error_reporting", "E_ERROR");
    error_reporting(E_ERROR);
}

/*
 * Make sure that if Magic Quotes is enabled, slashes are stripped.
 */
if(get_magic_quotes_gpc()) {
    // Remove all slashes from get input
    foreach ($_GET as $value) {
        $value = stripslashes($value);
    }// end foreach loop
    
    // Remove all slashes from post input
    foreach($_POST as $value) {
        $value = stripslashes($value);
    }// end foreach loop
}// end if statement

/*
 * Include relevant data for different application roles
 */
if($bln_isConference) {
    require_once $str_appLocation . 'data/fotcData.php';
}// end if statement

if($bln_isStudentReg) {
    require_once $str_appLocation . 'data/studentRegData.php';
}// end if statement
?>