<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SellerVisittingExport implements FromView, ShouldAutoSize
{
    public $scans;
    public $seller;

    public function __construct($props)
    {
        $this->scans = $props['scans'];
        $this->seller = $props['seller'];
    }
    public function view(): View
    {
        return view('spreadsheets.seller_visitting', [
            'scans' => $this->scans,
            'seller' => $this->seller,
        ]);
    }
}
