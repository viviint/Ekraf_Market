<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Ekraf Market</title>
    {{-- Pastikan AlpineJS jalan buat dropdown review --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-red-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">EKRAF MARKET</h1>
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="hover:underline">Lanjut Belanja</a>
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

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
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
                {{-- HAPUS overflow-hidden DISINI BIAR DROPDOWN GAK KEPOTONG --}}
                <div class="bg-white rounded-lg shadow p-6 border border-gray-100 relative">

                    {{-- Header Card: Invoice & Status --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 border-b pb-4">
                        <div>
                            <p class="text-sm text-gray-500">No. Invoice: <span class="font-mono font-bold text-gray-800">#{{ $order->invoice_number }}</span></p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            @php
                                $statusColor = match($order->status) {
                                    'Menunggu Pembayaran', 'pending' => 'bg-yellow-100 text-yellow-800',
                                    'Menunggu Verifikasi' => 'bg-blue-100 text-blue-800',
                                    'Diproses' => 'bg-purple-100 text-purple-800',
                                    'Sedang Dikirim', 'shipping' => 'bg-indigo-100 text-indigo-800',
                                    'Selesai', 'completed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- BAGIAN TAMPILAN RESI --}}
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

                    {{-- ======= BAGIAN BARU: LIST PRODUK & REVIEW ======= --}}
                    <div class="mb-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-2">Daftar Produk:</h3>
                        <div class="space-y-3">
                            @foreach ($order->products as $product)
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-3 rounded shadow-sm">

                                    {{-- Info Produk --}}
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $product->name }}</h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $product->pivot->quantity }} x Rp {{ number_format($product->price) }}
                                        </p>
                                    </div>

                                    {{-- TOMBOL REVIEW (Cuma Muncul kalau Status SELESAI / COMPLETED) --}}
                                    @if(in_array(strtolower($order->status), ['selesai', 'completed']))
                                        <div x-data="{ open: false }" class="mt-2 sm:mt-0 w-full sm:w-auto relative">
                                            <button @click="open = !open" class="text-xs text-blue-600 font-bold hover:underline border border-blue-600 px-3 py-1 rounded hover:bg-blue-50 w-full sm:w-auto text-center">
                                                ★ Beri Ulasan
                                            </button>

                                            {{-- Form Review Dropdown --}}
                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 x-transition
                                                 class="mt-2 p-4 bg-white border border-gray-300 rounded-lg shadow-xl absolute right-0 z-20 w-72 sm:w-80">

                                                <form action="{{ route('reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                                    <label class="block text-xs font-bold mb-1 text-gray-700">Pilih Rating:</label>
                                                    <select name="rating" class="w-full text-sm border-gray-300 rounded mb-3 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                        <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                        <option value="3">⭐⭐⭐ (Biasa)</option>
                                                        <option value="2">⭐⭐ (Kurang)</option>
                                                        <option value="1">⭐ (Buruk)</option>
                                                    </select>

                                                    <label class="block text-xs font-bold mb-1 text-gray-700">Komentar:</label>
                                                    <textarea name="comment" rows="3" class="w-full text-sm border-gray-300 rounded mb-3" placeholder="Tulis pengalamanmu..."></textarea>

                                                    {{-- INI BUTTONNYA BANG --}}
                                                    <button type="submit" class="w-full bg-blue-600 border border-blue-700 text-white font-bold py-2 mt-3 rounded-lg text-sm hover:bg-blue-700 hover:shadow-lg transition duration-200">
                                                        Kirim Ulasan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- ================================================ --}}

                    {{-- Footer Card: Total & Tombol --}}
                    <div class="flex justify-between items-center border-t pt-4 mt-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Tagihan:</p>
                            <p class="text-xl font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>

                        @if($order->status == 'Menunggu Pembayaran' || $order->status == 'pending')
                            <a href="{{ route('orders.payment', $order) }}" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 font-semibold text-sm transition">
                                Upload Bukti Bayar &rarr;
                            </a>
                        @else
                            <button disabled class="bg-gray-100 text-gray-400 px-4 py-2 rounded font-semibold text-sm cursor-not-allowed">
                                Detail
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
