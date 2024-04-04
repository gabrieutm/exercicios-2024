<?php

namespace Chuva\Php\WebScrapping;

use DOMDocument;
use DOMXPath;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Scrapes the HTML document and returns an array of data.
   */
  public function scrap(DOMDocument $dom): array {
    $xpath = new DOMXPath($dom);
    $proceedings = $xpath->query('//a[@class="paper-card p-lg bd-gradient-left"]');
    
    $data = [];
    
    foreach ($proceedings as $proceeding) {
      $paper = [];
      
      $info_id = $xpath->query('.//div[@class="volume-info"]', $proceeding)->item(0)->nodeValue;
      $info_title = $xpath->query('.//h4[@class="my-xs paper-title"]', $proceeding)->item(0)->nodeValue;
      $info_type = $xpath->query('.//div[@class="tags mr-sm"]', $proceeding)->item(0)->nodeValue;
      
      $paper['ID'] = $info_id;
      $paper['Title'] = $info_title;
      $paper['Type'] = $info_type;
      
      $authors = [];
      $author_nodes = $xpath->query('.//div[@class="authors"]/span', $proceeding);
      foreach ($author_nodes as $author_node) {
        $authors[] = $author_node->nodeValue;
      }
      
      $institutes = [];
      $institute_nodes = $xpath->query('.//div[@class="authors"]/span[@title]/@title', $proceeding);
      foreach ($institute_nodes as $institute_node) {
        $institutes[] = $institute_node->nodeValue;
      }
      
      $paper['Authors'] = $authors;
      $paper['Institutes'] = $institutes;
      
      $data[] = $paper;
    }
    
    return $data;
  }
}
