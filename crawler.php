<?php

$start = "http://localhost/webCrawler/sites.html";


$already_crawled = [];
$crawling = [];

function get_details($url) {

     $options = ['http'=>[
          'method'=>"GET",
          'headers'=>"User-Agent: MilciBot/0.1\n"
          ]];

     $context = stream_context_create($options);

     $doc = new DOMDocument();
     @$doc->loadHTML(@file_get_contents($url, false, $context));

     $title = $doc->getElementsByTagName("title");
     $title = $title->item(0)->nodeValue;

     $description = "";
     $keywords = "";

     $langs = $doc->getElementsByTagName("html");
     for ($i = 0; $i < $langs->length; $i++) {
          $langOp = $langs->item($i);
          $lang = $langOp->getAttribute("lang");

     }

     $metas = $doc->getElementsByTagName("meta");
     for ($i = 0; $i < $metas->length; $i++) {
          $meta = $metas->item($i);

          if ($meta->getAttribute("name") == strtolower("description")) {
               $description = $meta->getAttribute("content");
          }
          if ($meta->getAttribute("name") == strtolower("keywords")) {
               $keywords = $meta->getAttribute("content");
          }
     }

     return '{ "Title": "' . str_replace("\n", "", $title) . '", "Description": "' . str_replace("\n", "", $description) . '", "Keywords": "' . str_replace("\n", "", $keywords) . '", "URL": "' . $url . '", "Lang": "' . str_replace("\n", "", $lang) . '"}';

}

function follow_links($url) {

     global $already_crawled;
     global $crawling;
     global $pdo;

     $options = ['http'=>[
          'method'=>"GET",
          'headers'=>"User-Agent: MilciBot/0.1\n"
          ]];

     $context = stream_context_create($options);

     $doc = new DOMDocument();
     @$doc->loadHTML(@file_get_contents($url, false, $context));

     $linklist = $doc->getElementsByTagName("a");

     foreach ($linklist as $link) {
          $l = $link->getAttribute("href");

          if (substr($l, 0, 1) == "/" && substr($l, 0, 2) != "//") {
               $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . $l;
          }else if (substr($l, 0, 2) == "//") {
               $l = parse_url($url)["scheme"] . ":" . $l;
          }else if (substr($l, 0, 2) == "./") {
               $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . dirname(parse_url($url)["path"]) . substr($l, 1);
          }else if (substr($l, 0, 1) == "#") {
               $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . parse_url($url)["path"] . $l;
          }else if (substr($l, 0, 3) == "../") {
               $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . "/" . $l;
          }else if (substr($l, 0, 11) == "javascript:") {
               continue;
          }else if (substr($l, 0, 5) != "https" && substr($l, 0, 4) != "http") {
               $l = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . "/" . $l;
          }

          if (!in_array($l, $already_crawled)) {
               $already_crawled[] = $l;
               $crawling[] = $l;

               $details = json_decode(get_details($l));
               echo $details->URL . " ";

               $rows = $pdo->query("SELECT * FROM `search_index` WHERE url_hash='" . md5($details->URL) . "'");
               $rows = $rows->fetchColumn();

               $params = [':title'=>$details->Title, ':description'=>$details->Description, ':lang'=>$details->Lang, ':keywords'=>$details->Keywords, ':url'=>$details->URL, ':url_hash'=>md5($details->URL)];

               if ($rows > 0) {
                    if (!is_null($params[':title']) && !is_null($params[':description']) && $params[':title'] != '' && !is_null($params[':lang'])) {
                         $result = $pdo->prepare("UPDATE `search_index` SET title=:title, description=:description, lang=:lang, keywords=:keywords, url=:url, url_hash:url_hash WHERE url_hash=:url_hash");
                         $result = $result->execute($params);
                    }
               }else {
                    if (!is_null($params[':title']) && !is_null($params[':description']) && $params[':title'] != '' && !is_null($params[':lang'])) {
                         $result = $pdo->prepare("INSERT INTO `search_index` VALUES ('', :title, :description, :lang, :keywords, :url, :url_hash)");
                         $result = $result->execute($params);
                    }

               }
               // echo get_details($l) . "\n";
          }

     }

     array_shift($crawling);
     foreach($crawling as $site) {
          follow_links($site);
     }

}

follow_links($start);
