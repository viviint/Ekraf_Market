<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekraf Market - Telkom University Surabaya [cite: 9]</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <nav class="bg-red-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">EKRAF MARKET</h1>

            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="text-yellow-300 font-bold hover:underline text-sm mr-2">Dashboard Admin</a>
                    @endif

            <a href="{{ route('cart.index') }}" class="relative hover:text-gray-200 p-1 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.023.824l.707 4.243a1.125 1.125 0 0 0 0 2.378H18.75M13.75 3h4.875a1.125 1.125 0 0 1 1.096 1.096l-1.375 6.875M16.5 13.5h.008v.008h-.008zM16.5 13.5h.008v.008h-.008zM6 18.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM18.75 18.75a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                </svg>

                @if (isset($cartItemCount) && $cartItemCount > 0)
            <span class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-800 rounded-full -top-1 right-0 border-2 border-red-700">
                {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
            </span>
                @endif
            </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-white text-red-700 px-4 py-2 rounded font-semibold text-sm hover:bg-gray-100">
                            Logout
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="bg-white text-red-700 px-4 py-2 rounded font-semibold text-sm hover:bg-gray-100">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-white hover:underline text-sm">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="bg-white py-10 shadow-sm">
    <div class="container mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-gray-800">Merchandise & Jajan Mahasiswa</h2>
        <p class="text-gray-500 mt-2">Dukung wirausaha mahasiswa Tel-U Surabaya!</p>
    </div>
    </header>

    <main class="container mx-auto py-10 px-4">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h3 class="text-xl font-bold border-l-4 border-red-600 pl-3">Katalog Terbaru</h3>

            <form action="{{ route('home') }}" method="GET" class="flex w-full md:w-1/2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari jajan atau merchandise..."
                    class="border border-gray-300 rounded-l-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-red-600"
                >
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-r-lg hover:bg-red-700 font-semibold transition">
                    Cari
                </button>
            </form>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

                <div class="p-4">
                    <p class="text-xs text-red-600 font-semibold uppercase tracking-wide">
                        {{ $product->category->name ?? 'Umum' }}
                    </p>
                    <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $product->name }}</h3>
                    <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                        <form action="{{ route('cart.store', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700">
                                + Keranjang
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-right">Stok: {{ $product->stock }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center py-6 mt-10">
        <p>&copy; 2025 Kementerian Ekraf BEM Tel-U Surabaya</p>
    </footer>

</body>
</html>
