<?php

namespace App\Http\Controllers;
use App\Helpers\ApiFormatter;
use App\Models\Stuff;
use App\Models\InboundStuff;
use App\Models\StuffStock;
use Illuminate\Http\Request;
use App\Models\Lending;

class StuffController extends Controller
{


    public function __construct()
    {
        //middleware:membatasi,nama" function yang hanya bisa diakses setelah login
        $this->middleware('auth:api');
    }
    public function index()
    {
        try {
            $data = Stuff::all()->toArray();

            return ApiFormatter::sendResponse( 200,'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse( 400,'bad request', $err->getMessage());
        }
    }



    public function store(Request $request)
    {
       
        try {
            //validasi
            // 'nama_column => 'validasi'
            $this->validate($request,[
                'name' => 'required',
                'category' => 'required',
            ]);

            $prosesData = Stuff::create([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'success', $prosesData);
            } else{
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data stuff! silahkan coba lagi.');

            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse( 400,'bad request', $err->getMessage());

        }
    }


    public function  show($id)
    {
        try {
            $data = Stuff::where('id',$id)->first(); 
            //first():kalo gaada, tetep success data kosong,firstorFail():klao gaada,error,Find():mencari berdasarkan pk,where():mencari lebih spesifik
            return ApiFormatter::sendResponse(200, 'success', $data);

        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $this->validate($request,[
                'name' => 'required',
                'category' => 'required',
            ]);
            $checkProsess = Stuff::where('id', $id)->update([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            if ($checkProsess) {
                // ::create([]) : menghasilkan data yang ditambah
                // ::update ([]) : menghasilkan boolean,jadi buat ambil data terbaru dicari lagi
                $data = Stuff::where('id',$id)->first(); 
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }


    public function destroy($id)
{
    try {
        // Periksa apakah stuff memiliki data inbound, stuff stock, atau lending terkait
        $hasRelatedData = InboundStuff::where('stuff_id', $id)->exists() ||
                          StuffStock::where('stuff_id', $id)->exists() ||
                          Lending::where('stuff_id', $id)->exists();

        // Jika terdapat data terkait, kembalikan respons yang sesuai
        if ($hasRelatedData) {
            return ApiFormatter::sendResponse(400, 'Bad request', 'Stuff memiliki data terkait dan tidak dapat dihapus.');
        }

        // Lakukan penghapusan jika tidak terdapat data terkait
        $checkProcess = Stuff::where('id', $id)->delete();

        if ($checkProcess) {
            return ApiFormatter::sendResponse(200, 'Success', 'Berhasil hapus data stuff.');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'Bad request', $err->getMessage());
    }
}


    public function trash()
    {
        try {
            //onlytrashed : memanggil data sampah yg sudah dihapus/deleted_At nya terisi
            $data = Stuff::onlyTrashed()->get();
            return ApiFormatter::sendResponse(200, 'success', $data);

        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }

    public function restore($id)
    {
        try {
            $checkRestore = Stuff::onlyTrashed()->where('id', $id)->restore();

            if ($checkRestore) {
                $data = Stuff::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }

    public function permanentDelete($id)
    {
        try {
            $checkpermanentDelete = Stuff::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkpermanentDelete) {
                return ApiFormatter::sendResponse(200, 'success', ' Berhasil menghapus permanent data stuff!!');

            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }

}
