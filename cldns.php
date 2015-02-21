<?php

function __autoload($className) {
    if (file_exists($className . '.php')) {
        require_once $className . '.php';
        return true;
    }
    return false;
} 

$url   = 'https://www.cloudflare.com/api_json.html';
$tkn   = '1844da91727063907a389ea0697771328150d';
$email = 'vmanyushin@gmail.com';
$zone  = 'sysop.pro';

$rs = new DnsResolver('sysop.pro');
$cf = new CloudFlare ($url, $tkn, $email, $zone);

foreach ($cf as $rrs) {
    echo $rrs . "\n";
}
?>

