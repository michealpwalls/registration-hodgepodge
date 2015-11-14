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

$countryList = array(
    "Afghanistan",
    "Albania",
    "Algeria",
    "Andorra",
    "Angola",
    "Antigua and Barbuda",
    "Argentina",
    "Armenia",
    "Australia",
    "Austria",
    "Azerbaijan",
    "Bahamas",
    "Bahrain",
    "Bangladesh",
    "Barbados",
    "Belarus",
    "Belgium",
    "Belize",
    "Benin",
    "Bhutan",
    "Bolivia",
    "Bosnia and Herzegovina",
    "Botswana",
    "Brazil",
    "Brunei",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Cambodia",
    "Cameroon",
    "Canada",
    "Cape Verde",
    "Cayman Islands",
    "Central African Republic",
    "Chad",
    "Chile",
    "China",
    "Colombi",
    "Comoros",
    "Congo (Brazzaville)",
    "Congo",
    "Costa Rica",
    "Cote d'Ivoire",
    "Croatia",
    "Cuba",
    "Cyprus",
    "Czech Republic",
    "Denmark",
    "Djibouti",
    "Dominica",
    "Dominican Republic",
    "East Timor (Timor Timur)",
    "Ecuador",
    "Egypt",
    "El Salvador",
    "Equatorial Guinea",
    "Eritrea",
    "Estonia",
    "Ethiopia",
    "Fiji",
    "Finland",
    "France",
    "Gabon",
    "Gambia, The",
    "Georgia",
    "Germany",
    "Ghana",
    "Greece",
    "Grenada",
    "Guatemala",
    "Guinea",
    "Guinea-Bissau",
    "Guyana",
    "Haiti",
    "Honduras",
    "Hungary",
    "Iceland",
    "India",
    "Indonesia",
    "Iran",
    "Iraq",
    "Ireland",
    "Israel",
    "Italy",
    "Jamaica",
    "Japan",
    "Jordan",
    "Kazakhstan",
    "Kenya",
    "Kiribati",
    "Korea, North",
    "Korea, South",
    "Kuwait",
    "Kyrgyzstan",
    "Laos",
    "Latvia",
    "Lebanon",
    "Lesotho",
    "Liberia",
    "Libya",
    "Liechtenstein",
    "Lithuania",
    "Luxembourg",
    "Macedonia",
    "Madagascar",
    "Malawi",
    "Malaysia",
    "Maldives",
    "Mali",
    "Malta",
    "Marshall Islands",
    "Mauritania",
    "Mauritius",
    "Mexico",
    "Micronesia",
    "Moldova",
    "Monaco",
    "Mongolia",
    "Morocco",
    "Mozambique",
    "Myanmar",
    "Namibia",
    "Nauru",
    "Nepal",
    "Netherlands",
    "New Zealand",
    "Nicaragua",
    "Niger",
    "Nigeria",
    "Norway",
    "Oman",
    "Pakistan",
    "Palau",
    "Panama",
    "Papua New Guinea",
    "Paraguay",
    "Peru",
    "Philippines",
    "Poland",
    "Portugal",
    "Qatar",
    "Romania",
    "Russia",
    "Rwanda",
    "Saint Kitts and Nevis",
    "Saint Lucia",
    "Saint Vincent",
    "Samoa",
    "San Marino",
    "Sao Tome and Principe",
    "Saudi Arabia",
    "Senegal",
    "Serbia and Montenegro",
    "Seychelles",
    "Sierra Leone",
    "Singapore",
    "Slovakia",
    "Slovenia",
    "Solomon Islands",
    "Somalia",
    "South Africa",
    "Spain",
    "Sri Lanka",
    "Sudan",
    "Suriname",
    "Swaziland",
    "Sweden",
    "Switzerland",
    "Syria",
    "Taiwan",
    "Tajikistan",
    "Tanzania",
    "Thailand",
    "Togo",
    "Tonga",
    "Trinidad and Tobago",
    "Tunisia",
    "Turkey",
    "Turkmenistan",
    "Tuvalu",
    "Uganda",
    "Ukraine",
    "United Arab Emirates",
    "United Kingdom",
    "United States",
    "Uruguay",
    "Uzbekistan",
    "Vanuatu",
    "Vatican City",
    "Venezuela",
    "Vietnam",
    "Yemen",
    "Zambia",
    "Zimbabwe"
);

?>
            <h3>Student information</h3>
            <p>Before customizing your schedule, please answer the following:</p>
            <div class="formbox">
                <form name="registration" action="student_registration.php" method="post" accept-charset="utf-8">
                    <script>lastEntered = '';</script>
                    <label for="studentNationality">What country were you born in?</label><br>
                    <select name="studentNationality" required>
                        <option value="" selected>-- Choose an option --</option>
<?php
    foreach ($countryList as $country) {
        echo "<option value=\"{$country}\">{$country}</option>";
    }
?>
                    </select><br>
                    <label for="studentResidence">What country do you currently live in?</label><br>
                    <select name="studentResidence" required>
                        <option value="" selected>-- Choose an option --</option>
<?php
    foreach ($countryList as $country) {
        echo "<option value=\"{$country}\">{$country}</option>";
    }
?>
                    </select><br>
                    <label for="studentVisaApproved">Has your student visa been approved?</label><br>
                    <select name="studentVisaApproved" required>
                        <option value="" selected>-- Choose an option --</option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select><br>
                    <label for="studentHasPassport">Do you have a valid passport?</label><br>
                    <select name="studentHasPassport" required>
                        <option value="" selected>-- Choose an option --</option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select><br>
                    <label for="studentArrivingDate">When will you be arriving in Canada?</label><br>
                    <input type="text" name="studentArrivingDate" id="datepicker" required><br>
                    <label for="studentProgram">What academic program are you enrolled in?</label><br>
                    <select name="studentProgram">
<?php
    foreach ($ary_studentGroups as $code => $name) {
        echo "<option value=\"{$code}\">{$name}</option>";
    }
?>
                    </select><br>
                    <label for="studentLunch">Would you prefer a Vegetarian lunch?</label><br>
                    <select name="studentLunch" required>
                        <option value="" selected>-- Choose an option --</option>
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select><br>
                    <label for="studentAllergies">Do you have any food allergies? Please list them (Separate by commas <strong>,</strong>)</label><br>
                    <input type="text" name="studentAllergies" size="44"><br>
                    <label for="studentLunchSpecialReqs">Are there any special requirements for your lunch?</label><br>
                    <textarea name="studentLunchSpecialReqs" rows="4" cols="35"></textarea><br>
                    <input type="hidden" name="stkn" value="<?=$studentToken;?>">
                    <input type="submit" value="Submit">
                </form>
            </div>