<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Library\DownloadAWS;
use App\Console\Library\JsonlToCSV;
use App\Console\Library\CSVToVarious;

class OrdersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo ("\n");
        //Download File from AWS
        $getFile = new DownloadAWS();
        $getFile->downloadFile();
        echo ("\n");

        //Generate File CSV File
        $generateCSV = new JsonlToCSV();
        $generateCSV->convertJsonlToCSV();
        echo ("\n");

        //Convert to Various Format
        $convertCSV = new CSVToVarious();
        $convertCSV->convertCSVToXML();
        echo ("\n");
        $convertCSV->convertCSVToJSONL();
        echo ("\n");
       


        
    }
}
