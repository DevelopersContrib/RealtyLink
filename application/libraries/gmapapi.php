<?php

class Gmapapi
{
    
    public function getmaplocation($address){
        $url = "http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if(curl_exec($ch) === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        $response_a = json_decode($response);
        if (count($response_a->results)>0){
            $lat = $response_a->results[0]->geometry->location->lat;
            $long = $response_a->results[0]->geometry->location->lng;
            $location = "$lat,$long";
        }else {
            $location = "-33.92, 151.25";
        }
        return $location;
        
    }
    
}