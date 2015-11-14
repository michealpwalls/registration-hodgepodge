/*
    Copyright 2014-2015 Micheal P. Walls <michealpwalls@gmail.com>
        Some code written by "kennebec" on the StackExchange network
        and used under the Creative Commons Attribute Share Alike 
        License v2.5 with slight (comments/formatting) modifications.
        <http://stackoverflow.com/questions/2400935/browser-detection-in-javascript#2401861>

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

/*
    Instantiates a JQuery accordion for the workshops
    Uses a browser-detect script to detect IE reliably and disables
    the accordion's animations
 */

//DEBUG
var toString = Object.prototype.toString;

/**
 * By: "kennebec" on StackExchange
 * 
 * Creates a property in the navigator object containing the browser
 * name and version, extracted from the user-agent with regular
 * expression matches
 * 
 * output format: name + " " + version
 */
navigator.sayswho= (
	function() {
		var ua= navigator.userAgent, tem, 
		M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*([\d\.]+)/i) || [];

		/**
		 * Internet Explorer's user agent switches from MSIE to IE
		 * at ~11.0
		 */
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+(\.\d+)?)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}// end MSIE switch

		M= M[2]? [M[1], M[2]]:[navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
		return M.join(' ');
	}
)(); //end navigator.sayswho property

//By default turn on accordion animation
animateValue = true;

//If IE, disable animations.
	/**
	 * This is the only way I could find to implement a large JQuery
	 * accordion in IE, including IE11 which would freeze completely
	 * either after a small animation (Empty 'Monday workshops', for
	 * example) or before a large animation (Full 'Tuesday workshops',
	 * for example).
	 */
if( navigator.sayswho == "IE 11.0" || navigator.sayswho == "MSIE 10.0" || navigator.sayswho == "MSIE 9.0" || navigator.sayswho == "MSIE 8.0") {
	animateValue = false;

	//DEBUG
	console.log( "PDWeek's Debug Output: Found an IE, disabling animations" );
}

$(function() {
	$( "#workshopAccordion" ).accordion(
		{
			heightStyle: "content",
			animate: animateValue,
			collapsible: true,
			active: false
		}
	);
});