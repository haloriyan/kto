<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\SellerPayload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SellerController extends Controller
{
    public static function payload($payloads, $key) {
        $payloads = json_decode(json_encode($payloads), false);
        $toReturn = null;

        foreach ($payloads as $item) {
            if ($item->type == $key) {
                $toReturn = $item;
            }
        }

        return $toReturn->value;
    }
    public function savePayload($seller, $payloads) {
        $payloads = json_decode(json_encode($payloads), false);
        foreach ($payloads as $type => $value) {
            $savePayload = SellerPayload::create([
                'seller_id' => $seller->id,
                'type' => $type,
                'value' => $value,
            ]);
        }
    }
    public function create() {
        $myData = AdminController::me();
        return view('admin.seller.create', [
            'myData' => $myData
        ]);
    }
    public function edit($id) {
        $myData = AdminController::me();
        $seller = Seller::where('id', $id)->with('payloads')->first();
        $payloads = json_decode(json_encode($seller->payloads));

        return view('admin.seller.edit', [
            'myData' => $myData,
            'payloads' => $payloads,
            'seller' => $seller
        ]);
    }
    public function store(Request $request) {
        $logo = $request->file('cover');
        $logoFileName = $logo->getClientOriginalName();

        $saveData = Seller::create([
            'name' => $request->payloads['name_en'],
            'logo' => $logoFileName,
            'website' => $request->website
        ]);

        $logo->storeAs('public/seller_logos', $logoFileName);

        $this->savePayload($saveData, $request->payloads);

        return redirect()->route('admin.seller')->with([
            'message' => "Berhasil menambahkan seller"
        ]);
    }
    public function update(Request $request) {
        $query = Seller::where('id', $request->id);
        $seller = $query->first();

        $toUpdate = ['website' => $request->website];
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoFileName = $logo->getClientOriginalName();
            $toUpdate['logo'] = $logoFileName;
            $deleteOldLogo = Storage::delete('public/seller_logos/' . $seller->logo);
            $logo->storeAs('public/seller_logos', $logoFileName);
        }

        $query->update($toUpdate);
        $deletePayloads = SellerPayload::where('seller_id', $seller->id)->delete();
        $this->savePayload($seller, $request->payloads);
        
        return redirect()->route('admin.seller')->with([
            'message' => "Berhasil mengubah seller"
        ]);
    }
    public function delete(Request $request) {
        $query = Seller::where('id', $request->id);
        $seller = $query->first();

        $deleteData = $query->delete();
        $deleteLogo = Storage::delete('public/seller_logos/' . $seller->logo);

        return redirect()->route('admin.seller')->with([
            'message' => "Berhasil menghapus seller"
        ]);
    }
    public function updateDb() {
        $sellers = Seller::with('payloads')->get();
        
        foreach ($sellers as $seller) {
            $newName = "";
            foreach ($seller->payloads as $payload) {
                if ($payload->type == "name_en") {
                    $newName = $payload->value;
                }
            }
            $updateData = Seller::where('id', $seller->id)->update([
                'name' => $newName
            ]);
        }
    }
    public function qr($id) {
        $seller = Seller::where('id', $id)->with('payloads')->first();

        $str = route('boothScan', $seller->unique_id);
        $qrCode = QrCode::size(150)->generate($str);

        $pdf = Pdf::loadView('pdf.ExhibitorQR', [
            'seller' => $seller,
            'qrCode' => $qrCode,
        ]);

        return $pdf->stream();
    }
}
