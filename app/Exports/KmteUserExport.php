<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KmteUserExport implements FromView, ShouldAutoSize
{
    public $users;

    public function __construct($props)
    {
        $this->users = $props['users'];
    }

    public function view(): View
    {
        return view('spreadsheets.kmte_user', [
            'users' => $this->users,
        ]);
    }
}
