<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\KmtmUser;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BuyerController extends Controller
{
    public static function me() {
        $myData = Auth::guard('buyer')->user();
        return $myData;
    }
    public function loginPage() {
        $message = Session::get('message');
        return view('buyer.login', [
            'message' => $message
        ]);
    }
    public function login(Request $request) {
        $buyer = KmtmUser::where([
            ['email', $request->email],
            ['name', 'LIKE', '%'.$request->name.'%'],
            ['eligible', 1]
        ])->first();

        if ($buyer == "") {
            return redirect()->route('kmtm.loginPage')->withErrors([
                "kmtm_login_error"
            ]);
        } else {
            $loggingIn = Auth::guard('buyer')->loginUsingId($buyer->id);
            return redirect()->route('kmtm.home');
        }
    }
    public function home() {
        $myData = self::me();

        $appointments = Appointment::where([
            ['buyer_id', $myData->id]
        ])->with(['seller.payloads', 'schedule'])->get();

        return view('buyer.home', [
            'myData' => $myData,
            'appointments' => $appointments
        ]);
    }
    public static function payload($data, $key) {
        $toReturn = null;
        $payloads = $data->payloads;
        foreach ($payloads as $payload) {
            if ($payload->type == $key) {
                $toReturn = $payload->value;
            }
        }
        return $toReturn;
    }
    public function makeAppointment(Request $request) {
        $myData = self::me();
        $sellers = [];
        $sellersRaw = Seller::orderBy('name', 'ASC')->with(['payloads'])->get();
        $appointments = Appointment::where('buyer_id', $myData->id)->get('id');

        if (($appointments->count() < 6 && $myData->join_type == "company") || $myData->join_type == "personal") {
            // 
        } else {
            return redirect()->route('kmtm.home');
        }

        foreach ($sellersRaw as $raw) {
            $check = Appointment::where([
                ['seller_id', $raw->id],
                ['buyer_id', $myData->id]
            ])->first();
            if ($check == "") {
                array_push($sellers, $raw);
            }
        }

        if ($request->exid != null && $request->schedule_id != null) {
            $check = Appointment::where([
                ['seller_id', $request->exid],
                ['schedule_id', $request->schedule_id]
            ])->get('id');
            
            if ($check->count() == 0) {
                $saveAppointment = Appointment::create([
                    'seller_id' => $request->exid,
                    'schedule_id' => $request->schedule_id,
                    'buyer_id' => $myData->id,
                ]);

                return redirect()->route('kmtm.home');
            } else {
                return redirect()->route('visitor.makeAppointment', ['exid' => $request->exid])->withErrors([
                    'Maaf, jadwal yang Anda inginkan telah dipilih orang lain. Mohon pilih jadwal lainnya'
                ]);
            }
        }

        if ($request->exid == null) {
            if (count($sellers) > 0) {
                $seller = $sellers[0];
            } else {
                return redirect()->back();
            }
        } else {
            $seller = Seller::where('id', $request->exid)->first();
        }

        return view('buyer.makeAppointment', [
            'sellers' => $sellers,
            'seller' => $seller,
            'myData' => $myData,
            'request' => $request,
        ]);
    }
    public function cancelAppointment(Request $request) {
        $data = Appointment::where('id', $request->id);
        $appointment = $data->first();
        $query = KmtmUser::where('id', $appointment->buyer_id)->increment('cancellation_count');
        $data->delete();

        return redirect()->route('kmtm.home')->with([
            'message' => ""
        ]);
    }
}
