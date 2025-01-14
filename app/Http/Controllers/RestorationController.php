<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Models\StuffStock;
use App\Models\Restoration;
use App\Models\Lending;

class RestorationController extends Controller
{
    public function __construct()
{
    //middleware:membatasi,nama" function yang hanya bisa diakses setelah login
    $this->middleware('auth:api');
}
    
public function store(Request $request, $lending_id)
 
{

    try {

        $this->validate($request, [

            'date_time' => 'required',
            'total_good_stuff' => 'required',
            'total_defec_stuff' => 'required',

        ]);

        $lending = Lending::where('id', $lending_id)->first();

        $totalStuffRestoration = (int)$request->total_good_stuff + (int)$request->total_defec_stuff;

        if ((int)$totalStuffRestoration > (int)$lending['total_stuff']) {

            return ApiFormatter::sendResponse (400, 'bad request', 'Total barang kembali lebih banyak dari barang dipinjam!');

        } else {

            $restoration = Restoration::updateOrCreate([
                'lending_id' => $lending_id
            ],[
                'date_time' => $request->date_time,
                'total_good_stuff' => $request->total_good_stuff,
                'total_defec_stuff' => $request->total_defec_stuff,
                'user_id' => auth()->user()->id,

            ]);

            $stuffStock = StuffStock::where('stuff_id', $lending['stuff_id'])->first();
            $totalAvailableStock = (int)$stuffStock['total_available'] + (int)$request->total_good_stuff;
            $totalDefecStock = (int)$stuffStock['total_defec'] + (int)$request->total_defec_stuff;
            $stuffStock->update([
                'total_available' => $totalAvailableStock,
                'total_defec' => $totalDefecStock,
            ]);

            $lendingRestoration = Lending::where('id', $lending_id)->with('user','restoration','restoration.user','stuff',
            'stuff.stuffStock')->first();
            return ApiFormatter::sendResponse (200, 'success', 'Pengembalian barang berhasil!');
        }

    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }

}

}
