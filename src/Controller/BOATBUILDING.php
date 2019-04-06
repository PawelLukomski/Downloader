<?php

namespace Controller;
require __DIR__.'/../advanced_html_dom.php';

class BOATBUILDING extends Controller
{
    CONST MAIN_URL = 'boatbuilding.com.pl';
    CONST FILE_TO_SAVE = __DIR__."/../../resources/pneumatyczne.csv";

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
        $mainUrl = self::MAIN_URL;
        $fileS = fopen(self::FILE_TO_SAVE, "a+");
        $this->setUrl($mainUrl);
        $htmlData = $this->getMainHTML();
        //print_r($htmlData);
        $pageData = str_get_html($htmlData);

        foreach ($pageData->find("div.menu table.moduletable table td a") as $categories)
        {
            print_r($categories->plaintext."\n");
            if($categories->plaintext == "Łodzie pneumatyczne")
            {
            $this->setUrl($categories->href);
            $underHtml = str_get_html($this->getMainHTML());
            $boatData = [];
            foreach ($underHtml->find("div.menu table.moduletable table tr[align='left'] div a") as $list)
            {

                $boatData['name'] = $list->plaintext;
                print_r(" - ".$list->href."\n");
                $this->setUrl($list->href);
                $boatPage = str_get_html($this->getMainHTML());
                foreach ($boatPage->find("div.content") as $page) {
                    /*
                    if ($desc = $page->find("div.opis"))
                        $boatData['desc'] = $desc->plaintext;
                    */

                    //var_dump($boatData);



                    $wlas = [];
                    foreach ($page->find("table.dane_silnika") as $wlasc) {
                        //preg_match_all("/^[a-zA-Z\ł\ś\ć\.\ą\ę\ń\ó\ż\s]*/m", $wlasc->html(), $filterW);
                        //foreach ($filterW as $value)
                            //foreach ($value as $line)
                                //if ($line != "")
                      /*              //$wlas[] = trim($line);
                        foreach ($wlasc->find("tr") as $tr)
                        {
                            if($par1 = $tr->find("td.dane_silnika"))
                                $parName = $par1->plaintext;
                            if($par2 = $tr->find("td.dane_silnika2"))
                                $properties[$parName] = $par2->plaintext;
                        }

                        //print_r("\n\n\n".$wlasc->html());
                        //var_dump($wlas);
*/
                    }
                    /*
                    $wart = [];
                    foreach ($page->find("div.info_wart") as $wlasc) {
                        $filterV = explode("\n", $wlasc->text());
                        foreach ($filterV as $value)
                            if (trim($value) != "")
                                $wart[] = trim($value);

                        //print_r("\n\n\n".$wlasc->html());
                        //var_dump($wart);

                    }
                    $properties = [];
                    foreach ($wlas as $key => $wla) {
                        $properties[$wla] = $wart[$key];
                    }
                    */
                    //$boatData['properties'] = $properties;

                    $gallery = [];
                    $this->setUrl($list->href."&limit=1&limitstart=2");
                    $galleryPage = str_get_html($this->getMainHTML());
                    foreach ($galleryPage->find("table.contentpaneopen td img") as $img)
                    {
                        //print_r($img->href);
                        $gallery[] = $img->src;
                    }
                    $boatData['gallery'] = $gallery;
                    print_r(json_encode($boatData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n\n");
                    fputs($fileS, json_encode($boatData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");
                    //var_dump($properties);
                }
            }
            }

        }
        fclose($fileS);
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
