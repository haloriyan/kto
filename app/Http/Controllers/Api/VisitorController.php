<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Models\Visitor;
use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\KmtmUser;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VisitorController extends Controller
{
    public static function register($props) {
        $token = Str::random(32);
        
        $saveData = Visitor::create([
            'name' => $props['name'],
            'email' => $props['email'],
            'password' => bcrypt("inikatasandi"),
            'is_active' => true,
            'token' => $token,
            'appointment_eligible' => 0,
        ]);

        return $saveData;
    }
    public function auth(Request $request) {
        $visitor = Visitor::where('token', $request->token)->first();
        return response()->json([
            'visitor' => $visitor,
        ]);
    }
    public function login(Request $request) {
        $email = $request->email;
        $query = Visitor::where('email', $request->email);
        $visitor = $query->first();
        $visitorProps = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($visitor == "") {
            $visitor = self::register($visitorProps);
        } else {
            $check = $visitor->name == $request->name && $visitor->email == $visitor->email;
            if ($check) {
                $token = Str::random(32);
                $query->update([
                    'token' => $token,
                ]);
                $visitor = $query->first();
            } else {
                $visitor = self::register($visitorProps);
            }
        }

        return response()->json([
            'status' => 200,
            'visitor' => $visitor
        ]);
    }
    public function kmtmRegister(Request $request) {
        $status = 200;
        $message = "";
        $lang = $request->lang;
        $answers = json_encode($request->answers);
        $check = KmtmUser::where([
            ['name', 'LIKE', '%'.$request->name.'%'],
            ['email', 'LIKE', '%'.$request->email.'%'],
        ])->first();

        if ($check != "") {
            $status = 405;
            $message = $lang == "en" ? 
                "You already registered to KMTM. Please wait for data verification and we will reach you soon" : 
                "Anda telah terdaftar sebagai peserta KMTM. Mohon menunggu verifikasi dan kami akan segera memberi tahu Anda";
        } else {
            $sellers = $request->interesting_sellers == null ? null : implode(",", $request->interesting_sellers);

            $saveData = KmtmUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'reference' => $request->reference,
                'join_type' => $request->joinType,
                'from_company' => $request->fromCompany,
                'line_of_business' => $request->lineOfBusiness,
                'company_established' => $request->companyEstablished,
                'eligible' => false,
                'has_notified' => false,
                'custom_field' => $answers,
                'interesting_sellers' => $sellers
            ]);
            $message = $lang == "en" ? 
                "Thank you for joining KMTM. Please wait for data verification and we will reach you soon." : 
                "Terima kasih telah bergabung pada KMTM. Mohon menunggu verifikasi dan kami akan segera memberi tahu Anda";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function listExhibitor() {
        $exhibitors = Exhibitor::orderBy('name', 'ASC')->get();

        return response()->json([
            'exhibitors' => $exhibitors
        ]);
    }
    public function seller() {
        $sellers = Seller::orderBy('name', 'ASC')->with('payloads')->get();
        
        return response()->json([
            'sellers' => $sellers
        ]);
    }
}
