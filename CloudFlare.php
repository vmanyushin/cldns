<?php
class CloudFlare implements IteratorAggregate
{
    private $resources = array();  

    public function __construct($url = '', $token = '', $email = '', $zone = 'example.com')
    {
        $this->zone  = $zone;
        $this->token = $token;
        $this->email = $email;
        $this->url   = $url;
        $this->ns    = '';

        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
        
        $this->getZoneDetails();
        $rrs = $this->getResourceRecords();
        $this->processRecords($rrs);
    }

    private function getZoneDetails()
    {
        $response = $this->query(http_build_query(array(
                            'a'     => 'zone_load_multi',
                            'tkn'   => $this->token,
                            'email' => $this->email,
                            'z'     => $this->zone)));

        $response = json_decode(curl_exec($this->curl));
        $objs = $response->response->zones->objs;

        foreach ($objs as $zone) {

            if ($zone->zone_name != $this->zone) 
                continue;

            $zone->orig_ns_names = str_replace(array('{','}'), '', strtolower($zone->orig_ns_names));
            
            foreach(split(',', $zone->orig_ns_names) as $nameserver) {
                $this->resources[] = sprintf("%-10s %6s %6s %20s",
                    '@', 'IN', 'NS', $nameserver);            
            }
        }

            
    }

    private function getResourceRecords()
    {
        $response = $this->query(http_build_query(array(
                            'a'     => 'rec_load_all',
                            'tkn'   => $this->token,
                            'email' => $this->email,
                            'z'     => $this->zone)));
        
        $response->response->recs->count;

        if ($response->response->recs->count == 0)
            return 0;

        return $response->response->recs->objs;
    }

    private function query($post_data)
    { 
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_data);
        $response = json_decode(curl_exec($this->curl));

        if ($response->result == 'error') {
            throw new Exception($response->msg);
        }

        return $response;
    }

    private function processRecords($objs)
    {
        foreach ($objs as $record) {

            if ($record->display_name == $this->zone) $record->display_name = '@';
            if ($record->display_name == $this->zone) $record->content     .= '.';

            switch($record->type) {
                case 'MX':
                    $this->resources[] = sprintf("%-10s %6s %6s %5s %13s",
                        $record->display_name,
                        'IN',
                        $record->type,
                        $record->prio,
                        $record->content);
                    break;
                case 'SPF':
                    $this->resources[] = sprintf("%-10s %6s %6s %6s '%s'",
                        $record->display_name,
                        'IN',
                        $record->type,
                        '',
                        $record->content);
                    break;
                case 'TXT':
                    $this->resources[] = sprintf("%-10s %6s %6s %6s '%s'",
                        $record->display_name,
                        'IN',
                        $record->type,
                        '',
                        $record->content);
                    break;
               default:
                    $this->resources[] = sprintf("%-10s %6s %6s %20s",
                        $record->display_name,
                        'IN',
                        $record->type,
                        $record->content);
                    break;
            }
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->resources);
    }
}
?>
