# cldns

first set your api_token and api_email inside cldns.pl 
then use as: 
    perl cldns.pl <zone>

perl cldns sysop.pro > sysop.pro.db

sysop.conf.db
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
;;
;; Fri Feb 20 05:14:26 2015
;; zone sysop.pro
;;

$ORIGIN sysop.pro.
sysop.pro.	20849	IN	SOA	ben.ns.cloudflare.com. dns.cloudflare.com. (
					2017605817	;serial
					10000     	;refresh
					2400      	;retry
					604800    	;expire
					3600   )	;minimum
@                             IN      A   54.234.98.199
mx                            IN      A   54.234.98.199
ns1                           IN      A   54.234.98.199
ns2                           IN      A   75.102.22.152
www                           IN      A   54.234.98.199
dev                           IN  CNAME  www.sysop.pro.
@                             IN     MX    10   mx.sysop.pro.
@                             IN    SPF "v=spf1 ip4:54.234.98.199 ~all"
dev                           IN    TXT "development"
