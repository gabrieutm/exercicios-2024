<?php

namespace Chuva\Php\WebScrapping;

require_once 'vendor/autoload.php';

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');

    libxml_use_internal_errors(TRUE);
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');
    libxml_use_internal_errors(FALSE);
    $scrapper = new Scrapper();
    $result = $scrapper->scrap($dom);
    $columns_name = $result['columns'];
    $data = $result['data'];

    self::createCsv($data, $columns_name);
  }

  private static function createCsv(array $data, $columns_name): void {
    $filename = 'teste.csv';
    $writer = WriterEntityFactory::createCSVWriter();
    $writer->openToFile($filename);

    $writer->addRow(WriterEntityFactory::createRowFromArray($columns_name));

    foreach ($data as $item) {
      $writer->addRow(WriterEntityFactory::createRowFromArray($item));
    }

    $writer->close();
  }

}

// Executa o processo, teste workflow;
Main::run();