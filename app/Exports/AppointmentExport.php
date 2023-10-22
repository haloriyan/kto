<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AppointmentExport implements FromView, ShouldAutoSize
{
    public $appointments;
    public $sellers;
    public $schedules;
    public $times;

    public function __construct($props)
    {
        $this->sellers = $props['sellers'];
        $this->schedules = $props['schedules'];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('spreadsheets.appointment', [
            'sellers' => $this->sellers,
            'schedules' => $this->schedules,
        ]);
    }
}
