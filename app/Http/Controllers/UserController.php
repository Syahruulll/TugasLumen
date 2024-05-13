<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiFormatter;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        //middleware:membatasi,nama" function yang hanya bisa diakses setelah login
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        try {
            $data = User::all()->toArray();

            return ApiFormatter::sendResponse( 200,'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse( 400,'bad request', $err->getMessage());
        }
    }

    public function store(Request $request)
{
    try { 
        $this->validate($request, [
            'username' => 'required|min:4|unique:users,username,',
            'email' => 'required|unique:users,email,',
            'password' => 'required',
            'role' => 'required',
        ]);
        
        // dd($request->all());  

        $userd = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($userd) {
            return ApiFormatter::sendResponse(200, 'success', $userd); 
        } else {
            return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data User! Silahkan coba lagi.');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}

public function  show($id)
{
    try {
        $data = User::where('id',$id)->first(); 
        return ApiFormatter::sendResponse(200, 'success', $data);

    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());
    }
}

public function update(Request $request, $id)
    {
        try {
            $this->validate($request,[
                'username' => 'required|min:4|unique:users,username,'. $id,
                'email' => 'required|unique:users,email,' . $id ,
                'password' => 'required',
                'role' => 'required',
            ]);
            $checkProsess = User::where('id', $id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($checkProsess) {
                $data = User::where('id',$id)->first(); 
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }
// public function update(Request $request, $id)
// {
//     try {
//         // Validasi request jika diperlukan
//         $this->validate($request, [
//             'username' => 'sometimes|required|min:4|unique:users,username,'. $id,
//             'email' => 'sometimes|required|unique:users,email,'.$id,
//             'password' => 'sometimes|required',
//             'role' => 'sometimes|required',
//         ]);

//         // Dapatkan data pengguna yang ada
//         $user = User::findOrFail($id);

//         // Perbarui atribut jika diminta dalam request dan valid
//         if ($request->filled('username')) {
//             $user->username = $request->username;
//         } elseif ($request->filled('email')) {
//             $user->email = $request->email;
//         } elseif ($request->filled('password')) {
//             $user->password = Hash::make($request->password);
//         } elseif ($request->filled('role')) {
//             $user->role = $request->role;
//         }

//         // Simpan perubahan jika ada
//         $user->save();

//         // Dapatkan data pengguna terbaru
//         $data = User::find($id);

//         return ApiFormatter::sendResponse(200, 'success', $data);
//     } catch (\Exception $err) {
//         return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
//     }
// }



    public function destroy($id)
    {
        try {
            $checkProsess = User::where('id', $id)->delete();

            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'success', ' Berhasil hapus data user!!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }

    public function trash()
    {
        try {
            //onlytrashed : memanggil data sampah yg sudah dihapus/deleted_At nya terisi
            $data = User::onlyTrashed()->get();
            return ApiFormatter::sendResponse(200, 'success', $data);

        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }
    public function restore($id)
    {
        try {
            $checkRestore = User::onlyTrashed()->where('id', $id)->restore();

            if ($checkRestore) {
                $data = User::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }

    public function permanentDelete($id)
    {
        try {
            $checkpermanentDelete = User::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkpermanentDelete) {
                return ApiFormatter::sendResponse(200, 'success', ' Berhasil menghapus permanent data user!!');

            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request',$err->getMessage());

        }
    }
}
