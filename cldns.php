<?php
/**
 * Created by PhpStorm.
 * User: vmanyushin@gmail.com
 * Date: 25.02.2015
 * Time: 17:35
 * url: https://github.com/vmanyushin/cldns
 */

// емейл общий для логина на сайт www.cloudflare.com и для API
$username = '';

// пароль для входа на сайт
$password = '';

// Your API key https://www.cloudflare.com/my-account
$api_tkn  = '';

$loginUrl = 'https://www.cloudflare.com/login';
$token    = '';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$content = curl_exec($ch);
preg_match("/name=\"security_token\" value=\"(.*?)\"/", $content, $matches);
$token = $matches[1];

if ($content === FALSE)
    throw new Exception(htmlspecialchars(curl_error($ch)));

// file_put_contents('index.html', $content);

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'act=login&'.'login_email='.$username.'&login_pass='.$password.'&security_token='.$token);

$content = curl_exec($ch);

if ($content === FALSE)
    throw new Exception(htmlspecialchars(curl_error($ch)));

// file_put_contents('index.html', $content);

$zones = getZoneList($api_tkn,$username);

foreach ($zones as $zone) {
    print_r($zone);
    exportZone($ch, $zone);
}


function exportZone($ch, $zone_name)
{
    curl_setopt($ch, CURLOPT_URL, "https://www.cloudflare.com/dns-settings?z=$zone_name");
    curl_setopt($ch, CURLOPT_POST, 0);

    $content = curl_exec($ch);

    preg_match("/\"security_token\":\"(.*?)\"/", $content, $matches);
    $token = $matches[1];

    curl_setopt($ch, CURLOPT_URL, "https://www.cloudflare.com/ajax/zone-export.html?zExp&z=$zone_name&security_token=$token");
    curl_setopt($ch, CURLOPT_POST, 0);

    $content = curl_exec($ch);

    if ($content === FALSE)
        throw new Exception(htmlspecialchars(curl_error($ch)));

    file_put_contents($zone_name . '.db', $content);
}

function getZoneList($api_tkn, $username) {
    $request = http_build_query(array('a' => 'zone_load_multi', 'tkn' => $api_tkn, 'email' => $username));
    $zones   = array();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.cloudflare.com/api_json.html');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($ch);

    if ($content === FALSE)
        throw new Exception(htmlspecialchars(curl_error($ch)));

    $json_data = json_decode($content);
    $zone_data = $json_data->response->zones->objs;

    foreach ($zone_data as $zone) {
        $zones[] = $zone->zone_name;
    }

    return ($zones);
}