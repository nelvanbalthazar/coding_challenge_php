<?php

namespace App\Console\Library;

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class DownloadAWS 
{
    public function downloadThroughAWSSDK(){
        // $s3_file = Storage::cloud()->get('challenge-1-in.jsonl');
        // $s3      = Storage::disk('local')->put('Order.jsonl', $s3_file);
        $bucket = 'catch-code-challenge';
        $keyname = 'challenge-1-in.jsonl';
    
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-southeast-2'
        ]);
        
        try {
            // Get the object.
            $result = $s3->getObject([
                'Bucket' => $bucket,
                'Key'    => $keyname,
                
            ]);
        
            // Display the object in the browser.
            header("Content-Type: {$result['ContentType']}");
            echo $result['Body'];
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
       
        }
    }


    public function downloadFile(){
        $url    = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl';
        $guzzle = new Client();
        $response = $guzzle->get($url);
        Storage::put('order.jsonl', $response->getBody());
        echo "File Downloaded Successfully";
    }
}
