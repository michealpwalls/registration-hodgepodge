<?php
/*
 * index_en_shared.php  -   conference-registration
 */

if ($adminAction === "contact") {
    require "{$str_appLocation}admin/contact.php";
} else if ($adminAction === "setup") {
    $bln_subComponent = (bool) true;

    require "{$str_appLocation}admin/setup/setup.php";
}