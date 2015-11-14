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

$bln_testing	= (bool) false;
$str_loadTarget = (string) 'workshops';
//$str_fileType = (string) 'text/plain';

	if( !function_exists( getFileUpload ) ) {
		if( file_exists( '../lib/fileUploads.php' ) ) {
			require_once( '../lib/fileUploads.php' );
		} else {
			$str_textWall = '';
			echoToConsole( 'WTF, can\'t locate fileUploads lib!', true );
			for( $i = 0; $i < 30; $i++ ) {
				$str_textWall .= "<br>\n" . openssl_digest( microtime(), 'sha256', false );
			}//end for loop

			die( '<strong>Woops!</strong> An internal error has occurred!<br>'
				. 'A team if highly trained Gibbons has been dispatched to fix'
				. 'it at once.<br>If you happen to see them, please show them'
				. 'this:<br><br> ' . $str_textWall );
		}//end if statement
	}// end if statement

	if( !function_exists( parseLoadFile ) ) {
		if( file_exists( '../lib/loadParser.php' ) ) {
			require_once( '../lib/loadParser.php' );
		} else {
			$str_textWall = '';
			echoToConsole( 'WTF, can\'t locate loadParser lib!', true );
			for( $i = 0; $i < 30; $i++ ) {
				$str_textWall .= "<br>\n" . openssl_digest( microtime(), 'sha256', false );
			}//end for loop

			die( '<strong>Woops!</strong> An internal error has occurred!<br>'
				. 'A team if highly trained Gibbons has been dispatched to fix'
				. 'it at once.<br>If you happen to see them, please show them'
				. 'this:<br><br> ' . $str_textWall );
		}// end if statement
	}// end if statement

/**
 * Get the data file from the user
 */
	$str_fileName = (string) $str_loadTarget . 'File';
	$uploadResult = getFileUpload( $str_fileName, $str_fileType, $str_loadTarget );

	if( is_bool( $uploadResult ) ) {
		if( $uploadResult == true ) {
			/**
			 * Got a working file to work with!
			 */
			echoToConsole( "Upload function got a working file to work with!", true );

			$str_loadContent = (string) file_get_contents( "{$str_appLocation}admin/setup/uploads/{$str_fileName}" );
		} else {
			/**
			 * There was an internal error that could not be recovered
			 * from and the upload function failed.
			 */
			 echoToConsole( "Upload function returned boolean False", true );
		}// end if statement
	} else if( is_string( $uploadResult ) ) {
		/**
		 * The upload failed but the file data was parsed and returned
		 * as a string instead.
		 */
		echoToConsole( "Outstanding! The upload function failed to store the file but still managed to extract all the data from it!", true );

		$str_loadContent = (string) $uploadResult;

		//Free memory
		unset( $uploadResult );
	}// end if statement

	if( isset( $str_loadContent ) ) {
	/**
	 * Parse the string input
	 */
		$ary_loadContent = (array) Array();
		$ary_loadContent = parseLoadFile( $str_loadContent );

		//Free memory
		unset( $str_loadContent );

	/**
	 * Build the Query
	 */

		if( !isset( $_POST['dataStructure'] ) ) {
			$loadQuery = (string) "INSERT INTO workshops VALUES\n";
		} else {
			$dataStructure = addslashes( $_POST['dataStructure'] );
			$loadQuery = (string) "INSERT INTO workshops{$dataStructure} VALUES\n";
		}//end if statement

		for( $i = 0; $i < count($ary_loadContent); ++$i ) {

			$loadQuery .= "\t( ";

			for( $y = 0; $y < count( $ary_loadContent[$i] ); ++$y ) {
				$pattern_integer = "/^[((-)|(+))]?[0-9]{1,11}$/";
				$pattern_timestamp = "/^TIMESTAMP /";
				$pattern_singleQuoted = "/^('[^']*')$/";

				if( preg_match( $pattern_integer,$ary_loadContent[$i][$y] ) == 1 ) {
					$loadQuery .= $ary_loadContent[$i][$y];
				} else if( preg_match( $pattern_timestamp,$ary_loadContent[$i][$y] ) == 1 ) {
					$loadQuery .= $ary_loadContent[$i][$y];
				} else if( preg_match( $pattern_singleQuoted, $ary_loadContent[$i][$y] ) == 1 ) {
					$loadQuery .= $ary_loadContent[$i][$y];
				} else {
					$loadQuery .= '\'' . addslashes( $ary_loadContent[$i][$y] ) . '\'';
				}// end if statement

				if( $y < count($ary_loadContent[$i]) - 1 ) {
					$loadQuery .= ",";
				}// end if statement

			}// end inner for loop

			if( $i == count($ary_loadContent) - 1 ) {
				$loadQuery .= ")";
			} else {
				$loadQuery .= "),\n";
			}// end if statement

		}// end outer for loop

		$loadQuery .= ";";
		
		if( $bln_testing == true ) {
			$loadQuery = htmlentities( $loadQuery );

			echo <<<END

			<span class="block left-margin upper-space lower-space">
				Testing mode is on. The Query that was built follows:<br><br>
				<strong>{$loadQuery}</strong>
			</span>

END;
			exit();
		}// end if statement

	/**
	 * Connect to and Query the Database
	 */
		// Open the database connection
		$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb )
			or die( "Failed to connect to database. Impossible to continue." . mysqli_error( $dbConnectionObject  ) );

		// Set the character set, for use with mysqli_real_escape_string
		mysqli_set_charset( $dbConnectionObject, $str_dbCharset );
		
		//Query the Database
		$loadResult = mysqli_query( $dbConnectionObject, $loadQuery );
		
		if( $loadResult == false ) {
			echoToConsole( "The query returned False!", true );
			
			echo( "<p>" . mysqli_error( $dbConnectionObject ) . "</p>\n" ) ;
			
			echo( "<p>{$loadQuery}</p>\n" );
		} else {
			echo( "<div class=\"upper-space ui-state-info\">Successfully loaded " . mysqli_affected_rows( $dbConnectionObject ) . " records into the database.</div>\n" );
		}// end if statement

		//Disconnect from the Database
		mysqli_close( $dbConnectionObject );
	}// end if statement
?>