# cldns

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

;
; zone sysop.pro exported from cloudflare at Tuesday 24th of February 2015 01:07:16 AM
;
$ORIGIN sysop.pro.   ; set default origin

@        IN        SOA    ben.ns.cloudflare.com.    dns.cloudflare.com. (
                              2017639268  ; serial
                                   10000  ; refresh
                                    2400  ; retry
                                  604800  ; expire
                                    3600) ; minimum

sysop.pro.               IN      A        54.234.98.199
mx.sysop.pro.            IN      A        92.243.176.75
ns1.sysop.pro.           IN      A        54.234.98.199
ns2.sysop.pro.           IN      A        75.102.22.152
smtp.sysop.pro.          IN      A        92.243.176.75
*.sysop.pro.             IN      A        92.243.176.75
www.sysop.pro.           IN      A        54.234.98.199
dev.sysop.pro.           IN  CNAME        www.sysop.pro
sysop.pro.               IN     MX    10  mx.sysop.pro
_adsp._domainkey.sysop.pro.     IN    TXT        'dkim=all'
dev.sysop.pro.           IN    TXT        'development'
primary._domainkey.sysop.pro.     IN    TXT        '"v=DKIM1; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAocU+RC/UI1ANX4R+dKP+E8sox8jD48B/GSSdfRBVtCzwbIjVLKVh+W6dynl6JDTuqAUN/vP8iGHNATKALYgBTnm2zjNtgwEaMK9UTndzLX64cOKHSJOfiKm1eCE4mW0GuU6aTxKyIBSgRhne+qfiMyVx0PDMhD1CHKsnUCiomX0PZaHuEZnAVkYJ8kbIUEotk6S9uooxJqXrfSrYiBiWBTWNUN0RP/NGqdZ80h666hWIl2VPpjaG58exUUGaPSoM2MOagIgP8zCNttzfKsaDvTHMxxiGNDxVvyPjmYKs8NP+pn4OWzinYGPCOr37gZexRQzSlZ02KImKf32OwVvvcwIDAQAB; t=s"'
sysop.pro.               IN    TXT        'v=spf1 ip4:92.243.176.75 ~all'
