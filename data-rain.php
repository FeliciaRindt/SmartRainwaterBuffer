 <!--       Bachelor Project Creative Technology by Felicia Rindt 
            data-rain for a smart rainwater buffering system interface 
            Retreiving rainforecast data for the upoming two hours-->


<?php
    $file = file_get_contents("https://gpsgadget.buienradar.nl/data/raintext?lat=52.2&lon=6.9"); //Coordinates of Enschede
    $file = explode("\r\n", $file);

    $data = array();

    for ($i = 0; $i < count($file)-1; $i++) {
        $data[$i] = explode("|", $file[$i]);
        $data[$i]["value"] = $data[$i][0];
        unset($data[$i][0]);
        $data[$i]["time"] = $data[$i][1];
        unset($data[$i][1]);
    }

    echo json_encode($data); //encode data to json
?>