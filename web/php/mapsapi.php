<?php

// var_dump(geocode("Bulevardul 1 Decembrie 1918 nr 39 Romania"));

function geocode($address) {

    // PREPARE THE REQUEST FOR GOOGLE
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

    // OBTAIN RESPONSE FROM GOOGLE API
    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);

    // CHECK IF APPROVED
    if($resp['status']=='OK'){
 
        // GET THE COORDINATES
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // CHECK FOR ALL INFORMATION
        if($lati && $longi && $formatted_address){
         
            $data_arr = array();
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }

} // geocode

?>