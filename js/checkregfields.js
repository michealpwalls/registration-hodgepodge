/*
    Copyright 2014-2015 Greg Rodrigo & Micheal P. Walls <michealpwalls@gmail.com>

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

function checkFields()
{
   foundError = false;
   
   missing = "";

   if (document.registration.lastname.value=="")
   {
       missing += "Last name,\n";
       foundError = true;
   }

   if (document.registration.firstname.value=="")
   {
       missing += "First name,\n";
       foundError = true;
   }

   if (foundError == false)
   {
       return true;
   }
   else
   {
       if (missing != "")
       {
           missing = "Please fill in or complete the following field(s):\n" + missing + "\n\n";
       }       
	   
	   alert(missing);
   }
      
   return false;
     
}

function checkEmail()
{
   if (document.registration.emailnew.value=="" || document.registration.emailnew.value=="FirstName.LastName") {
		alert("Please provide us with your email address.");
		return false;
	}
	else {
		var email = document.registration.emailnew.value;
		
		var aDot = email.search(/\./i);

		var aAmpersand = email.search(/\@/i);

		if (aDot == -1 || aAmpersand != -1) {
			alert("Please provide us with the first part of your new Georgian email address. Example, Greg.Rodrigo");
			return false;	
		} else {
			return true;
		}

	}  
}