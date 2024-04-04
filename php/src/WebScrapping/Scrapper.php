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
    
    foreach($proceedings as $proceeding) {

      $matches_authors_array = [];
      $matches_institutes_array = [];

      $info_id = $xpath->query('.//div[@class="volume-info"]', $proceeding);
      foreach($info_id as $id_info) {
          $matches_id[] = $id_info->nodeValue;
      }

      $info_title = $xpath->query('.//h4[@class="my-xs paper-title"]', $proceeding);
      foreach($info_title as $title_info) {
          $matches_title[] = $title_info->nodeValue;
      }

      $info_type = $xpath->query('.//div[@class="tags mr-sm"]', $proceeding);
      foreach($info_type as $type_info) {
          $matches_type[] = $type_info->nodeValue;
      }

      $info_author = $xpath->query('.//div[@class="authors"]/span', $proceeding);
      foreach ($info_author as $author_info) {
          $matches_authors_array[] = rtrim($author_info->nodeValue, ';');
      }
      $matches_authors[] = $matches_authors_array;

      $info_institute = $xpath->query('.//div[@class="authors"]/span[@title]/@title', $proceeding);
      foreach ($info_institute as $institute_info) {
          $matches_institutes_array[] = $institute_info->nodeValue;
      }
      $matches_institutes[] = $matches_institutes_array;
  }

  $max_authors_count = 0;

  foreach ($matches_authors as $authors) {
      $count = count($authors);
      if ($count > $max_authors_count) {
          $max_authors_count = $count;
      }
  }

  $columns_name = ['ID', 'Title', 'Type'];
  for ($i = 1; $i <= $max_authors_count; $i++) {
      $columns_name[] = "Author $i";
      $columns_name[] = "Author $i Institute";
  }

  foreach ($matches_id as $i => $id) {
    $title = $matches_title[$i]; // Obtém o título para este projeto
    $type = $matches_type[$i]; // Obtém o tipo para este projeto
    $authors = $matches_authors[$i]; // Obtém os autores para este projeto
    $institutes = $matches_institutes[$i]; // Obtém os institutos para este projeto
    $row = [$id, $title, $type]; // Inicializa o array com o ID, título e tipo

    // Adiciona cada autor e seu respectivo instituto ao array de linha
    for ($j = 0; $j < $max_authors_count; $j++) {
        if (isset($authors[$j])) {
            $row[] = $authors[$j]; // Adiciona o autor
            if (isset($institutes[$j])) {
                $row[] = $institutes[$j]; // Adiciona o instituto do autor
            } else {
                $row[] = ''; // Se não houver instituto correspondente, adiciona uma string vazia
            }
        } else {
            $row[] = ''; // Se não houver autor correspondente, adiciona duas strings vazias (autor e instituto)
            $row[] = '';
        }
    }

    $data[] = $row;

  }

  return ['columns' => $columns_name, 'data' => $data];

}
}