<?php

namespace Controller;
require __DIR__.'/../advanced_html_dom.php';

class MIASTA extends Controller
{
    const MAIN_URL = "http://www.polskawliczbach.pl/Miasta";

    protected $currentPage;

    public function getData()
    {
        $this->setUrl(self::MAIN_URL);
        $mainPage = str_get_html($this->getMainHTML());
        foreach ($mainPage->find("table[class='table table-striped table-condensed dataTable'] tbody") as $list)
        {
            foreach($list->find("tr") as $row)
            {
                $cityData = [];
                if($city = $row->find("td")) {
                    //var_dump($city->plaintext);
                    $cityName = $city[1];
                    $cityV = $city[3];
                    $cityData['voivodeship'] = $cityV->plaintext;
                    $cityData['city'] = $cityName->plaintext;

                    //print_r("".$cityName->plaintext."  ==>  ".$cityV->plaintext."\n");
                }
                print_r(json_encode($cityData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }


}