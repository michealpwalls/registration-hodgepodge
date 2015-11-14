<?php
/*
 * forms_en_sessionPicker.php   -   conference-registration
 */
?>
            <form action="<?=$str_appURL;?>admin/index.php" method="get">
                <input type="hidden" name="action" value="edit-session">
                <input type="hidden" name="admtkn" value="<?=$adminTokenInput;?>">
                <label for="id">Select a session to edit:</label>
                <select name="id">
                    <option value="" selected>-- Sessions --</option>
                    <optgroup label="Day 1 Sessions">
                        <option value="1">Session 1</option>
                        <option value="11">Session 2</option>
                    </optgroup>
                    <optgroup label="Day 2 Sessions">
                        <option value="21">Session 1</option>
                        <option value="31">Session 2</option>
                        <option value="41">Session 3</option>
                    </optgroup>
                </select><br>
                <input type="submit" value="Continue">
            </form>