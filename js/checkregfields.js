/**
 * @author Greg Rodrigo
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