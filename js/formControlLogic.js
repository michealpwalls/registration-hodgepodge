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

		function disableControls() {
			$( "input[name='contactOptions_waitlisted']" ).prop( "disabled", true );
			$( "select[name='contactOptions_lunch']" ).prop( "disabled", true );
			$( "select[name='contactOptions_fotc']" ).prop( "disabled", true );
			$( "select[name='contactOptions_keynote']" ).prop( "disabled", true );
			$( "select[name='contactOptions_review']" ).prop( "disabled", true );
			$( "select[name='contactOptions_registered']" ).prop( "disabled", true );
			$( "select[name='contactOptions_extraordinary']" ).prop( "disabled", true );
		}// end disableControls function
		
		function enableControls() {
			$( "input[name='contactOptions_waitlisted']" ).prop( "disabled", false );
			$( "select[name='contactOptions_lunch']" ).prop( "disabled", false );
			$( "select[name='contactOptions_fotc']" ).prop( "disabled", false );
			$( "select[name='contactOptions_keynote']" ).prop( "disabled", false );
			$( "select[name='contactOptions_review']" ).prop( "disabled", false );
			$( "select[name='contactOptions_registered']" ).prop( "disabled", false );
			$( "select[name='contactOptions_extraordinary']" ).prop( "disabled", false );
		}// end enableControls function
		
		function showElement( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.style.display = "block";
		}// end showElement function
		
		function hideElement( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.style.display = "none";
		}// end hideElement function
		
		function disableOption( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.setAttribute('disabled', 'disabled');
		}// end disableOption function
		
		function enableOption( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.removeAttribute('disabled');
		}// end enableOption function
		
		function checkOption( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.setAttribute('checked', 'checked');
		}// end checkOption function
		
		function unCheckOption( targetElementId ) {
			var targetElement = document.getElementById( targetElementId );
			targetElement.removeAttribute('checked');
		}// end unCheckOption function