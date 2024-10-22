<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok Barang',
            'list' => ['Home', 'Stok Barang']
        ];
        $page = (object) [
            'title' => 'Daftar stok barang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'stok';
        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
    public function list(Request $request)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])
            ->select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah');
        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('supplier_name', function ($stok) {
                return $stok->supplier->supplier_nama;
            })
            ->addColumn('barang_name', function ($stok) {
                return $stok->barang->barang_nama;
            })
            ->addColumn('user_name', function ($stok) {
                return $stok->user->nama;
            })
            ->addColumn('action', function ($stok) {
                $btn = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create_ajax()
    {
        $suppliers = SupplierModel::all();
        $barang = BarangModel::all();
        return view('stok.create_ajax', compact('suppliers', 'barang'));
    }
    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_jumlah' => 'required|integer',
                'stok_tanggal' => 'required|date'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            StokModel::create([
                'supplier_id' => $request->supplier_id,
                'barang_id' => $request->barang_id,
                'user_id' => auth()->user()->user_id,
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah' => $request->stok_jumlah
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }
        return redirect('/stok');
    }
    public function edit_ajax($id)
    {
        $stok = StokModel::findOrFail($id);
        $suppliers = SupplierModel::all();
        $barang = BarangModel::all();
        return view('stok.edit_ajax', compact('stok', 'suppliers', 'barang'));
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_jumlah' => 'required|integer',
                'stok_tanggal' => 'required|date'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            $stok = StokModel::findOrFail($id);
            $stok->update([
                'supplier_id' => $request->supplier_id,
                'barang_id' => $request->barang_id,
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah' => $request->stok_jumlah
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil diupdate'
            ]);
        }
        return redirect('/stok');
    }
    public function show_ajax($id)
    {
        $stok = StokModel::with('supplier', 'barang', 'user')->find($id);
        return view('stok.show_ajax', compact('stok'));
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $stok = StokModel::findOrFail($id);
            try {
                $stok->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data stok gagal dihapus karena masih terkait dengan data lain.'
                ]);
            }
        }
        return redirect('/stok');
    }
    public function confirm_ajax($id)
    {
        $stok = StokModel::with('supplier', 'barang', 'user')->find($id);
        return view('stok.confirm_ajax', compact('stok'));
    }
    public function import_view()
    {
        return view('stok.import');
    }
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Supplier ID');
        $sheet->setCellValue('B1', 'Barang ID');
        $sheet->setCellValue('C1', 'Jumlah Stok');
        $sheet->setCellValue('D1', 'Tanggal Stok (yyyy-mm-dd hh:mm:ss)');
        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', '2');
        $sheet->setCellValue('C2', '100');
        $sheet->setCellValue('D2', '2024-01-01 10:00:00');
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_stok.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function import_excel(Request $request)
    {
        $rules = [
            'file_stok' => 'required|mimes:xlsx|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }
        $file = $request->file('file_stok');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $insertData = [];
        foreach ($rows as $key => $row) {
            if ($key > 1) {
                $insertData[] = [
                    'supplier_id' => $row['A'],
                    'barang_id' => $row['B'],
                    'stok_jumlah' => $row['C'],
                    'stok_tanggal' => $row['D'],
                    'user_id' => auth()->user()->user_id,
                ];
            }
        }
        StokModel::insertOrIgnore($insertData);
        return response()->json([
            'status' => true,
            'message' => 'Data stok berhasil diimport',
        ]);
    }
    public function export_excel()
    {
        $stok = StokModel::with(['supplier', 'barang'])->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Supplier');
        $sheet->setCellValue('C1', 'Barang');
        $sheet->setCellValue('D1', 'Jumlah');
        $sheet->setCellValue('E1', 'Tanggal');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $no = 1;
        $rowNum = 2;
        foreach ($stok as $item) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $item->supplier->supplier_nama);
            $sheet->setCellValue('C' . $rowNum, $item->barang->barang_nama);
            $sheet->setCellValue('D' . $rowNum, $item->stok_jumlah);
            $sheet->setCellValue('E' . $rowNum, $item->stok_tanggal);
            $rowNum++;
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'stok_data_' . date('Y-m-d_H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $stok = StokModel::with(['supplier', 'barang'])->get();
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        return $pdf->stream('stok_data.pdf');
    }
}
