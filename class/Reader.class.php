<?php
/**
 * Created by PhpStorm.
 * User: Lukafurlan
 * Date: 8/29/2016
 * Time: 12:28 AM
 */

class Reader {

    private $rss = array();
    private $array = array();

    const MONTHS = array("Jan" => "01",
        "Feb" => "02",
        "Mar" => "03",
        "Apr" => "04",
        "May" => "05",
        "Jun" => "06",
        "Jul" => "07",
        "Aug" => "08",
        "Sep" => "09",
        "Oct" => "10",
        "Nov" => "11",
        "Dec" => "12");

    /**
     * @param $url
     */
    public function addURL($url)
    {
        array_push($this->rss, $url);
    }

    /**
     * Puts data from different rss feeds in one array
     */
    public function constructArray()
    {
        foreach($this->rss as $row) {
            $load = simplexml_load_file($row);
            foreach($load->channel->item as $item) {
                array_push($this->array, array("title" => $item->title, "description" => strip_tags($item->description), "pubDate" => $this->formatDate($item->pubDate), "origDate" => $item->pubDate, "link" => $item->link));
            }
        }
        $this->array = $this->sksort($this->array, "pubDate");
    }

    /**
     * @return array
     */
    public function displayArray() {
        return $this->array;
    }

    /**
     * @param $date
     * @return string
     */
    public function formatDate($date)
    {
        $parts = explode(" ", $date);
        $day = $parts[1];
        $month = Self::MONTHS[$parts[2]];
        $year = $parts[3];
        $time = explode(":", $parts[4]);
        return  $year.$month.$day.$time[0].$time[1].$time[2];
    }


    /**
     * Credits to serpro@gmail.com
     * @param $array
     * @param string $subkey
     * @param bool $sort_ascending
     * @return array
     */
    public function sksort(&$array, $subkey="id", $sort_ascending=false) {

        if (count($array))
            $temp_array[key($array)] = array_shift($array);

        foreach($array as $key => $val){
            $offset = 0;
            $found = false;
            foreach($temp_array as $tmp_key => $tmp_val)
            {
                if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
                {
                    $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                        array($key => $val),
                        array_slice($temp_array,$offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
        }

        if ($sort_ascending) $array = array_reverse($temp_array);

        else $array = $temp_array;

        return $array;
    }

}