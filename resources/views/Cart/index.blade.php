<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex flex-col min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-red-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="/" class="text-2xl font-bold tracking-wide">EKRAF MARKET</a>

            <div class="flex items-center gap-3">
                {{-- Tombol Lanjut Belanja --}}
                <a href="{{ route('home') }}" class="text-sm font-semibold hover:bg-red-800 px-4 py-2 rounded transition border border-red-500">
                    &larr; Lanjut Belanja
                </a>

                {{-- Tombol Riwayat (Biar konsisten) --}}
                <a href="{{ route('orders.index') }}" class="text-sm font-semibold hover:bg-red-800 px-4 py-2 rounded transition">
                    📦 Riwayat
                </a>

                {{-- Logout (Penting biar gak terjebak) --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-white text-red-700 px-4 py-2 rounded font-bold text-sm hover:bg-gray-100 transition shadow-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="container mx-auto py-10 px-4 flex-grow">
        <div class="flex items-center justify-between mb-6 border-b pb-2 border-gray-300">
            <h2 class="text-2xl font-bold text-gray-800">🛒 Keranjang Belanja</h2>
            <span class="text-sm text-gray-500">{{ $carts->count() }} Item</span>
        </div>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
            </div>
        @endif

        {{-- Logic Keranjang Kosong --}}
        @if($carts->isEmpty())
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang kamu kosong</h3>
                <p class="text-gray-500 text-base mb-6">Yuk isi dengan jajan atau merchandise mahasiswa!</p>
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-8 py-3 rounded-full font-bold hover:bg-red-700 transition shadow-lg inline-flex items-center gap-2">
                    Mulai Belanja &rarr;
                </a>
            </div>
        @else
            {{-- Tabel Keranjang --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="p-4 font-bold">Produk</th>
                                <th class="p-4 font-bold">Harga</th>
                                <th class="p-4 font-bold text-center">Jumlah</th>
                                <th class="p-4 font-bold text-right">Subtotal</th>
                                <th class="p-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $grandTotal = 0; @endphp
                            @foreach($carts as $item)
                            @php
                                $subtotal = $item->product->price * $item->quantity;
                                $grandTotal += $subtotal;
                            @endphp
                            <tr class="hover:bg-red-50 transition group">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Gambar Kecil (Thumbnail) --}}
                                        <div class="w-12 h-12 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Img</div>
                                            @endif
                                        </div>
                                        <div class="font-bold text-gray-800">{{ $item->product->name }}</div>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <span class="bg-gray-100 px-3 py-1 rounded font-mono font-bold text-gray-700">{{ $item->quantity }}</span>
                                </td>
                                <td class="p-4 text-right font-bold text-red-600 text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-100 p-2 rounded-full transition" title="Hapus dari keranjang">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Keranjang (Total & Checkout) --}}
                <div class="p-6 bg-gray-50 border-t flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-gray-600">
                        <p class="text-sm">Subtotal Produk:</p>
                        <p class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full md:w-auto bg-red-600 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-red-700 transition shadow-lg flex justify-center items-center gap-2 transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Lanjut Checkout
                    </a>
                </div>
            </div>
        @endif
    </main>

    {{-- FOOTER YANG TADI KEPOTONG --}}
    <footer class="bg-gray-900 text-white py-8 mt-auto">
        <div class="container mx-auto text-center">
            <p class="font-bold text-lg mb-2">EKRAF MARKET</p>
            <p class="text-sm text-gray-400">&copy; 2025 Kementerian Ekraf BEM Tel-U Surabaya. <br>Dibuat dengan ❤️ untuk Mahasiswa.</p>
        </div>
    </footer>

</body>
</html>
