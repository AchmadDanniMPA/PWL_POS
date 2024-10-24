<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    public function show(string $id)
    {
        $user = UserModel::with('level')->findOrFail($id);
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];
        
        $page = (object) [
            'title' => 'Detail user'
        ];
        $activeMenu = 'user';
        return view('user.show', compact('breadcrumb', 'page', 'user', 'activeMenu'));
    }
    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $level = LevelModel::all();
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit user'
        ];
        $activeMenu = 'user';
        return view('user.edit', compact('breadcrumb', 'page', 'user', 'level', 'activeMenu'));
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => "required|string|min:3|unique:m_user,username,$id,user_id",
            'nama' => 'required|string|max:100',
            'password' => 'nullable|string|min:5',
            'level_id' => 'required|integer'
        ]);
        $user = UserModel::findOrFail($id);
        $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id
        ]);
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }
    public function destroy(string $id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return redirect('/user')->with('error', "Data user tidak ditemukan");
        }
        try {
            $user->delete();
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dihapus karena terkait dengan data lain');
        }
    }
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user';
        $level = LevelModel::all();
        return view('user.index', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax', compact('level'));
    }
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|string|min:3|unique:m_user,username',
                'nama'      => 'required|string|max:100',
                'password'  => 'required|min:6'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validasi Gagal',
                    'msgField'  => $validator->errors(),
                ]);
            }
            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        return redirect('/user');
    }
    public function edit_ajax(string $id)
    {
        $user = UserModel::findOrFail($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.edit_ajax', compact('user', 'level'));
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            if ($request->filled('password')) {
                $request->merge(['password' => bcrypt($request->password)]);
            } else {
                $request->request->remove('password');
            }
            $user->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
            ]);
        }
        return redirect('/user');
    }
    public function confirm_ajax(string $id)
    {
        $user=UserModel::findOrFail($id);
        return view('user.confirm_ajax', compact('user'));
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/user');
    }
    public function import()
    {
        return view('user.import');
    }
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Username');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Level');
        $sheet->setCellValue('D1', 'Password');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'admin');
        $sheet->setCellValue('B2', 'Administrator');
        $sheet->setCellValue('C2', 'ADM');
        $sheet->setCellValue('D2', '');
        $sheet->setCellValue('A3', 'manager');
        $sheet->setCellValue('B3', 'Manager');
        $sheet->setCellValue('C3', 'MNG');
        $sheet->setCellValue('D3', '');
        $sheet->setCellValue('A4', 'staff');
        $sheet->setCellValue('B4', 'Staff Member');
        $sheet->setCellValue('C4', 'STF');
        $sheet->setCellValue('D4', '');
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Template User');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_user.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'username' => $value['A'],
                            'nama' => $value['B'],
                            'password' => bcrypt($value['C']),
                            'level_id' => $value['D'],
                        ];
                    }
                }
                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/user');
    }
    public function export_excel()
    {
        $users = UserModel::select('username', 'nama', 'level_id')
            ->orderBy('level_id')
            ->with('level')
            ->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Level');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($users as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->level->level_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data User');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $users = UserModel::select('username', 'nama', 'level_id')
            ->orderBy('level_id')
            ->with('level')
            ->get();
        $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);
        $pdf->setPaper('a4', 'potrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();
        return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
    }
    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Anda',
            'list'  => ['Home', 'Profile']
        ];
        $activeMenu = 'profile';
        return view('profil.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
    public function showChangePhotoForm()
    {
        return view('profil.change_photo');
    }
    public function showManageProfileForm()
    {
        return view('profil.manage');
    }
    public function updatePhoto(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_pfp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $file = $request->file('file_pfp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/pfp');
            $file->move($path, $filename);
            $user = UserModel::findOrFail(auth()->user()->user_id);
            $user->profile_picture = $filename;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Foto profil berhasil diupload',
                'newProfilePicturePath' => asset('images/pfp/' . $filename),
            ]);
        }
    }
    public function updateProfile(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|string|max:50|unique:m_user,username,' . auth()->user()->user_id . ',user_id',
                'nama' => 'required|string|max:100',
                'password' => 'nullable|string|min:6',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $user = UserModel::findOrFail(auth()->user()->user_id);
            $user->username = $request->username;
            $user->nama = $request->nama;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui',
            ]);
        }
    }
    public function show_ajax($id)
    {
        $user = UserModel::with('level')->find($id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }
        return view('user.show_ajax', compact('user'));
    }
}
