<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KMTMUserB2C implements FromView, ShouldAutoSize
{
    public $users;
    public $columns;

    public function __construct($props)
    {
        $this->users = $props['users'];
        $this->columns = $props['field_columns'];
    }
    public function view(): View
    {
        return view('spreadsheets.kmtm_user_b2c', [
            'users' => $this->users,
            'columns' => $this->columns,
        ]);
    }
}
