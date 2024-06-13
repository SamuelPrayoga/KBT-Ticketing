<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rute = Rute::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $transportasi = Transportasi::count();
        $user = User::count();

        // Mengumpulkan data penjualan harian
        $penjualanHarian = Pemesanan::select(DB::raw('DATE(waktu) as date'), DB::raw('sum(total) as total'))
                                    ->where('status', 'Sudah Bayar')
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->get();

        // Mengumpulkan data jumlah pemesanan tiket harian
        $jumlahPemesananHarian = Pemesanan::select(DB::raw('DATE(waktu) as date'), DB::raw('count(kursi) as total_kursi'))
                                           ->groupBy('date')
                                           ->orderBy('date')
                                           ->get();

        // Memisahkan data tanggal dan jumlah penjualan
        $dates = $penjualanHarian->pluck('date');
        $sales = $penjualanHarian->pluck('total');

        // Memisahkan data tanggal dan jumlah pemesanan tiket
        $datesPemesanan = $jumlahPemesananHarian->pluck('date');
        $jumlahPemesanan = $jumlahPemesananHarian->pluck('total_kursi');

        return view('server.home', compact('rute', 'pendapatan', 'transportasi', 'user', 'dates', 'sales', 'datesPemesanan', 'jumlahPemesanan'));
    }
}
