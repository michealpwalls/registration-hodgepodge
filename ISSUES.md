conference-registration codebase issues
=======================================

  1) use of deprecated mysql functions:

    * /register3.php
    * /admin/index.php?action=nametags
    * /admin/index.php?action=listWaitlisted


  2) GraceLESS fails

    * Undefined variable: str_dbCharset: /admin/index.php?action=listWorkshops (/admin/index.php)
    * Undefined variable: usersResultObject: /admin/index.php?action=listWorkshops (/admin/index.php)
    * mysqli_free_result(): expects parameter 1 to be mysqli_result, null given: /admin/index.php?action=listWorkshops (/admin/index.php)
    * mysqli_free_result(): Couldn't fetch mysqli_result: /admin/index.php?action=listWorkshops (/admin/index.php)
    * Call to undefined function daysRemaining(): /admin/index.php?action=listRegistrants (/lib/listworkshops.php)
    * Less than ten Last Ten Users: /admin/index.php
    * no sessions to list: /admin/index.php?action=listWorkshopsNotRegistrants


    3) Incorrect use of echo, include and require language-constructs

        echo, include, include_once, require & require_once are all
        language-constructs not functions. They should not have ().