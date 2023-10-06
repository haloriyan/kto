<?php

namespace App\Exports;

use App\Models\KmtmUser as ModelsKmtmUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KMTMUser implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $users;
    public $columns;

    public function __construct($props)
    {
        $this->users = $props['users'];
        $this->columns = $props['field_columns'];
    }
    public function view(): View
    {
        return view('spreadsheets.kmtm_user', [
            'users' => $this->users,
            'columns' => $this->columns,
        ]);
    }
}
