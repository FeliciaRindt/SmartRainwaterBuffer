 <!--  Bachelor Project Creative Technology by Felicia Rindt 
            data-status.php for a smart rainwater buffering system interface -->

<?php
    $file = file_get_contents("http://regenbuffer.student.utwente.nl/app.php/status"); //get status number
    echo json_encode($file);//encode to json
?>