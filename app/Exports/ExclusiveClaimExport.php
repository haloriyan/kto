<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExclusiveClaimExport implements FromView, ShouldAutoSize
{
    public $claims;

    public function __construct($props)
    {
        $this->claims = $props['claims'];
    }
    public function view(): View
    {
        return view('spreadsheets.exclusive_claim', [
            'claims' => $this->claims,
        ]);
    }
}
