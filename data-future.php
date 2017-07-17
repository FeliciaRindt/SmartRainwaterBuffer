 <!--  Bachelor Project Creative Technology by Felicia Rindt 
            data-future.php for a smart rainwater buffering system interface -->

<?php
    $file = file_get_contents("http://regenbuffer.student.utwente.nl/app.php/buffers/1/future");
    echo json_encode($file);
?>