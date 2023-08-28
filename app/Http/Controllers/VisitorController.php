<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Claim;
use App\Models\Exhibitor;
use Session;
use App\Models\Visitor;
use App\Models\VisitorScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public static function me() {
        $myData = Auth::guard('visitor')->user();
        return $myData;
    }
    public function loginPage(Request $request) {
        $message = Session::get('message');
        
        return view('visitor.login', [
            'message' => $message,
            'request' => $request,
        ]);
    }
    public function registerPage() {
        $message = Session::get('message');
        return view('visitor.register', [
            'message' => $message,
        ]);
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('visitor')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            $params = [];
            if ($request->r != "") {
                $params['r'] = $request->r;
            }
            return redirect()->route('visitor.loginPage', $params)->withErrors(['Kombinasi email dan password tidak tepat']);
        }

        if ($request->r != "") {
            $r = base64_decode($request->r);
            return redirect($r);
        }

        return redirect()->route('visitor.home');
    }
    public function register(Request $request) {
        $saveData = Visitor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active' => 1,
            'appointment_eligible' => 0,
        ]);

        return redirect()->route('visitor.loginPage')->with([
            'message' => "Berhasil daftar"
        ]);
    }
    public function logout() {
        $loggingOut = Auth::guard('visitor')->logout();
        return redirect()->route('visitor.loginPage');
    }
    public function auth($token, $redirectTo = NULL) {
        $visitor = Visitor::where('token', $token)->first();
        $redirectTo = base64_decode($redirectTo);
        if ($visitor != "") {
            Auth::guard('visitor')->loginUsingId($visitor->id);

            if ($redirectTo != null) {
                return redirect($redirectTo);
            } else {
                return redirect()->route('historyScan');
            }
        }
    }

    public function home() {
        $myData = self::me();
        return view('visitor.home', [
            'myData' => $myData
        ]);
    }
    public function boothScan($uniqueID) {
        $exhibitor = Exhibitor::where('unique_id', $uniqueID)->first();
        $myData = self::me();

        $check = VisitorScan::where([
            ['exhibitor_id', $exhibitor->id],
            ['visitor_id', $myData->id]
        ])->first();

        if ($check == "") {
            $saveData = VisitorScan::create([
                'exhibitor_id' => $exhibitor->id,
                'visitor_id' => $myData->id,
            ]);
        }

        return view('visitor.doneScan', [
            'exhibitor' => $exhibitor,
        ]);
    }
    public function scanningBooth(Request $request) {
        // 
    }
    public function history() {
        $myData = self::me();
        $message = Session::get('message');

        $histories = VisitorScan::where('visitor_id', $myData->id)
        ->with('exhibitor')
        ->get();

        return view('visitor.history', [
            'histories' => $histories,
            'myData' => $myData,
            'message' => $message,
        ]);
    }
    public function makeAppointment(Request $request) {
        $exhibitors = [];
        $schedules = [];
        $exhibitor = null;

        if ($request->schedule_id != null && $request->exid != null) {
            $myData = self::me();
            $check = Appointment::where([
                ['exhibitor_id', $request->exid],
                ['schedule_id', $request->schedule_id]
            ])->get('id');
            
            if ($check->count() == 0) {
                $saveAppointment = Appointment::create([
                    'exhibitor_id' => $request->exid,
                    'schedule_id' => $request->schedule_id,
                    'visitor_id' => $myData->id,
                ]);

                return redirect()->route('historyScan');
            } else {
                return redirect()->route('visitor.makeAppointment', ['exid' => $request->exid])->withErrors([
                    'Maaf, jadwal yang Anda inginkan telah dipilih orang lain. Mohon pilih jadwal lainnya'
                ]);
            }
            return "asds";
        }

        if ($request->exid == null) {
            $exhibitors = Exhibitor::orderBy('name', 'ASC')->get();
        } else {
            $exhibitor = Exhibitor::where('id', $request->exid)->first();
        }

        return view('visitor.makeAppointment', [
            'exhibitors' => $exhibitors,
            'exhibitor' => $exhibitor,
            'request' => $request,
        ]);
    }

    public function claim(Request $request) {
        $myData = self::me();
        $visits = VisitorScan::where('visitor_id', $myData->id)->get();

        if ($visits->count() >= env('MIN_TO_CLAIM')) {
            $claim = Claim::create([
                'visitor_id' => $myData->id,
                'is_accepted' => false,
            ]);
        } else {
            return redirect()->route('visitor.home')->withErrors([
                'Anda belum bisa mengklaim hadiah'
            ]);
        }

        return redirect()->route('visitor.home')->with([
            'message' => "Berhasil mengajukan klaim hadiah. Silahkan ke booth penyelenggara untuk mengambil hadiah Anda"
        ]);
    }
}
