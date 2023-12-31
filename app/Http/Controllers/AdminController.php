<?php

namespace App\Http\Controllers;

use Str;
use App\Exports\AppointmentExport;
use App\Exports\ExclusiveClaimExport;
use App\Exports\KmteUserExport;
use App\Exports\KMTMUser as ExportsKMTMUser;
use App\Exports\KMTMUserB2C;
use App\Exports\MysteryClaimExport;
use App\Exports\SellerVisittingExport;
use App\Exports\TechnoClaimExport;
use App\Exports\VisitorExport;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Claim;
use App\Models\ExclusiveClaim;
use App\Models\Exhibitor;
use App\Models\KmteUser;
use App\Models\KmtmUser;
use App\Models\Scan;
use App\Models\Schedule;
use App\Models\Seller;
use App\Models\TechnoClaim;
use App\Models\Visitor;
use App\Models\VisitorScan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function seller() {
        $myData = self::me();
        $message = Session::get('message');
        $sellers = Seller::orderBy('name', 'ASC')->with(['payloads'])->get();

        return view('admin.seller', [
            'myData' => $myData,
            'message' => $message,
            'sellers' => $sellers
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
    public function visitorExport() {
        $now = Carbon::now();
        $visitors = Visitor::orderBy('created_at', 'DESC')->with(['visits.seller'])->get();
        $filename = "KMTE Visitors - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';

        foreach ($visitors as $i => $visitor) {
            $sellerVisits = [];
            foreach ($visitor->visits as $visit) {
                array_push($sellerVisits, $visit->seller->name);
            }
            $visitors[$i]['seller_visits'] = $sellerVisits;
        }

        return Excel::download(new VisitorExport([
            'visitors' => $visitors
        ]), $filename);
    }
    public function visitorDetail($id, Request $request) {
        $myData = self::me();
        $visitor = Visitor::where('id', $id)
        ->with('visits.seller')
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

        $query = Scan::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhereHas('seller', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }

        $visits = $query
        ->with(['seller.payloads', 'visitor'])
        ->paginate(25);
        $visits->appends($request->query());

        return view('admin.visitting', [
            'myData' => $myData,
            'request' => $request,
            'visits' => $visits,
        ]);
    }
    public function visittingExport($sellerID) {
        $now = Carbon::now();
        $seller = Seller::where('id', $sellerID)->first();
        $filename = $seller->name." Scan Report - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $scans = Scan::where('seller_id', $sellerID)->with(['visitor'])->get();

        return Excel::download(new SellerVisittingExport([
            'scans' => $scans,
            'seller' => $seller
        ]), $filename);
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
            $query = $query->whereHas('seller', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            })->orWhereHas('buyer', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $appointments = $query->with(['schedule', 'seller', 'buyer'])->paginate(25);
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
    public function appointmentExport() {
        $now = Carbon::now();
        $filename = "KMTM Appointments - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $schedulesRaw = Schedule::orderBy('date', 'ASC')->get();
        $schedules = [];
        $sellers = Seller::orderBy('name', 'ASC')->get();

        foreach ($schedulesRaw as $s) {
            array_push($schedules, Carbon::parse($s->date)->format('H:i'));
        }

        foreach ($sellers as $i => $seller) {
            $appointments = [];
            foreach ($schedulesRaw as $schedule) {
                $app = Appointment::where([
                    ['seller_id', $seller->id],
                    ['schedule_id', $schedule->id]
                ])->with(['buyer', 'schedule'])->first();

                if ($app != null && $app->buyer_id != null && $app->schedule->date == $schedule->date) {
                    array_push($appointments, $app);
                } else {
                    array_push($appointments, null);
                }
            }
            $sellers[$i]['appointments'] = $appointments;
        }

        return Excel::download(new AppointmentExport([
            'schedules' => $schedules,
            'schedulesRaw' => $schedulesRaw,
            'sellers' => $sellers
        ]), $filename);
    }
    public function kmteUser(Request $request) {
        $myData = self::me();
        $filter = [];
        if ($request->q != "") {
            array_push($filter, ['name', 'LIKE', '%'.$request->q.'%']);
        }
        
        $users = KmteUser::where($filter)->orderBy('created_at', 'DESC')->paginate(25);
        $total = KmteUser::get('id')->count();

        return view('admin.kmte_user', [
            'myData' => $myData,
            'request' => $request,
            'users' => $users,
            'total' => $total,
        ]);
    }
    public function kmteUserExport() {
        $now = Carbon::now();
        $users = KmteUser::orderBy('created_at', 'DESC')->get();
        $filename = "KMTE User - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';

        return Excel::download(new KmteUserExport([
            'users' => $users
        ]), $filename);
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
    public function kmtmUserExport(Request $request) {
        $now = Carbon::now();
        
        if ($request->type == "b2b") {
            $query = KmtmUser::where('join_type', 'company');
        } else {
            $query = KmtmUser::where('join_type', 'personal');
        }

        $users = $query->orderBy('created_at', 'DESC')->get();
        $customFieldColumns = [];

        foreach ($users as $user) {
            if ($user->custom_field != null) {
                $customFieldColumns = $user->custom_field;
            }
        }
        $customFieldColumns = json_decode($customFieldColumns, false);
        
        if ($request->type == "b2b") {
            $filename = "KMTM User (B2B) - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
            return Excel::download(new ExportsKMTMUser([
                'users' => $users,
                'field_columns' => $customFieldColumns
            ]), $filename);
        } else {
            $filename = "KMTM User (B2C) - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
            return Excel::download(new KMTMUserB2C([
                'users' => $users,
                'field_columns' => $customFieldColumns
            ]), $filename);
        }
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
        $claims = $query->with('visitor.visits.seller')->paginate(25);
        $claims->appends($request->query());

        return view('admin.claim', [
            'myData' => $myData,
            'message' => $message,
            'request' => $request,
            'claims' => $claims,
        ]);
    }
    public function claimExport() {
        $now = Carbon::now();
        $filename = "Mystery Gift Claim Report - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $claims = Claim::orderBy('created_at', 'ASC')->with('visitor')->get();

        return Excel::download(new MysteryClaimExport([
            'claims' => $claims,
        ]), $filename);
    }
    public function exclusiveClaim(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $query = ExclusiveClaim::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $claims = $query->with('visitor.visits.seller')->paginate(25);
        $claims->appends($request->query());
        
        return view('admin.exclusiveClaim', [
            'myData' => $myData,
            'request' => $request,
            'message' => $message,
            'claims' => $claims,
        ]);
    }
    public function exclusiveClaimExport() {
        $now = Carbon::now();
        $filename = "Exclusive Gift Claim Report - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $claims = ExclusiveClaim::orderBy('created_at', 'ASC')->with('visitor')->get();

        return Excel::download(new ExclusiveClaimExport([
            'claims' => $claims,
        ]), $filename);
    }
    public function technoClaim(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $query = TechnoClaim::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->whereHas('visitor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->q.'%');
            });
        }
        $claims = $query->with('visitor.visits.seller')->paginate(25);
        $claims->appends($request->query());

        return view('admin.technoClaim', [
            'myData' => $myData,
            'request' => $request,
            'message' => $message,
            'claims' => $claims,
        ]);
    }
    public function technoClaimExport() {
        $now = Carbon::now();
        $filename = "Techno Claim Report - Exported on " . $now->format('d M Y_H:i:s') . '.xlsx';
        $claims = Claim::orderBy('created_at', 'ASC')->with('visitor')->get();

        return Excel::download(new TechnoClaimExport([
            'claims' => $claims,
        ]), $filename);
    }
    public function acceptTechnoClaim($id) {
        $data = TechnoClaim::where('id', $id);
        $claim = $data->with('visitor')->first();

        $data->update([
            'is_accepted' => true,
        ]);

        return redirect()->route('admin.technoGift.claim')->with([
            'message' => "Klaim kupon berhasil disetujui"
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
    public function acceptExclusiveClaim($id) {
        $data = ExclusiveClaim::where('id', $id);
        $claim = $data->with('visitor')->first();

        $data->update([
            'is_accepted' => true,
        ]);

        return redirect()->route('admin.exclusiveGift.claim')->with([
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
    public function changeEnv($key, $newValue, $delim = '') {
        $path = base_path('.env');
        $oldValue = env($key);

        if ($oldValue == $newValue) return;
        
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key.'='.$delim.$oldValue.$delim, 
                $key.'='.$delim.$newValue.$delim,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                $key.'="'.$delim.$oldValue.$delim.'"', 
                $key.'="'.$delim.$newValue.$delim.'"',
                file_get_contents($path)
            ));
        }
    }
    public function updateSettings(Request $request) {
        $toUpdate = ['max_distance', 'min_to_claim', 'latitude', 'longitude'];

        foreach ($toUpdate as $key) {
            $this->changeEnv(strtoupper($key), $request->{$key});
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
    public function migrateKmtmBuyer() {
        $buyers = KmtmUser::all();
        foreach ($buyers as $buyer) {
            $query = Visitor::where([
                ['name', $buyer->name],
                ['email', $buyer->email],
            ]);
            $visitor = $query->first();

            if ($visitor == "") {
                $saveAsBuyer = Visitor::create([
                    'name' => $buyer->name,
                    'email' => $buyer->email,
                    'password' => bcrypt('inikatasandi'),
                    'is_active' => 1,
                    'appointment_eligible' => 0,
                    'token' => null,
                    'is_kmtm_buyer' => true,
                ]);
            } else {
                $query->update([
                    'is_kmtm_buyer' => true,
                ]);
            }
        }

        return $buyers;
    }
    public function regenerateSellerQR() {
        $sellers = Seller::all();
        foreach ($sellers as $seller) {
            $code = Str::random(32);
            Seller::where('id', $seller->id)->update([
                'unique_id' => $code,
            ]);
        }
        return "ok";
    }
}
