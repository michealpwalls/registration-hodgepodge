
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