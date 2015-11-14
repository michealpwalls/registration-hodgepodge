<?php
/*
 * logging.php	-	pdweek v1.3
 * 
 * Copyright 2014 Micheal Walls <michealpwalls@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

	/**
	 * This makes logging to the console much easier, which in turn
	 * makes printing Debug information easy.
	 * 
	 * The console can be accessed in any modern browser's
	 * "Developer Tools", usually by pressing F12. On Opera it's
	 * Ctrl+Shift+I (PC) || Cmd+Shift+I (Mac).
         * 
         * @param $messageIn (string)   The message to output
         * @param $option_tags (bool)   Whether or not to wrap in <script> tags
	 */
	function echoToConsole( $messageIn, $option_tags ) {
		global $str_appName;

		/**
		 * Trim wasn't working.. yea:P
		 * 
		 * TODO: Learn why trim did not work in the corner cases and
		 * 		replace this with a better solution.
		 */
		$message = str_replace( "\r\n", " ", $messageIn );
		$message = str_replace( "\t", " ", $message );
		$message = str_replace( "          ", " ", $message );

		if( $option_tags == true ) {
			echo( "<script>console.log( '" . addslashes( $str_appName ) . "\'s Debug Output: " . addslashes( $message ) . "' );</script>\n" );
		} else {
			echo( "console.log( '" . addslashes( $str_appName ) . "\'s Debug Output: " . addslashes( $message ) . "' );" );
		}// end if statement
	}// end echoToConsole() function
?>
