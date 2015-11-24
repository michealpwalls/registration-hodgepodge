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

$bln_oneSessionOnDayOne = (bool) true;
$str_exampleStudentId = (string) '200212345';
$str_exampleStudentToken = (string) 'QRWEJSWoaDemA7FtdsVV42cc';

// Define group A array (Facilitates quick matching)
$ary_studentGroups_a = (array) Array(
    "AUBU" => "(AUBU) Automotive Business",
    "BBAM" => "(BBAM) Bachelor Of Business (Automotive Management)",
    "ADMC" => "(ADMC) Advertising And Marketing Communications",
    "AVIA" => "(AVIA) Aviation Management",
    "BAAC" => "(BAAC) Business Administration - Accounting",
    "BACN" => "(BACN) Business - Accounting",
    "BACT" => "(BACT) Business - Accounting",
    "BADM" => "(BADM) Business Administration",
    "BAHR" => "(BAHR) Business Administration - Human Resources",
    "BMKN" => "(BMKN) Business - Marketing",
    "BMKT" => "(BMKT) Business - Marketing",
    "BOKP" => "(BOKP) Bookkeeping",
    "BSFN" => "(BSFN) Business Fundamentals",
    "BUSG" => "(BUSG) Business",
    "BUSN" => "(BUSN) Business",
    "LCLR" => "(LCLR) Law Clerk",
    "OFAE" => "(OFAE) Office Administration - Executive",
    "OFAG" => "(OFAG) Office Administration - General",
    "OFAM" => "(OFAM) Office Adminstration - Medical",
    "BENT" => "(BENT) Business - Entrepreneurship",
    "BINT" => "(BINT) International Business Management",
    "HRMN" => "(HRMN) Human Resources Management",
    "BSCN" => "(BSCN) Bachelor Of Science In Nursing (Bscn) Collaborative Program",
    "BBGM" => "(BBGM) Bachelor Of Business (Golf Management)",
    "TREC" => "(TREC) Therapeutic Recreation",
    "EVNT" => "(EVNT) Event Management",
    "ADTP" => "(ADTP) Addictions: Treatment And Prevention",
    "CODA" => "(CODA) Communicative Disorders Assistant",
    "FUND" => "(FUND) Fundraising And Resource Development",
    "ACPT" => "(ACPT) Acupuncture",
    "DNAS" => "(DNAS) Dental Assisting (Levels I And Ii)",
    "DNTH" => "(DNTH) Dental Hygiene",
    "DNTM" => "(DNTM) Denturism",
    "ESTH" => "(ESTH) Esthetician",
    "MASG" => "(MASG) Massage Therapy",
    "OPTA" => "(OPTA) Occupational Therapist Assistant And Physiotherapist Assistant",
    "OPTI" => "(OPTI) Opticianry",
    "PARA" => "(PARA) Paramedic",
    "PHRM" => "(PHRM) Pharmacy Technician",
    "PNRS" => "(PNRS) Practical Nursing",
    "PRHS" => "(PRHS) Pre-Health Sciences",
    "PSWR" => "(PSWR) Personal Support Worker",
    "VETA" => "(VETA) Veterinary Assistant",
    "VETN" => "(VETN) Veterinary Technician",
    "CULN" => "(CULN) Culinary Management",
    "CULS" => "(CULS) Culinary Skills - Chef Training",
    "FHPR" => "(FHPR) Fitness And Health Promotion",
    "GLFO" => "(GLFO) Golf Facilities Operation Management",
    "HADM" => "(HADM) Hospitality Administration - Hotel And Resort",
    "HMGT" => "(HMGT) Hospitality Management - Hotel And Resort",
    "RELS" => "(RELS) Recreation And Leisure Services",
    "TRVL" => "(TRVL) Tourism And Travel",
    "CYCA" => "(CYCA) Child And Youth Care (Formerly Child And Youth Worker)",
    "CYWK" => "(CYWK) Child And Youth Worker",
    "DSWR" => "(DSWR) Developmental Services Worker",
    "ECED" => "(ECED) Early Childhood Education",
    "SSWK" => "(SSWK) Social Service Worker"
);// End of group A

// Define group B array (Facilitates quick matching)
$ary_studentGroups_b = (array) Array(
    "CJSR" => "(CJSR) Community And Justice Services",
    "COPA" => "(COPA) Computer Programmer Analyst",
    "COPR" => "(COPR) Computer Programmer",
    "CSTN" => "(CSTN) Computer Systems Technician - Networking",
    "IWDD" => "(IWDD) Interactive Web Design And Development",
    "INSS" => "(INSS) Information Systems Security",
    "AADF" => "(AADF) Art And Design Fundamentals",
    "DPAI" => "(DPAI) Digital Photography And Imaging",
    "FIAA" => "(FIAA) Fine Arts - Advanced",
    "FIAR" => "(FIAR) Fine Arts",
    "GRDE" => "(GRDE) Graphic Design",
    "GRDP" => "(GRDP) Graphic Design Production",
    "HAIR" => "(HAIR) Hairstyling",
    "INDC" => "(INDC) Interior Decorating",
    "INTE" => "(INTE) Interior Design",
    "INTR" => "(INTR) Interior Design",
    "JMET" => "(JMET) Jewellery And Metals",
    "GLDS" => "(GLDS) Goldsmithing And Silversmithing",
    "KBDE" => "(KBDE) Kitchen And Bath Design",
    "MUSM" => "(MUSM) Museum And Gallery Studies",
    "ARTC" => "(ARTC) Architectural Technician",
    "ARTE" => "(ARTE) Architectural Technology",
    "CABT" => "(CABT) Cabinetmaking Techniques",
    "CART" => "(CART) Carpentry Techniques",
    "CVTN" => "(CVTN) Civil Engineering Technician - Construction",
    "CVTY" => "(CVTY) Civil Engineering Technology",
    "EETN" => "(EETN) Electrical Engineering Technician",
    "EETY" => "(EETY) Electrical Engineering Technology",
    "ELTQ" => "(ELTQ) Electrical Techniques",
    "ENTN" => "(ENTN) Environmental Technician",
    "ENVR" => "(ENVR) Environmental Technology",
    "GAST" => "(GAST) Gas Technician",
    "HRAC" => "(HRAC) Heating, Refrigeration And Air Conditioning Technician",
    "METC" => "(METC) Marine Engineering Technician",
    "METY" => "(METY) Mechanical Engineering Technology",
    "MNAV" => "(MNAV) Marine Technology - Navigation",
    "MTME" => "(MTME) Mechanical Techniques - Marine Engine Mechanic",
    "MTPS" => "(MTPS) Mechanical Technician - Precision Skills",
    "MTSE" => "(MTSE) Mechanical Techniques - Small Engine Mechanic",
    "PETY" => "(PETY) Power Engineering Technology",
    "PLTQ" => "(PLTQ) Plumbing Techniques",
    "WETC" => "(WETC) Welding Techniques",
    "MEMG" => "(MEMG) Marine Engineering Management",
    "RAPP" => "(RAPP) Research Analyst"
);// End of group B

// Combine the two groups to create a complete list
$ary_studentGroups = array_merge($ary_studentGroups_a,$ary_studentGroups_b);

// Sort the complete list alphabetically without losing key/value associations.
asort($ary_studentGroups, SORT_STRING);
?>