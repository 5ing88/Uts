<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class UtsController extends Controller
{
    public function index()
    {
        $totalPelanggan = DB::table('t_pelanggan')->count();
        $totalTransaksi = DB::table('t_transaksi')->count();
        $pelanggan = DB::table('t_pelanggan')->select('id_pelanggan', 'nama_pelanggan')->orderBy('nama_pelanggan', 'asc')->get();

        $daftarTransaksi = DB::table('t_transaksi as t')
            ->select('t.id_transaksi', 'p.nama_pelanggan', 'p.email', 't.tanggal_transaksi', 't.total_transaksi')
            ->join('t_pelanggan as p', 't.id_pelanggan', '=', 'p.id_pelanggan')
            ->orderBy('t.tanggal_transaksi', 'desc') 
            ->orderBy('t.id_transaksi', 'desc')
            ->get();

        $grafikData = DB::table('t_transaksi as t')
            ->select(DB::raw('p.nama_pelanggan'), DB::raw('SUM(t.total_transaksi) as total'))
            ->join('t_pelanggan as p', 't.id_pelanggan', '=', 'p.id_pelanggan')
            ->groupBy('p.nama_pelanggan')
            ->orderBy('total', 'desc')
            ->get();

        return view('dashboard', [
            'totalPelanggan' => $totalPelanggan,
            'totalTransaksi' => $totalTransaksi,
            'pelanggan' => $pelanggan,
            'daftarTransaksi' => $daftarTransaksi,
            'grafikData' => $grafikData 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:t_pelanggan,id_pelanggan',
            'tanggal_transaksi' => 'required|date',
            'total_transaksi' => 'required|numeric|min:1',
        ]);
        
        $nextId = DB::table('t_transaksi')->max('id_transaksi') + 1;

        DB::table('t_transaksi')->insert([
            'id_transaksi' => $nextId,
            'id_pelanggan' => $request->id_pelanggan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_transaksi' => $request->total_transaksi,
        ]);
        
        return redirect()->route('dashboard')
        ->with('success', 'Data transaksi berhasil ditambahkan.');
    }

    public function storePelanggan(Request $request)
    {
        $request->validate([
        'nama_pelanggan' => 'required|string|max:100',
        'email' => 'required|email|unique:t_pelanggan,email',
        'no_hp' => 'nullable|string|max:15',
        'alamat' => 'nullable|string|max:200',
        ]);

        $nextId = DB::table('t_pelanggan')->max('id_pelanggan') + 1;

        DB::table('t_pelanggan')->insert([
        'id_pelanggan' => $nextId,
        'nama_pelanggan' => $request->nama_pelanggan,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        ]);

        return redirect()->route('dashboard')
        ->with('success', 'Data pelanggan berhasil ditambahkan.'); 
    }
}