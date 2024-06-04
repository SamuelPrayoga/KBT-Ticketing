@extends('layouts.app')
@section('title', 'Cari Kursi')
@section('styles')
    <style>
        a:hover {
            text-decoration: none;
        }

        .kursi {
            box-sizing: border-box;
            border: 2px solid #858796;
            width: 100%;
            height: 120px;
            display: flex;
            cursor: pointer;
        }

        .kursi.selected {
            background-color: #9ab1f7; /* Warna kursi yang dipilih */
            color: white;
        }

        a[disabled] {
            pointer-events: none;
            color: grey;
            text-decoration: none;
        }
    </style>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12" style="margin-top: 30px">
            <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i>
                Kembali</a>
            <div class="row mt-2">
                @for ($i = 1; $i <= $transportasi->jumlah; $i++)
                    @php
                        $array = ['kursi' => 'K' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']];
                        $cekData = json_encode($array);

                        $pemesanan = App\Models\Pemesanan::where('rute_id', $rute->id)->whereDate('waktu', $data['waktu'])->get();
                        $kursi = [];
                        foreach ($pemesanan as $pes) {
                            $seats = json_decode($pes->kursi, true);
                            foreach ($seats as $seat) {
                                array_push($kursi, $seat);
                            }
                        }
                    @endphp
                    @if (in_array('K' . $i, $kursi))
                        @if ($transportasi->kursi($cekData) != null)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="kursi" style="background-color:silver">
                                    <div class="font-weight-bold text-dark m-auto" style="font-size: 26px;">
                                        K{{ $i }}</div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="kursi" style="background: #858796">
                                    <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">
                                        K{{ $i }}</div>
                                </div>
                            </div>
                        @endif
                    @else
                        @if ($transportasi->kursi($cekData) != null)
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="kursi" onclick="selectKursi(this, 'K{{ $i }}')">
                                    <div class="font-weight-bold text-dark m-auto" style="font-size: 26px;">
                                        K{{ $i }}</div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                                <div class="kursi" style="background: #858796">
                                    <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">
                                        K{{ $i }}</div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endfor
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" onclick="prosesPemesanan()">Pesan Kursi</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let selectedSeats = [];

        function selectKursi(element, seatNumber) {
            element.classList.toggle('selected');
            if (selectedSeats.includes(seatNumber)) {
                selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
            } else {
                selectedSeats.push(seatNumber);
            }
        }

        function prosesPemesanan() {
            if (selectedSeats.length > 0) {
                const data = {
                    seats: selectedSeats,
                    route: '{{ $data["id"] }}',
                    time: '{{ $data["waktu"] }}'
                };
                const jsonData = encodeURIComponent(JSON.stringify(data)); // Simple base64 encoding, you can replace it with your encryption method

                window.location.href = `/pesan/${jsonData}`;
            } else {
                alert('Pilih kursi terlebih dahulu.');
            }
        }
    </script>
@endsection
