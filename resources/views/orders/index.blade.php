<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-red-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">EKRAF MARKET</h1>
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="hover:underline">Lanjut Belanja</a>
                {{-- <a href="{{ route('profile.edit') }}" class="font-bold">Akun Saya</a> --}}
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2 border-gray-300">Riwayat Pesanan Saya</h2>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                <p class="text-gray-500 text-lg mb-4">Belum ada pesanan nih.</p>
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">Mulai Belanja</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100 relative overflow-hidden">

                    {{-- Header Card: Invoice & Status --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-500">No. Invoice: <span class="font-mono font-bold text-gray-800">#{{ $order->invoice_number }}</span></p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            {{-- Logic Warna Status --}}
                            @php
                                $statusColor = match($order->status) {
                                    'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',
                                    'Menunggu Verifikasi' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-blue-100 text-blue-800', // Jaga-jaga kalau admin simpan key 'paid'
                                    'Diproses' => 'bg-purple-100 text-purple-800',
                                    'Sedang Dikirim' => 'bg-indigo-100 text-indigo-800',
                                    'shipping' => 'bg-indigo-100 text-indigo-800', // Jaga-jaga kalau admin simpan key 'shipping'
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    {{-- BAGIAN BARU: TAMPILAN RESI --}}
                    {{-- Hanya muncul kalau kolom 'resi' di database ada isinya --}}
                    @if($order->resi)
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3 flex flex-col sm:flex-row justify-between items-center animate-pulse-once">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase">Nomor Resi Pengiriman</p>
                                <p class="text-lg font-mono font-bold text-gray-800 tracking-wider">{{ $order->resi }}</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded border">Ekspedisi: JNE / J&T</span>
                        </div>
                    </div>
                    @endif

                    {{-- Footer Card: Total & Tombol --}}
                    <div class="flex justify-between items-center border-t pt-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Tagihan:</p>
                            <p class="text-xl font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>

                        @if($order->status == 'Menunggu Pembayaran' || $order->status == 'pending')
                            <a href="{{ route('orders.payment', $order) }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 font-semibold text-sm transition">
                                Upload Bukti Bayar &rarr;
                            </a>
                        @else
                            <button disabled class="bg-gray-200 text-gray-400 px-4 py-2 rounded font-semibold text-sm cursor-not-allowed">
                                Lihat Detail
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
