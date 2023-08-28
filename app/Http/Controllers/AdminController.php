<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Exhibitor;
use App\Models\Schedule;
use App\Models\Visitor;
use App\Models\VisitorScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public static function me() {
        $myData = Auth::guard('admin')->user();
        if ($myData != "") {
            $names = explode(" ", $myData->name);
            $myData->initial = $names[0][0];
            if (count($names) > 1) {
                $myData->initial .= $names[count($names) - 1][0];
            }
        }
        return $myData;
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            $params = [];
            if ($request->r != "") {
                $params['r'] = $request->r;
            }
            return redirect()->route('admin.loginPage', $params)->withErrors(['Kombinasi email dan password tidak tepat']);
        }

        if ($request->r != "") {
            $r = base64_decode($request->r);
            return redirect($r);
        }

        return redirect()->route('admin.dashboard');
    }
    public function loginPage(Request $request) {
        $message = Session::get('message');
        
        return view('admin.login', [
            'message' => $message,
            'request' => $request
        ]);
    }
    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();

        return redirect()->route('admin.loginPage')->with([
            'message' => "Berhasil logout"
        ]);
    }
    public function dashboard() {
        $myData = self::me();
        $totalVisitors = Visitor::get('id')->count();
        $visitors = Visitor::orderBy('created_at', 'DESC')->take(5)->get();
        $appointments = Appointment::orderBy('created_at', 'DESC')->get('id');

        return view('admin.dashboard', [
            'myData' => $myData,
            'visitors' => $visitors,
            'appointments' => $appointments,
            'total_visitor' => $totalVisitors
        ]);
    }
    public function exhibitor() {
        $myData = self::me();
        $exhibitors = Exhibitor::orderBy('name', 'ASC')->get();

        return view('admin.exhibitor', [
            'myData' => $myData,
            'exhibitors' => $exhibitors,
        ]);
    }
    public function visitor(Request $request) {
        $myData = self::me();
        $filter = [];
        $totalVisitors = Visitor::get('id')->count();
        if ($request->q != "") {
            array_push($filter, ['name', 'LIKE', '%'.$request->q.'%']);
        }
        $visitors = Visitor::where($filter)->orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.visitor', [
            'myData' => $myData,
            'visitors' => $visitors,
            'request' => $request,
            'total_visitor' => $totalVisitors,
        ]);
    }
    public function visitting(Request $request) {
        $myData = self::me();

        $query = VisitorScan::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhereHas('exhibitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }

        $visits = $query
        ->with(['exhibitor', 'visitor'])
        ->paginate(25);

        return view('admin.visitting', [
            'myData' => $myData,
            'request' => $request,
            'visits' => $visits,
        ]);
    }
    public function schedule() {
        $myData = self::me();
        $schedules = Schedule::orderBy('date', 'ASC')->get();
        $message = Session::get('message');

        return view('admin.schedule', [
            'myData' => $myData,
            'message' => $message,
            'schedules' => $schedules,
        ]);
    }
    public function appointment(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $filter = [];

        $query = Appointment::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('exhibitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $appointments = $query->with(['schedule', 'exhibitor', 'visitor'])->paginate(25);
        $total_data = Appointment::orderBy('created_at', 'DESC')->get('id');

        return view('admin.appointment', [
            'myData' => $myData,
            'message' => $message,
            'appointments' => $appointments,
            'request' => $request,
            'total_data' => $total_data,
        ]);
    }
}
