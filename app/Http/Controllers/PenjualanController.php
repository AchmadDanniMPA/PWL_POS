<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index() {
        $breadcrumb = (object)[
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $activeMenu = 'penjualan';
        return view('penjualan.index', compact('breadcrumb', 'activeMenu'));
    }
    public function list(Request $request) {
        $penjualan = PenjualanModel::with(['user.level'])->select('penjualan_id', 'penjualan_kode', 'pembeli', 'user_id', 'penjualan_tanggal');
        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('user_level', function ($penjualan) {
                return $penjualan->user->level->level_nama;
            })
            ->addColumn('action', function ($penjualan) {
                $btn = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function create_ajax()
    {
        $users = UserModel::with('level')->get();
        $barang = BarangModel::all();
        return view('penjualan.create_ajax', compact('users', 'barang'));
    }
    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'barang_id' => 'required|array',
                'barang_id.*' => 'required|integer|exists:m_barang,barang_id',
                'harga' => 'required|array',
                'harga.*' => 'required|numeric',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ]);
            }
            $user = UserModel::findOrFail($request->user_id);
            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'pembeli' => $user->nama,
            ]);
            foreach ($request->barang_id as $key => $barang_id) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => $request->harga[$key],
                    'jumlah' => $request->jumlah[$key],
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }
        return redirect('/penjualan');
    }    
    public function show_ajax($id) {
        $penjualan = PenjualanModel::with('penjualanDetail.barang', 'user.level')->findOrFail($id);
        return view('penjualan.show_ajax', compact('penjualan'));
    }
    public function edit_ajax($id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->findOrFail($id); 
        $users = UserModel::with('level')->get();
        $barang = BarangModel::all();
        return view('penjualan.edit_ajax', compact('penjualan', 'users', 'barang'));
    }
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'penjualan_kode' => 'required|string|min:3',
                'penjualan_tanggal' => 'required|date',
                'barang_id' => 'required|array',
                'barang_id.*' => 'required|integer',
                'harga' => 'required|array',
                'harga.*' => 'required|numeric|min:1',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer|min:1',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->update([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
            ]);
            PenjualanDetailModel::where('penjualan_id', $id)->delete();
            foreach ($request->barang_id as $key => $barang_id) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => $request->harga[$key],
                    'jumlah' => $request->jumlah[$key],
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diupdate'
            ]);
        }
        return redirect('/penjualan');
    }    
    public function confirm_ajax($id) {
        $penjualan = PenjualanModel::with('penjualanDetail.barang')->findOrFail($id);
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }
    public function delete_ajax(Request $request, $id) {
        if ($request->ajax()) {
            $penjualan = PenjualanModel::findOrFail($id);
            try {
                PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();
                $penjualan->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Penjualan berhasil dihapus!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menghapus penjualan!'
                ]);
            }
        }
        return redirect('/penjualan');
    }
    public function import_view()
    {
        return view('penjualan.import');
    }    
    public function import_excel(Request $request)
    {
        $rules = [
            'file_penjualan' => 'required|mimes:xlsx|max:2048',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }
        $file = $request->file('file_penjualan');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $penjualanCache = [];
        foreach ($rows as $key => $row) {
            if ($key > 1) {
                if (!isset($penjualanCache[$row['B']])) {
                    $penjualan = PenjualanModel::create([
                        'user_id' => $row['A'],
                        'penjualan_kode' => $row['B'],
                        'pembeli' => $row['C'],
                        'penjualan_tanggal' => $row['D'],
                    ]);
                    $penjualanCache[$row['B']] = $penjualan->penjualan_id;
                }
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualanCache[$row['B']],
                    'barang_id' => $row['E'],
                    'jumlah' => $row['F'],
                    'harga' => $row['G'],
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Data penjualan berhasil diimport.'
        ]);
    }
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'user_id');
        $sheet->setCellValue('B1', 'penjualan_kode');
        $sheet->setCellValue('C1', 'pembeli');
        $sheet->setCellValue('D1', 'penjualan_tanggal');
        $sheet->setCellValue('E1', 'barang_id');
        $sheet->setCellValue('F1', 'jumlah');
        $sheet->setCellValue('G1', 'harga');
        $sampleData = [
            [1, 'PJL1', 'Pembeli 1', '2024-09-18 00:38:30', 1, 2, 15000],
            [1, 'PJL1', 'Pembeli 1', '2024-09-18 00:38:30', 2, 1, 25000],
            [1, 'PJL1', 'Pembeli 1', '2024-09-18 00:38:30', 3, 3, 35000],
            [2, 'PJL2', 'Pembeli 2', '2024-09-18 00:38:30', 4, 1, 45000],
            [2, 'PJL2', 'Pembeli 2', '2024-09-18 00:38:30', 5, 2, 55000],
            [2, 'PJL2', 'Pembeli 2', '2024-09-18 00:38:30', 6, 1, 65000],
            [3, 'PJL3', 'Pembeli 3', '2024-09-18 00:38:30', 7, 2, 75000],
            [3, 'PJL3', 'Pembeli 3', '2024-09-18 00:38:30', 8, 1, 85000],
            [3, 'PJL3', 'Pembeli 3', '2024-09-18 00:38:30', 9, 3, 95000],
        ];
        $rowNum = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValue('A' . $rowNum, $row[0]);
            $sheet->setCellValue('B' . $rowNum, $row[1]);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $sheet->setCellValue('D' . $rowNum, $row[3]);
            $sheet->setCellValue('E' . $rowNum, $row[4]);
            $sheet->setCellValue('F' . $rowNum, $row[5]);
            $sheet->setCellValue('G' . $rowNum, $row[6]);
            $rowNum++;
        }
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_penjualan.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
    public function export_excel()
    {
        $penjualan = PenjualanModel::with(['penjualanDetail'])->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'user_id');
        $sheet->setCellValue('B1', 'penjualan_kode');
        $sheet->setCellValue('C1', 'pembeli');
        $sheet->setCellValue('D1', 'penjualan_tanggal');
        $sheet->setCellValue('E1', 'barang_id');
        $sheet->setCellValue('F1', 'jumlah');
        $sheet->setCellValue('G1', 'harga');
        $rowNum = 2;
        foreach ($penjualan as $sale) {
            foreach ($sale->penjualanDetail as $detail) {
                $sheet->setCellValue('A' . $rowNum, $sale->user_id);
                $sheet->setCellValue('B' . $rowNum, $sale->penjualan_kode);
                $sheet->setCellValue('C' . $rowNum, $sale->pembeli);
                $sheet->setCellValue('D' . $rowNum, $sale->penjualan_tanggal);
                $sheet->setCellValue('E' . $rowNum, $detail->barang_id);
                $sheet->setCellValue('F' . $rowNum, $detail->jumlah);
                $sheet->setCellValue('G' . $rowNum, $detail->harga);
                $rowNum++;
            }
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'penjualan_data_' . date('Y-m-d_H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $penjualan = PenjualanModel::with(['penjualanDetail'])->get();
        $pdf = PDF::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        return $pdf->stream('penjualan_data.pdf');
    }
}
