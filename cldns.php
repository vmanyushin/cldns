<?php

function __autoload($className) {
    if (file_exists($className . '.php')) {
        require_once $className . '.php';
        return true;
    }
    return false;
} 

if (count($argv) < 2) {
    print "\nusage: php cldns.php <zone>\n";
    exit;
}

$url   = 'https://www.cloudflare.com/api_json.html';
$tkn   = '43234234234';
$email = 'vmanyushin@gmail.com';

$zone  = $argv[1];

$rs = new DnsResolver($zone);
$cf = new CloudFlare ($url, $tkn, $email, $zone);

foreach ($cf as $rrs) {
    echo $rrs . "\n";
}
?>

