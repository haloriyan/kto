<?php

namespace App\Http\Controllers;

use App\Models\KmteUser;
use App\Models\KmtmUser;
use Illuminate\Http\Request;

class KmteController extends Controller
{
    public function registerPage() {
        return view('kmte.register');
    }
    public function register(Request $request) {
        $check = KmteUser::where([
            ['name', 'LIKE', '%'.$request->name.'%'],
            ['email', 'LIKE', '%'.$request->email.'%']
        ])->get('id');

        $eligible = $request->eligible == "false" ? 0 : 1;

        if ($check->count() == 0) {
            $saveData = KmteUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'eligible' => $eligible,
                'referer' => $request->referer,
            ]);
        } else {
            $saveData = $check[0];
        }

        return redirect()->route('kmte.registerDone', ['id' => $saveData->id]);
    }
    public function registerDone(Request $request) {
        $user = KmteUser::where('id', $request->id)->first();
        return view('kmte.registerDone', [
            'user' => $user
        ]);
    }
}
