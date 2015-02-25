<?php
$localtime = date('l jS \of F Y h:i:s A');

$template2 = <<<SOA2
;
; zone $zone exported from cloudflare at $localtime
;
%-20s ; set default origin

@        IN        SOA    {$this->soa['mname']}.    {$this->soa['rname']}. (
%40s  ; serial
%40s  ; refresh
%40s  ; retry
%40s  ; expire
%40s) ; minimum


SOA2;
?>
