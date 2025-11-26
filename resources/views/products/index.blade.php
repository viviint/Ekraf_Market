<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekraf Market - Telkom University Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-red-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">EKRAF MARKET</h1>
            <div>
                <a href="#" class="mr-4 hover:underline">Keranjang</a>
                <a href="#" class="bg-white text-red-700 px-4 py-2 rounded font-semibold">Login</a>
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
        <h3 class="text-xl font-bold mb-7 border-l-4 border-red-600 pl-3">Katalog Terbaru</h3>

        <form action="{{ route('home') }}" method="GET" class="flex w-full md:w-1/2 mb-8">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">Foto {{ $product->name }}</span>
                </div>

                <div class="p-4">
                    <p class="text-xs text-red-600 font-semibold uppercase tracking-wide">
                        {{ $product->category->name ?? 'Umum' }}
                    </p>
                    <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $product->name }}</h3>
                    <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <button class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700">
                            + Keranjang
                        </button>
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
