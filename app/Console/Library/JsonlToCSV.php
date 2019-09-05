<?php

namespace App\Console\Library;
require 'vendor/autoload.php';


use Rs\JsonLines\JsonLines;
use OzdemirBurak\JsonCsv\File\Json;


class JsonlToCSV
{
    public function convertJsonlToCSV()
    {
        $json        = new JsonLines();
        $jsonData    = $json->delineFromFile('storage/app/order.jsonl');
        $decodedJson = json_decode($jsonData, true);
        $CSVData     = [];
        $count       = count($decodedJson);

        //Values Initialization
        $totalOrderValue = 0;
        $averageUnitPrice = 0;
        $productId=[];
        $distinctUnitCount=0;

        for($i=0; $i< $count; $i++)
        {
            $CSVData[$i]['orderId'] = $decodedJson[$i]['order_id'];
            $CSVData[$i]['orderDateTime'] = $decodedJson[$i]['order_date'];

           
            for($j=0; $j< count($decodedJson[$i]['items']); $j++)
            {
                //Count Average Unit Price
                $averageUnitPrice = ($averageUnitPrice + $decodedJson[$i]['items'][$j]['unit_price'])/count($decodedJson[$i]['items']);

                //Count Distinct Unit
                array_push($productId,$decodedJson[$i]['items'][$j]['product']['product_id']);
                $distinctUnitCount = count(array_unique($productId));

                //Count Total Order Value
                if(count($decodedJson[$i]['discounts']) == 0)
                {
                    $totalOrderValue = $totalOrderValue + ($decodedJson[$i]['items'][$j]['quantity'] * $decodedJson[$i]['items'][$j]['unit_price']);
                } 
                else {
                    if($decodedJson[$i]['discounts'][0]['type']=="DOLLAR")
                    {
                        $totalOrderValue = $totalOrderValue + (($decodedJson[$i]['items'][$j]['quantity'] * $decodedJson[$i]['items'][$j]['unit_price']) - $decodedJson[$i]['discounts'][0]['value']);
                    }
                    else if($decodedJson[$i]['discounts'][0]['type']=="PERCENTAGE")
                    {
                        $totalOrderValue = $totalOrderValue + (($decodedJson[$i]['items'][$j]['quantity'] * $decodedJson[$i]['items'][$j]['unit_price']) * (1-($decodedJson[$i]['discounts'][0]['value']/100)));

                    }
                }
            }
            $CSVData[$i]['totalOrderValue']    = $totalOrderValue;
            $CSVData[$i]['disctinctUnitCount'] = $distinctUnitCount;

            //Reinitilize Values
            $totalOrderValue = 0; 
            $productId=[];
            $distinctUnitCount=0;


            $CSVData[$i]['averageUnitPrice']  = $averageUnitPrice; 
            $CSVData[$i]['totalUnitsCount']   = count($decodedJson[$i]['items']);
            $CSVData[$i]['customerState']      = $decodedJson[$i]['customer']['shipping_address']['state'];

        }


        file_put_contents('storage/app/out.json', json_encode($CSVData));
      
        $jsonFile   = new Json('storage/app/out.json');
        $jsonFile->convertAndSave('storage/app/out.csv');
       
        echo ("CSV File Generated Successfully ");
    }
}
