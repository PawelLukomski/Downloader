<?php

namespace Controller;
require __DIR__.'/../advanced_html_dom.php';

class RANKINGLEKARZY extends Controller
{
    CONST MAIN_URL = 'https://www.rankinglekarzy.pl';
    CONST MAIN_PAGE = '/lekarze/';
    CONST FILE_TO_SAVE = __DIR__."/../../resources/rankinglekarzy3.csv";

    protected $start = 0;

    protected $currentPage = 1;

    public function __construct($start = null)
    {
        $this->start = $start;
    }

    public function getData()
    {


            $page = $this->getStart()*500;
            $this->setCurrentPage($page);
            var_dump($page);
        


        $counter = 0;
        do {
            if($counter == 500)
                die;

            $mainUrl = self::MAIN_URL.self::MAIN_PAGE.$this->getCurrentPage();
            $fileS = fopen(self::FILE_TO_SAVE, "a+");
            $this->setUrl($mainUrl);
            $htmlData = $this->getMainHTML();
            //print_r($htmlData);
            $pageData = str_get_html($htmlData);
            print_r("\n\n".$mainUrl."\n\n");
            foreach ($pageData->find("div.rl-column") as $list) {
                $companyData = [];
                foreach ($list->find("div.rl-box") as $row) {
                    if ($name = $row->find("span.rl-profile-title__span"))
                        $companyData['name'] = $name->plaintext;

                        /*
                        if($linkProfile = $row->find("div.rl-profil__topHeaderDetails > a", 1))
                    {

                                $profileUrl = self::MAIN_URL . $linkProfile->href;
                                if (preg_match("/(https\:\/\/www\.rankinglekarzy.pl\/)(.*)(\d)(\/)/m", $profileUrl)) {
                                    //preg_match("/(https\:\/\/www\.rankinglekarzy.pl\/)(.*)(\-)(\d*)(\/.*)/m", $profileUrl, $profileUrlF);
                                    $profileUrl = preg_replace("/(https\:\/\/www\.rankinglekarzy.pl\/)(.*)(\-)(\d*)(\/.*)(\#opinie)/", "$1$2$5-$4$6", $profileUrl);
                                }
                                if($profileUrl != "https://www.rankinglekarzy.pl") {
				    $companyData['link'] = $profileUrl;
                                    $this->setUrl($profileUrl);
                                    print_r("GOING TO: " . $profileUrl);
                                    $profileData = str_get_html($this->getMainHTML());
                                    if ($phoneNumber = $profileData->find("span[itemprop='telephone']", 0)) {
                                        if(preg_match("/(\<span\sitemprop\=\"telephone\"\scontent\=\")(.*)(\".*)/m", $phoneNumber->html(), $phoneNumberF1))
                                        {
                                            print_r("\n\n" . $phoneNumberF1[2] . "\n\n");
                                            $companyData['phone'] = $phoneNumberF1[2];
                                        }
                                        else
                                        {
                                            print_r("\n\nNO PHONE NUMBER\n\n");
                                            $companyData['phone'] = null;
                                        }
                                    }
                                }
				else
				{
				    $companyData['link'] = "";
				}

                    }
                        */
                    if($category = $row->find("h4.rl-profile-subtitle"))
                    {
                        //print_r($category->plaintext."\n\n");
                        $companyData['category'] = $category->plaintext;
                    }


                    if ($countRating = $row->find("span[class='rl-opinion-count']")) {
                        preg_match("/(liczba\sopinii\:\s)(\d*)/m", $countRating->plaintext, $countRatingF);
                        //print_r($countRatingF[2] . "\n");
                        $companyData['count_rating'] = $countRatingF[2];
                    }
                    if ($rating = $row->find("span[class='rl-stars rl-stars--big']")) {
                        preg_match("/(\<span\sclass\=\"rl\-stars\srl\-stars\-\-big\"\sdata\-value\=\")(\d\,?\d?)(\/\d\"\>\<\/span\>)/m", $rating->html(), $ratingF);
                        $companyData['rating'] = $ratingF[2];
                        //print_r($ratingF[2] . "\n");
                    }

                    foreach ($row->find("div.rl-address__street") as $address) {
                        foreach ($address->find("meta") as $point) {
                            preg_match("/(\<meta\scontent\=\")(.*)(\")\s(itemprop\=\")(.*)(\"\>)/m", $point->html(), $addressF);
                            if ($addressF[5] == "streetAddress")
                                $companyData['street'] = $addressF[2];

                            if ($addressF[5] == "addressLocality")
                                $companyData['city'] = $addressF[2];

                            if ($addressF[5] == "postalCode")
                                $companyData['postal'] = $addressF[2];


                        }
                    }
                    print_r(json_encode($companyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");
                    fputs($fileS, json_encode($companyData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");
                    $companyData = [];
                }


            }
            $next = $pageData->find("div.rl-profile__pagination > nav > a.arr-next", 1);
            $currentPage = $this->getCurrentPage();
            $this->setCurrentPage($currentPage+1);
            $counter++;
            $statusF = fopen(__DIR__."/../../statusranking.dat", "w");
            fputs($statusF, $currentPage);
            fclose($statusF);
        }
        while(!$next);
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
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }




}
