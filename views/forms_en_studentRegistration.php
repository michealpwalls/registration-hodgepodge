<?php
/*
 * forms_studentRegistration.php	-	conference-registration
 */
?>
            <h3>Registration information</h3>
            <p>To begin the registration process, please provide your Georgian ID and name:</p>
            <div class="formbox">
                <form name="registration" action="index.php" method="post" accept-charset="utf-8">
                    <script>lastEntered = '';</script>
                    <label for="georgianid">Georgian student ID as it appears on your acceptance letter (Example, <strong><?=$str_exampleStudentId;?></strong>).</label><br>
                    <input name="georgianid" class="input-text" type="text" width="20" maxlength="30" value="Georgian student ID" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;" required><br>
                    <label for="fullname">Given name and Surname as it appears on your passport.</label><br>
                    <input name="fullname" class="input-text" type="text" width="20" maxlength="200" value="Full name" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;" required><br>
                    <label for="email">Email address to contact you</label><br>
                    <input name="email" class="input-text" type="text" width="20"  maxlength="300" value="Email Address" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;" required><br>
                    <p>If you have any questions, please email <a href="mailto:<?=$str_supportEmail;?>"><?=$str_supportEmail;?></a></p>
                    <input class="button-green" type="submit" value="Submit">
                </form>
            </div>