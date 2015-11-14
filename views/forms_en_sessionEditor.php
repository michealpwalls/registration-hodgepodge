<?php
/*
 * forms_en_sessionEditor.php   -   conference-registration
 */

$formTarget = (string) "{$str_appURL}admin/index.php?action=edit-session&admtkn={$adminTokenInput}";

$mandatoryYesChecker = ($ary_sessionFields['mandatory_sessions'] == "yes" ? "checked" : "");
$mandatoryNoChecker = ($ary_sessionFields['mandatory_sessions'] == "no" ? "checked" : "");
$passportYesChecker = ($ary_sessionFields['requirePassport_sessions'] == "yes" ? "checked" : "");
$passportNoChecker = ($ary_sessionFields['requirePassport_sessions'] == "no" ? "checked" : "");
$dayOneSelector = ($ary_sessionFields['day_sessions'] == 1 ? "selected" : "");
$dayTwoSelector = ($ary_sessionFields['day_sessions'] == 2 ? "selected" : "");
?>
            <form action="<?=$formTarget;?>" method="post">
                <fieldset class="regbox">
                    <legend class="regboxtitle">Editing session Id <?=$ary_sessionFields['id_sessions']?></legend><br>
                    <input type="hidden" name="id" value="<?=$ary_sessionFields['id_sessions']?>">
                    <label for="form label1">Session number:</label>
                    <input type="text" name="numberInput" value="<?=$ary_sessionFields['number_sessions']?>" size="1"><br>
                    <label for="form label2">Is session mandatory?</label>
                    <label for="mandatoryInput"><input type="radio" name="mandatoryInput" value="yes" <?=$mandatoryYesChecker?>>Yes</label>&nbsp;<label for="mandatoryInput"><input type="radio" name="mandatoryInput" value="no"  <?=$mandatoryNoChecker?>>No</label><br>
                    <label for="form label3">Is passport required?</label>
                    <input type="radio" name="requirePassportInput" value="yes" <?=$passportYesChecker?>><label for="requirePassportInput">Yes</label>&nbsp;<label for="requirePassportInput"><input type="radio" name="requirePassportInput" value="no" <?=$passportNoChecker?>>No</label><br>
                    <label for="dayInput">Session day:</label>
                    <select name="dayInput">
                        <option value="">-- Days --</option>
                        <option value="1" <?=$dayOneSelector?>>Day 1</option>
                        <option value="2" <?=$dayTwoSelector?>>Day 2</option>
                    </select><br>
                    <label for="maxInput">Session maximum:</label>
                    <input type="text" name="maxInput" value="<?=$ary_sessionFields['max_sessions']?>" size="2"><br>
                    <label for="descriptionInput">Session description:</label><br>
                    <textarea name="descriptionInput" rows="6" cols="110"><?=$ary_sessionFields['description_sessions']?></textarea><br>
                    <label for="locationInput">Location:</label><br>
                    <input type="text" name="locationInput" value="<?=$ary_sessionFields['location_sessions']?>" size="6"><br>
                    <input type="submit" value="Edit">
                </fieldset>
            </form>