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
                <a href="{{ route('profile.edit') }}" class="font-bold">Akun Saya</a>
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
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-500">No. Invoice: <span class="font-mono font-bold text-gray-800">#{{ $order->invoice_number }}</span></p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $order->status == 'Menunggu Pembayaran' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status == 'Menunggu Verifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status == 'Diproses' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status == 'Selesai' ? 'bg-green-100 text-green-800' : '' }}
                            ">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t pt-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Tagihan:</p>
                            <p class="text-xl font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>

                        @if($order->status == 'Menunggu Pembayaran')
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
