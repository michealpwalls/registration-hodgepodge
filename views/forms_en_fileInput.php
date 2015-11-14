<?php
/**
 * forms_en_fileInput.php		- conference-registration
 */

echo "<form class=\"upper-space left-margin\" action=\"index.php?action=setup&setupAction=import-{$str_targetDb}&admtkn={$adminTokenInput}\" method=\"post\" enctype=\"multipart/form-data\">\n
        <fieldset><legend>File Details</legend>\n
            <label for=\"{$fileNameIn}\">Input File:</label>\n
            <input type=\"file\" name=\"{$fileNameIn}\" id=\"{$fileNameIn}\"><br>\n
            <label for=\"fileDelimiter\">Delimiter (Character that seperates values)</label>\n
            <input type=\text\" name=\"fileDelimiter\" id=\"fileDelimiter\">\n
        </fieldset><br>\n
        <fieldset><legend>Data Format</legend>
            <label for=\"dataStructure\">Structure of the incoming data</label><br>\n
            <input type=\"text\" name=\"dataStructure\" size=\"95\" value=\"{$str_defaultStructure}\"><br>\n
            <input type=\"hidden\" name=\"setupAction\" value=\"import-{$str_targetDb}\">\n
            <br><input type=\"submit\" name=\"submit\" value=\"Process\">\n
        </fieldset>
    </form>\n";
?>