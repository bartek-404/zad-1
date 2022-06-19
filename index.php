<?php


$ip = get_ip(); // przypisanie ip do zmiennej
$time_zone = get_timez_by_ip($ip); 


echo '<h2>Strefa czasowa dla ip <strong>'.$ip.' to: '.$time_zone.'</h2>'; // wyświetlanie ip i strefy
echo '<br />';

$curr_date = new DateTime("now", new DateTimeZone($time_zone)); //pobranie daty i godziny na podstawie strefy czasowej


echo $curr_date->format("Y-m-d H:i:s");


//utworzenie pliku z logami

$logFileName = 'app_log.log'; 
$logContent = "Bartosz Michalowski".PHP_EOL;
$date = new DateTime();
$date = $date->format("y:m:d h:i:s");
$port = $_SERVER['SERVER_PORT'];
if ($handle = fopen($logFileName, 'a'))
{
  fwrite($handle, $date);
  fwrite($handle, PHP_EOL);
  fwrite($handle, $logContent);
  fwrite($handle, PHP_EOL);
  fwrite($handle, $port);
  fwrite($handle, PHP_EOL);
 }
 fclose($handle);




function get_timez_by_ip($ip){ //pobieranie time zone poprzez ip strony geoplugin

    $info = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
    $lat = $info['geoplugin_latitude'];
    $lng = $info['geoplugin_longitude'];
    $country = $info['geoplugin_countryCode'];
    $time_zone = get_nearest_timezone($lat, $lng, $country) ;
    return $time_zone;
}

//pobieranie adresu ip z klienta
 function get_ip() {  
     $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;  
}  


function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') { 
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
        : DateTimeZone::listIdentifiers();

    if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

        $time_zone = '';
        $tz_distance = 0;

        
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {

            foreach($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat   = $location['latitude'];
                $tz_long  = $location['longitude'];

                $theta    = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                    + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;

                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone   = $timezone_id;
                    $tz_distance = $distance;
                }

            }
        }
        return  $time_zone;
    }
    return 'unknown';
}


?>
