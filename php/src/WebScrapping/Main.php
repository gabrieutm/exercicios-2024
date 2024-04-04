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

    libxml_use_internal_errors(true);
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');
    libxml_use_internal_errors(false);
    
    $scrapper = new Scrapper();
    $data = $scrapper->scrap($dom);

    self::createCSV($data);
  }

  /**
   * Creates a CSV file from the extracted data.
   */
  private static function createCSV(array $data): void {
    $filename = 'teste.csv';
    $writer = WriterEntityFactory::createCSVWriter();
    $writer->openToFile($filename);

    foreach ($data as $item) {
      $writer->addRow(WriterEntityFactory::createRowFromArray($item));
    }

    $writer->close();
  }
}

// Executa o processo
Main::run();
