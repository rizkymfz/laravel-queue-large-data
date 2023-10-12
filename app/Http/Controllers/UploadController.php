<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmployees;
use App\Models\JobBatch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function progress()
    {
        return view('progress');
    }

    /**
     * Upload File and Store To Database
     *
     * @param Request $request
     * @return void
     */
    public function uploadFile(Request $request)
    {
        try {
            if ($request->hasFile('csvFile')) {
                $fileName     = $request->csvFile->getClientOriginalName();
                $fileWithPath = public_path('uploads'). '/' . $fileName;

                if (!file_exists($fileWithPath)) {
                    $request->csvFile->move(public_path('uploads'), $fileName);
                }

                $header = null;
                $dataFromCsv = array();
                $records = array_map('str_getcsv', file($fileWithPath));

                //re-arranging data
                foreach ($records as $record) {
                    if (!$header)
                        $header = $record;
                    else
                        $dataFromCsv[] = $record;
                }
                
                //pecah data from 10k to 1k/300 each.
                $dataFromCsv  = array_chunk($dataFromCsv, 300);
                $employeeData = array();
                $batch = Bus::batch([])->dispatch();
                
                //Looping through each 1000/300 employees.
                foreach ($dataFromCsv as $key => $dataCsv) {
                    //Looping through each employee data.
                    foreach ($dataCsv as $data) {
                        $employeeData[$key][] = array_combine($header, $data);
                    }
                    $batch->add(new ProcessEmployees($employeeData[$key]));
                    // ProcessEmployees::dispatch($employeeData[$key]);
                }
            }
            //update session id everytime process new batch
            session()->put('lastBatchId', $batch->id);

            return redirect('/progress?id='. $batch->id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Function to get the progress while jobs exec
     *
     * @param Request $request
     * @return void
     */
    public function progressForCsvStoreProcess(Request $request)
    {
        $batchId = $request->id ?? session()->get('lastBatchId');
        try {
            $jobBatch = JobBatch::query()
                    ->where('id', $batchId)
                    ->count();

            if ($jobBatch) {
                $response = JobBatch::query()
                    ->where('id', $batchId)
                    ->first();

                return  response()->json($response);
            }

        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
