# cldns

first set your api_token and api_email inside cldns.pl 
then use as: 
    perl cldns.pl <zone>

perl cldns sysop.pro > sysop.pro.db

sysop.pro.db
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
;
; zone sysop.pro exported from cloudflare at Saturday 21st of February 2015 08:03:01 PM
;
$ORIGIN sysop.pro.   ; set default origin
$TTL 3600            ; set default TTL

@        IN        SOA    ben.ns.cloudflare.com.    dns.cloudflare.com. (
                              2017605817  ; serial
                                   10000  ; refresh
                                    2400  ; retry
                                  604800  ; expire
                                    3600) ; minimum

