<?php 
class DnsResolver
{
    public function __construct($zone = 'example.com')
    {
        $this->rr = dns_get_record($zone);

        foreach ($this->rr as $record) {
            if ($record['type'] == 'SOA') $this->soa = $record;
        }

        $this->soa['origin']      = "\$ORIGIN $zone.";
        $this->soa['ttl']         = "\$TTL " . $this->soa['minimum-ttl'];

        include 'template.php';

        printf($template2, 
            $this->soa['origin'], 
            $this->soa['ttl'],
            $this->soa['serial'],
            $this->soa['refresh'],
            $this->soa['retry'],
            $this->soa['expire'],
            $this->soa['minimum-ttl']
       );
       
    }
}
?>
