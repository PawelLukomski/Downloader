<?php

namespace Manager;


class CurlManager
{
    protected $url;

    public function getMainHTML()
    {
        $color = new Colors();
        $count = 0;
        $proxy = new Proxy();
        do {
            $cSession = curl_init();

            $url = $this->url;

            curl_setopt($cSession, CURLOPT_URL, $url);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:58.0) Gecko/20100101 Firefox/58.0',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: pl,en-US;q=0.7,en;q=0.3',
                'Accept-Encoding: gzip, deflate',
                'DNT: 1',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
                'Cache-Control: max-age=0'
            ]);
            curl_setopt($cSession, CURLOPT_ENCODING, '');
            curl_setopt($cSession, CURLOPT_FOLLOWLOCATION, FALSE);
            curl_setopt($cSession, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($cSession, CURLOPT_TIMEOUT, 5);
            curl_setopt($cSession, CURLOPT_HEADER, false);
            curl_setopt($cSession, CURLOPT_PROXYTYPE, 'http');
            $result = curl_exec($cSession);
            $responseCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);
            $redirectUrl = curl_getinfo($cSession, CURLINFO_REDIRECT_URL);
            curl_close($cSession);
            //$htmlList = str_get_html($result);
            print_r("\n____________________________________________________________\n".$color->str("IP ROUTING....", "white", "red")."\n");
            //print_r($proxy->getProxy()[0]. "\n\n");
            $count += 1;
            if($count == 2) {
                print_r("\n____________________________________________________________\n" . $color->str("ALREADY 3 ROUTING [".$responseCode."]", "white", "cyan") . "\n");
                sleep(5);
            }
            //print_r($result);
        }
        while(!$result);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


}
