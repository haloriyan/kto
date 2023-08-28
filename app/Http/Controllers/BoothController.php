<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoothController extends Controller
{
    public function store(Request $request) {
        $logo = $request->file('logo');
        $cover = $request->file('cover');
        $logoFileName = time()."_".$logo->getClientOriginalName();
        $coverFileName = time()."_".$cover->getClientOriginalName();

        $saveData = Booth::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logoFileName,
            'cover' => $coverFileName
        ]);

        $logo->storeAs('public/booth_logos', $logoFileName);
        $cover->storeAs('public/booth_covers', $coverFileName);

        return redirect()->route('admin.exhibitor')->with([
            'message' => "Berhasil menambahkan exhibitor"
        ]);
    }
    public function delete($id) {
        $data = Booth::where('id', $id);
        $booth = $data->first();

        $deleteData = $data->delete();
        Storage::delete('public/booth_logos/' . $booth->logo);
        Storage::delete('public/booth_covers/' . $booth->cover);

        return redirect()->route('admin.exhibitor')->with([
            'message' => "Berhasil menghapus exhibitor"
        ]);
    }
}
