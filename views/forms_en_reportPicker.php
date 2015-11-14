<?php
/* 
 * forms_en_reportPicker.php    -   conference-registration
 */
?>
<form method="get" action="<?=$str_appURL;?>admin/index.php">
    <label for="action">Choose a report to run:</label>
    <select name="action">
        <option value="" selected>-- Choose a report --</option>
        <option value="report-participants">Current session participants</option>
    </select>&nbsp;
    <input type="hidden" name="admtkn" value="<?=$adminTokenInput?>">
    <input type="submit" value="Run!">
</form>