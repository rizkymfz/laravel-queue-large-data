<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProcessEmployees implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employeeData;
    /**
     * Create a new job instance.
     */
    public function __construct($employeeData)
    {
        $this->employeeData = $employeeData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        foreach ($this->employeeData as $employeeData) {
            $employee = new Employee;
            $employee->emp_id   = mt_rand(100000, 999999);
            $employee->name     = $employeeData['First Name']. " " .$employeeData['Last Name'];
            $employee->email    = $employeeData['E Mail'];
            $employee->gender   = $employeeData['Gender'];
            $employee->save();
        }
        DB::commit();
    }
}
