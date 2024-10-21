<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar barang',
            'list' => ['Home', 'barang']
        ];
        $page = (object)[
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'barang';
        $kategori = KategoriModel::all();
        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->with('kategori');
        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }
        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('action', function ($barang) {
                $btn  = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah barang',
            'list' => ['Home', 'barang', 'Tambah']
        ];
        $page = (object)[
            'title' => 'Tambah barang baru'
        ];
        $kategori = KategoriModel::all();
        $activeMenu = 'barang';
        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric'
        ]);
        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);
        return redirect('/barang')->with('success', 'Data barang berhasil ditambahkan');
    }
    public function show($id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail barang',
            'list' => ['Home', 'barang', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail barang'
        ];
        $activeMenu = 'barang';
        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }
    public function edit($id)
    {
        $barang = BarangModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit barang',
            'list' => ['Home', 'barang', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit barang'
        ];
        $activeMenu = 'barang';
        $kategori = KategoriModel::all();
        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,'.$id.',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric'
        ]);
        BarangModel::find($id)->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);
        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }
    public function destroy($id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }
        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    public function create_ajax()
    {
        $kategori = KategoriModel::all();
        return view('barang.create_ajax', compact('kategori'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            BarangModel::create([
                'kategori_id' => $request->kategori_id,
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
        return redirect('/barang');
    }
    public function edit_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategori = KategoriModel::all();
        return view('barang.edit_ajax', compact('barang', 'kategori'));
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            $barang = BarangModel::findOrFail($id);
            $barang->update([
                'kategori_id' => $request->kategori_id,
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil diupdate'
            ]);
        }
        return redirect('/barang');
    }
    public function confirm_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);
        return view('barang.confirm_ajax', compact('barang'));
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $barang = BarangModel::findOrFail($id);
            try {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data barang gagal dihapus karena masih terkait dengan data lain.'
                ]);
            }
        }
        return redirect('/barang');
    }
    public function import() 
    { 
        return view('barang.import'); 
    } 
    public function import_ajax(Request $request) 
    { 
        if ($request->ajax() || $request->wantsJson()) { 
            $rules = [ 
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ]; 
            $validator = Validator::make($request->all(), $rules); 
            if ($validator->fails()) { 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
            $file = $request->file('file_barang');
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
                            'kategori_id' => $value['A'], 
                            'barang_kode' => $value['B'], 
                            'barang_nama' => $value['C'], 
                            'harga_beli' => $value['D'], 
                            'harga_jual' => $value['E'], 
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
                if (count($insert) > 0) { 
                    BarangModel::insertOrIgnore($insert);
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
        return redirect('/barang'); 
    }
    public function export_excel()
    {
        $barang = BarangModel::with('kategori')->orderBy('kategori_id')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($barang as $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang_kode);
            $sheet->setCellValue('C' . $baris, $value->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga_beli);
            $sheet->setCellValue('E' . $baris, $value->harga_jual);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data Barang');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Barang_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
