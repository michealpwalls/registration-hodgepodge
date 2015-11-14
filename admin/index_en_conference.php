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

switch($adminAction) {
    case "listAllUsers":

        if( file_exists( "listusers.php" ) ) {
            require_once "listusers.php";
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    case "nametags":

        if( file_exists( "nametags.php" ) ) {
            require_once "nametags.php";
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    case "listUsersNR":

        if( file_exists( "listusersnr.php" ) ) {
            require_once "listusersnr.php";
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    case "listWorkshops":

        echo "<h3>Show All Sessions</h3><br>\n";
        reportsConference_showAllWorkshops();
        break;

    case "listWorkshopsNotRegistrants":

        echo "<h3>Show All Sessions (Not Registrants)</h3><br>\n";
        reportsConference_showAllWorkshops(false);
        break;

    case "listRegistrants":

        if( file_exists( "listregistrants.php" ) ) {
            require_once "listregistrants.php";
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    case "listUsersFOTC":

        if( file_exists( "listfotc.php" ) ) {
            require_once "listfotc.php";
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    case "inviteWaitlisted":

        if( isset( $_POST['confirm']) ) {
            echoToConsole( "Confirm choice was set, checking the contents", true );

            if( $_POST['confirm'] == 'yes' ) {
                echoToConsole( "Confirm choice was yes, proceeding to Invite all Waitlisters!", true );

                if( file_exists( "setup/waitlist2users.php" ) ) {
                    require_once( "setup/waitlist2users.php" );
                } else {
                    echo "Could not locate the required module.\n";
                }// end if statement

            }// end if statement

        } else {

            echoToConsole( "Confirm choice was not set, displaying form", true );

            echo "<h3>Invite all users on the FoTC Waitlist</h3>\n
                    <form method=\"post\" action=\"index.php?action=inviteWaitlisted\" onSubmit=\"return confirmGeneric('Are you SURE you want to proceed?\\n\\nClicking Yes/Ok will transfer users on the FoTC Waiting List to the FoTC Attendees list, even if the FoTC is at maximum capacity.');\">\n
                        <label for \"userCount\">How many users should be invited?</label>\n
                        <input class=\"text-input\" type=\"number\" name=\"userCount\"><br>\n
                        <input type=\"hidden\" name=\"confirm\" value=\"yes\">\n
                        <input class=\"button-green\" type=\"submit\" value=\"Invite Users\">\n
                    </form>\n";

        }// end if statement
        break;

    case "listWaitlisted":

        if( file_exists( "listwaitlisted.php" ) ) {
            require_once( "listwaitlisted.php" );
        } else {
            echo "Could not locate the required module.\n";
        }
        break;

    default:

        echo "You entered an unsupported action.<br>\n";
        break;

}// end switch case statement
?>