<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmployees;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function uploadFile(Request $request)
    {
        try {
            if ($request->hasFile('csvFile')) {
                $filename = $request->csvFile->getClientOriginalName();
                $fileWithpath = public_path('uploads'). '/' . $filename;

                if (!file_exists($fileWithpath)) {
                    $request->csvFile->move(public_path('upload'), $filename);
                }

                $header = null;
                $dataFromCsv = [];
                $records = array_map('str_getcsv', file($fileWithpath));

                foreach ($records as $record) {
                    if (!$header)
                        $header = $record;
                    else
                        $dataFromCsv[] = $record;
                }

                $dataFromCsv = array_chunk($dataFromCsv, 300);
                $employeeData = [];

                foreach ($dataFromCsv as $key => $dataCsv) {
                    foreach ($dataCsv as $value) {
                        $employeeData[$key][] = array_combine($header, $value);
                    }
                    // ProcessEmployees::dispatch($employeeData[$key]);
                }

                dd($employeeData);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
