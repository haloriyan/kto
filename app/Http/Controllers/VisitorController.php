<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Claim;
use App\Models\ExclusiveClaim;
use App\Models\Exhibitor;
use App\Models\Scan;
use App\Models\Seller;
use App\Models\TechnoClaim;
use Session;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        return $request->password;
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
        $myData = self::me();
        $loggingOut = Auth::guard('visitor')->logout();
        $removeToken = Visitor::where('id', $myData->id)->update(['token' => null]);
        return view('visitor.logout');
        // return redirect()->route('visitor.loginPage');
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
        $seller = Seller::where('unique_id', $uniqueID)->first();
        $myData = self::me();

        $check = Scan::where([
            ['seller_id', $seller->id],
            ['visitor_id', $myData->id]
        ])->first();

        if ($check == "") {
            $saveData = Scan::create([
                'seller_id' => $seller->id,
                'visitor_id' => $myData->id,
            ]);
        }

        return view('visitor.doneScan', [
            'seller' => $seller,
        ]);
    }
    public function scanningBooth(Request $request) {
        // 
    }
    public function history() {
        $myData = self::me();
        $myData->exclusive_claim = ExclusiveClaim::where('visitor_id', $myData->id)->first();
        $myData->mystery_claim = Claim::where('visitor_id', $myData->id)->first();
        $myData->techno_area = TechnoClaim::where('visitor_id', $myData->id)->first();
        $message = Session::get('message');

        $histories = Scan::where('visitor_id', $myData->id)
        ->with('seller.payloads')
        ->get();

        return view('visitor.history', [
            'histories' => $histories,
            'myData' => $myData,
            'message' => $message,
        ]);
    }
    public function claimExclusiveGift() {
        $myData = self::me();
        $visits = Scan::where('visitor_id', $myData->id)->get();

        if ($visits->count() >= 1) {
            $claim = ExclusiveClaim::create([
                'visitor_id' => $myData->id,
                'is_accepted' => false,
            ]);
        } else {
            return redirect()->route('visitor.home')->withErrors([
                'You cannot claim exclusive gift yet'
            ]);
        }

        return redirect()->route('visitor.home')->with([
            'message' => "Successfully submitted your exclusive gift claim. Please go to the KTO's booth to collect your prize"
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
        $visits = Scan::where('visitor_id', $myData->id)->get();

        if ($visits->count() >= env('MIN_TO_CLAIM')) {
            $claim = Claim::create([
                'visitor_id' => $myData->id,
                'is_accepted' => false,
            ]);
        } else {
            return redirect()->route('visitor.home')->withErrors([
                'You cannot claim mystery gift yet'
            ]);
        }

        return redirect()->route('visitor.home')->with([
            'message' => "Successfully submitted your mystery gift claim. Please go to the KTO's booth to collect your prize"
        ]);
    }

    public function appointmentEligible($id) {
        $data = Visitor::where('id', $id);
        $visitor = $data->first();

        $data->update([
            'appointment_eligible' => !$visitor->appointment_eligible
        ]);

        return redirect()->back();
    }
    public function switchLang($lang) {
        session([
            'user_lang' => $lang,
            'expires' => now()->addHours(1234)
        ]);
        Artisan::call('view:clear');

        return redirect()->back();
    }
}
