<?php
/*
 * headToBody_jqueryUiDatepicker.php - conference-registration
 */
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Georgian College :: <?=$str_appName;?> Dashboard</title>
        <!--
            JQuery-UI css definitions were manually implemented in gl.css
            to fix IE11 performance issue when full jquery-ui.css was
            loaded.
        -->
        <link rel="stylesheet" href="<?=$str_appURL;?>css/georgianBanner.css">

        <!--
                Legacy JQuery and JQuery-UI used for IE8 functionality.
        -->
        <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
        <script>
        $(function() {
            $( "#datepicker" ).datepicker()
        });
        </script>
    </head>
    <body>