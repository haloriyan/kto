<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitorExport implements FromView, ShouldAutoSize
{
    public $visitors;

    public function __construct($props)
    {
        $this->visitors = $props['visitors'];
    }
    public function view(): View
    {
        return view('spreadsheets.visitor', [
            'visitors' => $this->visitors,
        ]);
    }
}
