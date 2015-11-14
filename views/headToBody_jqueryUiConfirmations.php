<?php
/*
    Copyright 2014-2015 Micheal P. Walls <michealpwalls@gmail.com>

    This file is part of the International Student Registration System.

    International Student Registration System is free software: you can
    redistribute it and/or modify it under the terms of the GNU General
    Public License as published by the Free Software Foundation, either
    version 3 of the License, or (at your option) any later version.

    International Student Registration System is distributed in the hope
    that it will be useful, but WITHOUT ANY WARRANTY; without even the
    implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
    PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with International Student Registration System.
    If not, see <http://www.gnu.org/licenses/>.
*/
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Georgian College :: <?=$str_appName;?> Dashboard</title>
        <!--
            JQuery-UI css definitions were manually implemented in gl.css
            to fix IE11 performance issue when full jquery-ui.css was
            loaded.
        -->
        <link rel="stylesheet" href="../css/georgianBanner.css">

        <!--
                Legacy JQuery and JQuery-UI used for IE8 functionality.
        -->
        <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>

        <script src="../js/accordion.js"></script>
        <script src="../js/formControlLogic.js"></script>
        <script>
            function confirmGeneric( confirmationMessage ) {
                var challengeResponse = false;

                challengeResponse = confirm( confirmationMessage );

                return challengeResponse;
            }// end confirm function

            function confirmDelete() {
                var challengeResponse = false;

                challengeResponse = confirm("This is a potentially dangerous tool, used to delete data from our records.\n\nAre you SURE you want to proceed?\n\nClicking Yes/Ok will delete EVERYTHING the " + $( "#deleteTarget" ).val() + " table.");

                return challengeResponse;
            }// end confirmDelete function

            function confirmReset() {
                var challengeResponse = false;

                challengeResponse = confirm("This is a potentially dangerous tool, used to reset the keynote seats in our records.\n\nAre you SURE you want to proceed?\n\nClicking Yes/Ok will set all the seats in the keynote table to their maximum values.");

                if( challengeResponse == true ) {
                    window.location = "<?=$str_appURL;?>admin/index.php?action=setup&setupAction=reset&confirm=yes";
                } else {
                    window.location = "<?=$str_appURL;?>admin/index.php?action=setup&setupAction=reset&confirm=no";
                }// end if statement
            }// end confirmReset function

            function confirmLoad( loadTarget ) {
                var challengeResponse = false;

                challengeResponse = confirm("This is a potentially dangerous tool, used to load external data into our " + loadTarget + " records.\n\nAre you SURE you want to proceed?\n\nClicking Yes/Ok will begin processing of text file and loading of data into the <?=$str_appName;?>'s Registration System.");

                if( challengeResponse == true ) {
                    window.location = "<?=$str_appURL;?>admin/index.php?action=setup&setupAction=load-" + loadTarget + "&confirm=yes";
                } else {
                    window.location = "<?=$str_appURL;?>admin/index.php?action=setup&setupAction=load-" + loadTarget + "&confirm=no";
                }// end if statement
            }// end confirmLoad function
        </script>
    </head>
    <body>