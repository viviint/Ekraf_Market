<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekraf Market - Beranda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    {{-- NAVBAR MERAH EKRAF --}}
    <nav class="bg-red-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            {{-- Logo --}}
            <a href="/" class="text-2xl font-bold flex items-center gap-2">
                EKRAF MARKET
            </a>

            {{-- Menu Kanan --}}
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        {{-- 🔥 TOMBOL BARU: RIWAYAT PESANAN 🔥 --}}
                        {{-- Hanya muncul kalau user sudah LOGIN --}}
                        <a href="{{ route('orders.index') }}" class="bg-white text-red-700 px-4 py-2 rounded-full font-bold text-sm hover:bg-gray-100 transition flex items-center gap-2 shadow-sm">
                            📦 Riwayat Pesanan
                        </a>

                        {{-- Tombol Keranjang (Hiasan) --}}
                        <a href="#" class="text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </a>

                        {{-- Tombol Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-white text-red-700 px-4 py-1.5 rounded font-bold text-sm hover:bg-gray-100 transition">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- Kalau Belum Login --}}
                        <a href="{{ route('login') }}" class="font-semibold hover:text-gray-200">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 bg-white text-red-700 px-4 py-2 rounded font-bold hover:bg-gray-100 transition">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto py-16 px-4 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Merchandise & Jajan Mahasiswa</h1>
            <p class="text-lg text-gray-600 mb-8">Dukung wirausaha mahasiswa Tel-U Surabaya dengan mudah dan aman.</p>

            {{-- Search Bar --}}
            <div class="max-w-xl mx-auto flex shadow-lg rounded-lg overflow-hidden">
                <input type="text" placeholder="Cari jajan atau merchandise..." class="w-full px-4 py-3 border-none focus:ring-0 text-gray-700 bg-gray-50">
                <button class="bg-red-600 text-white px-6 py-3 font-bold hover:bg-red-700 transition">Cari</button>
            </div>
        </div>
    </header>

    {{-- CONTENT AREA --}}
    <main class="container mx-auto py-10 px-4">
        <div class="flex items-center gap-2 mb-6 border-l-4 border-red-600 pl-3">
            <h2 class="text-2xl font-bold text-gray-800">Katalog Terbaru</h2>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Contoh Produk 1 --}}
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden flex flex-col">
                <div class="aspect-[4/3] bg-gray-200 relative overflow-hidden group">
                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                        <span>Gambar Produk</span>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs font-bold text-red-600 uppercase tracking-wide mb-1">Fashion</span>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-2">TUS Shirt Limited Edition</h3>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Rp 80.000</span>
                        <button class="bg-red-600 text-white px-3 py-1.5 rounded text-sm font-bold hover:bg-red-700 transition">
                            + Keranjang
                        </button>
                    </div>
                </div>
            </div>

            {{-- Contoh Produk 2 --}}
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden flex flex-col">
                <div class="aspect-[4/3] bg-gray-200 relative overflow-hidden group">
                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                        <span>Gambar Produk</span>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs font-bold text-red-600 uppercase tracking-wide mb-1">Merchandise</span>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-2">Keychain Akrilik Ekraf</h3>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Rp 28.000</span>
                        <button class="bg-red-600 text-white px-3 py-1.5 rounded text-sm font-bold hover:bg-red-700 transition">
                            + Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto text-center text-sm text-gray-400">
            &copy; 2025 Kementerian Ekraf BEM Tel-U Surabaya
        </div>
    </footer>

</body>
</html>
