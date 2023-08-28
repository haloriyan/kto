<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
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
