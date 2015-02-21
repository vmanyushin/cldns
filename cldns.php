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

// cloudflare token
$tkn   = '';

// email
$email = '';

$zone  = $argv[1];

$rs = new DnsResolver('sysop.pro');
$cf = new CloudFlare ($url, $tkn, $email, $zone);

foreach ($cf as $rrs) {
    echo $rrs . "\n";
}
?>

