<?php

namespace App\Console\Library;
require 'vendor/autoload.php';

use League\Csv\Reader;
use League\Csv\XMLConverter;

use Rs\JsonLines\JsonLines;
use OzdemirBurak\JsonCsv\File\Csv;



class CSVToVarious
{
    public function convertCSVToXML()
    {
        $file = new \SplFileObject('storage/app/out.csv', 'r');
        $csv = Reader::createFromFileObject($file);

        $converter = (new XMLConverter())
            ->rootElement('csv')
            ->recordElement('record', 'offset')
            ->fieldElement('field', 'name');

        $dom = $converter->convert($csv);
        $dom->formatOutput = true;
        $dom->encoding = 'iso-8859-15';
       

        //echo '<pre>', PHP_EOL;
        //echo htmlentities($dom->saveXML());
        file_put_contents('storage/app/out.xml', $dom->saveXML());

        echo("CSV Converted to XML");
        
    }
    public function convertCSVToJsonl()
    {
        // CSV to JSON
        $csv = new Csv('storage/app/out.csv');
        $csv->setConversionKey('options', JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        // To convert CSV to JSON string
        $jsonString = $csv->convert();
        // To convert CSV to JSON and save
        $csv->convertAndSave('storage/app/output.json');
        // To convert CSV to JSON and force download on browser

        $decodedJson = json_decode(file_get_contents('storage/app/output.json'), true);

        $jsonl = new JsonLines();
        $jsonl->enlineToFile($decodedJson, 'storage/app/out.jsonl');
       
       // file_put_contents('storage/app/output.jsonl',  $jsonl->enlineToFile($decodedJson) );
        echo("CSV Converted to JSONL");
    }
}
