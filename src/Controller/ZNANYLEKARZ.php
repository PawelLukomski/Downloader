<?php

namespace Controller;
require __DIR__.'/../advanced_html_dom.php';

class ZNANYLEKARZ extends Controller
{
    CONST MAIN_URL = 'https://www.znanylekarz.pl';
    CONST MAIN_PAGE = '/specjalizacje-lekarskie';
    CONST FILE_TO_SAVE = __DIR__."/../../resources/znanylekarz.csv";

    protected $currentPage = 1;

    protected $start;

    protected $count;

    public function __construct($start = null, $count = null)
    {
        $this->start = $start;
        $this->count = $count;
    }

    public function getData()
    {
        $mainUrl = self::MAIN_URL.self::MAIN_PAGE;
        $fileS = fopen(self::FILE_TO_SAVE, "a+");
        $this->setUrl($mainUrl);
        //$htmlData = $this->getMainHTML();
        //print_r($htmlData);
        //$pageData = str_get_html($htmlData);
        $links = file(__DIR__."/../../list.dat");

        if($this->getStart() == 0)
        {
            $this->setStart(0);
            $this->setCount(10);
        }
        else
        {
            $this->setStart($this->getStart()*$this->getCount());
            $this->setCount($this->getStart()+$this->getCount());
        }

        for ($i = $this->getStart(); $i < $this->getCount(); $i++)
        {
            print_r(trim($links[$i])."\n");
        }
        /*
        foreach ($pageData->find('div.panel-body > div.offset-top-1 > a.text-muted') as $list) {
            print_r($list->href."\n");
        */
        for ($i = $this->getStart(); $i < $this->getCount(); $i++){
            $this->setUrl(trim($links[$i]));
            $list2Data = str_get_html($this->getMainHTML());
            foreach ($list2Data->find("section.panel a") as $list2) {
                print_r($list2->href);
                $this->setCurrentPage(1);
                print_r($list2->plaintext . "\n");
                do {
                    $this->setUrl(self::MAIN_URL . $list2->href . "/" . $this->getCurrentPage());
                    var_dump($this->getUrl());
                    $listHTML = $this->getMainHTML();
                    $listData = str_get_html($listHTML);
                    foreach ($listData->find("div.panel-default") as $offerList) {
                        $companyData = [];
                        foreach ($offerList->find('div[class="panel-body rank-element padding-top-2 padding-bottom-1"]') as $row) {
                            if ($getName = $row->find("a.rank-element-name__link")) {
                                if ($getName->plaintext != '') {
                                    $name = $getName->plaintext;
                                    //print_r($name."\n");
                                    //print_r($getName->href."\n");
                                    $companyData['name'] = $name;
                                    $companyData['link'] = $getName->href;
                                }
                            }

                            if ($getStreet = $row->find('span.street')) {
                                //print_r($getStreet->plaintext."\n");
                                if (count($getStreet) > 1) {
                                    $companyData['street'] = $getStreet[0]->plaintext;
                                } else {
                                    $companyData['street'] = $getStreet->plaintext;
                                }
                            }
                            if ($getCity = $row->find('span.city')) {
                                //var_dump($getCity->plaintext);

                                if (count($getCity) > 1) {
                                    $companyData['city'] = $getCity[0]->plaintext;
                                } else {
                                    $companyData['city'] = $getCity->plaintext;
                                }
                                if(count($getCity) > 1)
                                {
                                    foreach ($getCity as $key => $item)
                                        print_r($item->plaintext."\n\n");
                                }
                            }

                            if ($upRating = $row->find('div[class="rank-element-rating offset-bottom-1 offset-bottom-1 clearfix"]')) {
                                if ($getRating = $upRating->find('a[class="rating rating--md text-muted"]')) {
                                    preg_match("/(data-score\=\")(\d)(\")/m", $getRating, $ratingF);
                                    if (isset($ratingF[2])) {
                                        //var_dump($ratingF[2]);
                                        $companyData['rating'] = $ratingF[2];
                                    } else {
                                        //var_dump($ratingF);
                                        preg_match("/(data-score\=\")(\d\.\d)(\")/m", $getRating, $ratingF2);
                                        $companyData['rating'] = $ratingF2[2];
                                    }
                                }
                                else
                                {
                                    $companyData['rating'] = 0;
                                }
                            }
                            if ($getCountRating = $row->find('a[class="rating rating--md text-muted"]')) {
                                //print_r($getCountRating->plaintext."\n");


                                preg_match("/(\()(\d*)(\sopinii\))/m", $getCountRating->plaintext, $countRatingF);
                                if (isset($countRatingF[2])) {
                                    $companyData['count_rating'] = $countRatingF[2];
                                } else {
                                    $companyData['count_rating'] = 0;
                                }
                            }


                            //print_r($phoneF[2]);


                        }
                        if (!empty($companyData)) {
                            print_r(json_encode($companyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n");
                            fputs($fileS, json_encode($companyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
                        }

                        //var_dump($companyData);

                    }
                    $nextPage = $listData->find("ul.pagination > li.next", 0);
                    if ($nextPage) {
                        $this->setCurrentPage($this->getCurrentPage() + 1);
                    }
                } while ($nextPage);
            }

        }

        fclose($fileS);
//        var_dump((array)$filter);
    }
    

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param null $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param null $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }



}
