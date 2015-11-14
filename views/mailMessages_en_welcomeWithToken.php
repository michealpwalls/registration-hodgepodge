<?php
/*
 * mailMessages_en_welcomeWithToken.php -   conference-registration
 */
$profileUrl = "{$str_appURL}index.php?stkn={$studentToken}";

$emailMessage = "<p>Hello " . htmlentities($firstName) . "</p>\r\n
            <p>Please click on the link below to continue the registration process.</p>\r\n
            <p><a href='{$profileUrl}'>Continue with registration</a></p>\r\n
            <p>If the link above does not redirect to the registration page, please copy and paste the following URL into a new browser window:<br>{$profileUrl}</p>\n
            <p>If you've received this mail in error, please forward this message to " . htmlentities($str_supportEmail) . " so that we can investigate the cause.</p>\n
            <p>Thanks.</p>\r\n";
?>