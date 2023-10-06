<?php

namespace App\Http\Controllers;

use App\Exports\KMTMUser as ExportsKMTMUser;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Claim;
use App\Models\Exhibitor;
use App\Models\KmtmUser;
use App\Models\Schedule;
use App\Models\Visitor;
use App\Models\VisitorScan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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
        $visitors->appends($request->query());

        return view('admin.visitor', [
            'myData' => $myData,
            'visitors' => $visitors,
            'request' => $request,
            'total_visitor' => $totalVisitors,
        ]);
    }
    public function visitorDetail($id, Request $request) {
        $myData = self::me();
        $visitor = Visitor::where('id', $id)
        ->with('visits.exhibitor')
        ->first();
        
        $names = explode(" ", $visitor->name);
        $visitor->initial = $names[0][0];
        if (count($names) > 1) {
            $visitor->initial .= $names[count($names) - 1][0];
        }

        return view('admin.VisitorDetail', [
            'myData' => $myData,
            'visitor' => $visitor,
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
        $visits->appends($request->query());

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
        $appointments->appends($request->query());
        $total_data = Appointment::orderBy('created_at', 'DESC')->get('id');

        return view('admin.appointment', [
            'myData' => $myData,
            'message' => $message,
            'appointments' => $appointments,
            'request' => $request,
            'total_data' => $total_data,
        ]);
    }
    public function kmtmUser(Request $request) {
        $myData = self::me();
        $filter = [];
        $message = Session::get('message');

        if ($request->q != "") {
            array_push($filter, ['name', 'LIKE', '%'.$request->q.'%']);
        }

        $users = KmtmUser::where($filter)->paginate(25);
        $total = KmtmUser::orderBy('created_at', 'DESC')->get('id')->count();

        return view('admin.kmtm_user', [
            'myData' => $myData,
            'request' => $request,
            'message' => $message,
            'total' => $total,
            'users' => $users,
        ]);
    }
    public function kmtmEligible($id) {
        $data = KmtmUser::where('id', $id);
        $user = $data->first();

        $data->update(['eligible' => !$user->eligible]);

        return redirect()->route('admin.kmtmUser')->with([
            'message' => "Berhasil mengubah status eligible"
        ]);
    }
    public function kmtmDetail($id) {
        $myData = self::me();
        $user = KmtmUser::where('id', $id)->first();
        $names = explode(" ", $user->name);
        $user->initial = $names[0][0];
        if (count($names) > 1) {
            $user->initial .= $names[count($names) - 1][0];
        }

        return view('admin.kmtm_user_detail', [
            'user' => $user,
            'myData' => $myData,
        ]);
    }
    public function kmtmUserExport() {
        $now = Carbon::now();
        $filename = "KMTM User - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $users = KmtmUser::orderBy('created_at', 'DESC')->get();
        $customFieldColumns = [];

        foreach ($users as $user) {
            if ($user->custom_field != null) {
                $customFieldColumns = $user->custom_field;
            }
        }
        $customFieldColumns = json_decode($customFieldColumns, false);
        
        return Excel::download(new ExportsKMTMUser([
            'users' => $users,
            'field_columns' => $customFieldColumns
        ]), $filename);
    }
    public function claim(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $query = Claim::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $claims = $query->with('visitor.visits.exhibitor')->paginate(25);
        $claims->appends($request->query());

        return view('admin.claim', [
            'myData' => $myData,
            'message' => $message,
            'request' => $request,
            'claims' => $claims,
        ]);
    }
    public function acceptClaim($id) {
        $data = Claim::where('id', $id);
        $claim = $data->with('visitor')->first();

        $data->update([
            'is_accepted' => true,
        ]);

        return redirect()->route('admin.claim')->with([
            'message' => "Klaim hadiah berhasil disetujui"
        ]);
    }
    public function settings() {
        $myData = self::me();
        $message = Session::get('message');

        return view('admin.settings', [
            'myData' => $myData,
            'message' => $message,
        ]);
    }
    public function updateSettings(Request $request) {
        $toUpdate = ['max_distance', 'min_to_claim'];

        foreach ($toUpdate as $key) {
            $query = strtoupper($key)."=".$request->{$key};
            Config::set(strtoupper($key), $request->{$key});
        }

        return redirect()->route('admin.settings')->with([
            'message' => "Berhasil menyimpan perubahan pengaturan"
        ]);
    }

    public function admin() {
        $myData = self::me();
        $admins = Admin::all();
        $message = Session::get('message');

        return view('admin.admin', [
            'myData' => $myData,
            'admins' => $admins,
            'message' => $message,
        ]);
    }
    public function store(Request $request) {
        $saveData = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.admin')->with([
            'message' => "Berhasil menambahkan user administrator baru"
        ]);
    }
    public function delete(Request $request) {
        $data = Admin::where('id', $request->id);
        $admin = $data->first();

        $data->delete();

        return redirect()->route('admin.admin')->with([
            'message' => "Berhasil menghapus " . $admin->name,
        ]);
    }
    public function changePassword(Request $request) {
        $myData = self::me();
        $data = Admin::where('id', $request->id);
        $admin = $data->first();

        $data->update([
            'password' => bcrypt($request->password)
        ]);

        if ($admin->id == $myData->id) {
            $loggingOut = Auth::guard('admin')->logout();
            return redirect()->route('admin.loginPage')->with([
                'message' => "Berhasil mengubah password. Silahkan login dengan password baru Anda"
            ]);
        } else {
            return redirect()->route('admin.admin')->with([
                'message' => "Berhasil mengubah password untuk " . $admin->name,
            ]);
        }
    }
}
