<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Models\Visitor;
use App\Http\Controllers\Controller;
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
}
