<?php
class CloudFlare implements IteratorAggregate
{
    private $domains   = array();
 
    public function __construct($url = '', $token = '', $email = '')
    {
        $this->token = $token;
        $this->email = $email;
        $this->url   = $url;
        
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);

        $this->getZones();        
    }

    
    private function getZones()
    {
        $response = $this->query(http_build_query(array(
            'a'     => 'zone_load_multi',
            'tkn'   => $this->token,
            'email' => $this->email)));
        

        $response = json_decode(curl_exec($this->curl));
        $objs = $response->response->zones->objs;
        
        foreach ($objs as $zone) {
            $rrs = $this->getResourceRecords($zone->zone_name);
            $this->domains[$zone->zone_name] = $this->processRecords($rrs, $zone->zone_name);
        }
    }



    private function getResourceRecords($zone)
    {
        $response = $this->query(http_build_query(array(
            'a'     => 'rec_load_all',
            'tkn'   => $this->token,
            'email' => $this->email,
            'z'     => $zone)));
        
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

    private function processRecords($objs, $zone)
    {
        $resources = array();

        foreach ($objs as $record) {
            
            if ($record->display_name == $zone) {
                $record->display_name .= '.';
            } else  {
                $record->display_name = $record->display_name . '.' . $zone . '.';
            }

            switch($record->type) {
                case 'MX':
                    $resources[] = sprintf("%-20s %6s %6s %5s %13s",
                        $record->display_name,
                        'IN',
                        $record->type,
                        $record->prio,
                        $record->content);
                    break;
                case 'SPF':
                    $resources[] = sprintf("%-20s %6s %6s %6s '%s'",
                        $record->display_name,
                        'IN',
                        $record->type,
                        '',
                        $record->content);
                    break;
                case 'TXT':
                    $resources[] = sprintf("%-20s %6s %6s %6s '%s'",
                        $record->display_name,
                        'IN',
                        $record->type,
                        '',
                        $record->content);
                    break;
               default:
                    $resources[] = sprintf("%-20s %6s %6s %20s",
                        $record->display_name,
                        'IN',
                        $record->type,
                        $record->content);
                    break;
            }
        }

        return $resources;
    }

/*
    private function getZoneNs()
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
            
            foreach(explode(',', $zone->orig_ns_names) as $nameserver) {
                $this->resources[] = sprintf("%-10s %6s %6s %20s",
                    '@', 'IN', 'NS', $nameserver);            
            }
        }
    }
*/
    public function getIterator()
    {
        return new ArrayIterator($this->domains);
    }
}
?>
