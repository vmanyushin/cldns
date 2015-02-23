<?php

function __autoload($className) {
    if (file_exists($className . '.php')) {
        require_once $className . '.php';
        return true;
    }
    return false;
} 

$url   = 'https://www.cloudflare.com/api_json.html';
$tkn   = '';
$email = '';

$cf = new CloudFlare ($url, $tkn, $email);

foreach ($cf as $zone_name => $zone_records) {

    $resolv = new DnsResolver($zone_name);
    file_put_contents($zone_name . ".db", $resolv->getSoa(), LOCK_EX);

    foreach ($zone_records as $records) {    
        file_put_contents($zone_name . ".db", "$records\n", FILE_APPEND | LOCK_EX);
    }
}
?>

