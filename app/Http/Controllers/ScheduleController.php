<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function get(Request $request) {
        $exhibitorID = $request->exhibitor_id;
        $schedulesRaw = Schedule::orderBy('date', 'ASC')->get();
        $schedules = [];

        foreach ($schedulesRaw as $i => $schedule) {
            $appointments = Appointment::where([
                ['exhibitor_id', $exhibitorID],
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
    public function add(Request $request) {
        $validated = $request->validate([
            'time' => 'required',
            'date' => 'required',
        ]);
        $time = $request->time . ":00";
        $date = $request->date . " " . $time;
        
        $saveData = Schedule::create([
            'date' => $date, 
        ]);

        return redirect()->route('admin.schedule')->with([
            'message' => "Berhasil menambahkan jadwal"
        ]);
    }
    public function delete(Request $request) {
        $data = Schedule::where('id', $request->id);
        $data->delete();

        return redirect()->route('admin.schedule')->with([
            'message' => "Berhasil menghapus jadwal"
        ]);
    }
    public function edit(Request $request) {
        $data = Schedule::where('id', $request->id);
        $time = $request->time . ":00";
        $date = $request->date . " " . $time;
        
        $data->update([
            'date' => $date,
        ]);

        return redirect()->route('admin.schedule')->with([
            'message' => "Berhasil mengubah jadwal"
        ]);
    }
}
