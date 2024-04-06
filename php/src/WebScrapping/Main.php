<?php

namespace Chuva\Php\WebScrapping;

require_once 'vendor/autoload.php';

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;

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

    $filename = 'teste.xlsx';
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile($filename);

    $style_columns = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontName('Arial')
                    ->setFontSize(14)
                    ->setFontColor(Color::WHITE)
                    ->setCellAlignment(CellAlignment::CENTER)
                    ->setBackgroundColor(Color::BLUE)
                    ->build();
    
    $style_items = (new StyleBuilder())
                    ->setFontName('Arial')
                    ->setFontSize(10)
                    ->setFontColor(Color::BLACK)
                    ->setShouldWrapText(true)
                    ->setCellAlignment(CellAlignment::LEFT)
                    ->setBackgroundColor(Color::WHITE)
                    ->build();

    $writer->addRow(WriterEntityFactory::createRowFromArray($columns_name, $style_columns));

    foreach ($data as $item) {
      $writer->addRow(WriterEntityFactory::createRowFromArray($item, $style_items));
    }

    $writer->close();
  }

}

Main::run();
