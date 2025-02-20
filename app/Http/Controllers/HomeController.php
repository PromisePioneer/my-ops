<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function summary(Request $request): JsonResponse
    {
        $roles = Role::with('department')->whereHas('department', function ($query) {
            $query->where('name', 'Operational');
        })->get();

        $roleAllowed = [];

//        foreach ($roles as $role) {
//            $roleAllowed[] = $role->name;
//        }

//        if ($request->user()->hasAnyRole($roleAllowed)) {
        $totalEmp = User::withoutRole('Super Admin')->where('active', true)->count();
        $totalBranch = Branch::whereNull('parent_id')->count();
        return response()->json([
            'totalEmp' => $totalEmp,
            'totalBranch' => $totalBranch,
        ]);
    }


    public function index(): View
    {
        $penarikan = "2025-02-01	2025-01-04 00:00:00	2025-02-03	vdrbogor-3	1 Orang	2025-02-03 15:59:21	SURYANTO AGUSSARI DINATA	m01266954	082390925009	Jl.dr Wahidin purnama.perumahan graha asri";
        $penarikan = explode("\t", $penarikan);

        $data = [
            'tanggal_penarikan' => $penarikan[0],
            'tgl_daftar' => $penarikan[1],
            'tgl_aktif' => $penarikan[2],
            'ditarik_oleh' => $penarikan[3],
            'jumlah_penarik' => count(explode(',', $penarikan[3])) . ' Orang',
            'last_pay' => $penarikan[5],
            'nama_user' => $penarikan[6],
            'user_id' => $penarikan[7],
            'hp' => $penarikan[8],
            'alamat' => $penarikan[9]
        ];

//        dd($data);
       return view('home');
    }
}
