<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function get(Request $request) {
        $sellerID = $request->seller_id;
        $schedulesRaw = Schedule::orderBy('date', 'ASC')->get();
        $schedules = [];

        foreach ($schedulesRaw as $i => $schedule) {
            $appointments = Appointment::where([
                ['seller_id', $sellerID],
                ['schedule_id', $schedule->id]
            ])->get('id');

            if ($appointments->count() == 0) {
                // array_splice($schedules, $i, 1);
                array_push($schedules, $schedule);
            }
        }

        return response()->json([
            'schedules' => $schedules
        ]);
    }
}
