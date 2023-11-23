<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;

class CsvController extends Controller
{
    public function csvToJson()
    {
        $filePath = storage_path('../Dummy/Book1.csv');
        $outPutFilePath = storage_path('app/processed_sales_data.csv');

        $csvReader = Reader::createFromPath($filePath);
        $csvWriter = Writer::createFromPath($outPutFilePath, 'w+');

        $records = $csvReader->getRecords();

        $header = ['Department Name', 'Total Number of Sales'];


        $salesByDepartment = [];

        foreach ($records as $record) {
       
            if (isset($record[0]) && isset($record[2])) {

                $department = $record[0];


                $sales = $record[2];

          
                if (isset($salesByDepartment[$department])) {
                    $salesByDepartment[$department] += $sales;
                } else {
                    $salesByDepartment[$department] = $sales;
                }
            }
        }

    
        foreach ($salesByDepartment as $department => $totalSales) {


            $csvWriter->insertOne([$department, $totalSales]);



        }

        return response()->json(['message' => 'Sales data processed successfully.', 'output_file' => $outPutFilePath]);
    }
}
