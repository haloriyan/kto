<?php

namespace App\Http\Controllers;

use Str;
use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExhibitorController extends Controller
{
    public function store(Request $request) {
        $icon = $request->file('icon');
        $iconFileName = $icon->getClientOriginalName();
        $cover = $request->file('cover');
        $coverFileName = $cover->getClientOriginalName();
        $slug = Str::slug($request->name);
        $uniqueID = Str::random(16);

        $saveData = Exhibitor::create([
            'name' => $request->name,
            'unique_id' => $uniqueID,
            'slug' => $slug,
            'description' => $request->description,
            'email' => $request->email,
            'website' => $request->website,
            'icon' => $iconFileName,
            'cover' => $coverFileName,
            'max_appointment' => 1,
        ]);

        $icon->storeAs('public/exhibitor_icons', $iconFileName);
        $cover->storeAs('public/exhibitor_covers', $coverFileName);

        return redirect()->route('admin.exhibitor')->with([
            'status' => 200,
            'message' => "Berhasil menambahkan exhibitor"
        ]);
    }
    public function delete(Request $request) {
        $data = Exhibitor::where('id', $request->id);
        $exhibitor = $data->first();

        $deleteData = $data->delete();
        if ($exhibitor->icon != null) {
            $deleteIcon = Storage::delete('public/exhibitor_icons/' . $exhibitor->icon);
        }
        if ($exhibitor->cover != null) {
            $deleteCover = Storage::delete('public/exhibitor_covers/' . $exhibitor->cover);
        }

        return redirect()->route('admin.exhibitor')->with([
            'status' => 200,
            'message' => "Berhasil menghapus exhibitor"
        ]);
    }
    public function qr($slug) {
        $exhibitor = Exhibitor::where('slug', $slug)->first();
        $name = Str::slug($exhibitor->name) . "_qr.pdf";

        // generating qr
        $str = route('boothScan', $exhibitor->unique_id);
        return $str;
        $qrCode = QrCode::size(150)->generate($str);

        $pdf = Pdf::loadView('pdf.ExhibitorQR', [
            'exhibitor' => $exhibitor,
            'qrCode' => $qrCode,
        ]);

        // return $pdf->download($name);
        return $pdf->stream();
    }
}
