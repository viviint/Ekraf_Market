<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <div class="max-w-2xl mx-auto py-10 px-4">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Ulas Pesanan #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="text-sm text-red-600 hover:underline">&larr; Kembali</a>
        </div>

        {{-- Pesan Sukses/Gagal --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6 border border-red-200">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- List Produk dalam Order --}}
        @foreach($order->items as $item)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="flex gap-4 mb-4 border-b pb-4">
                    {{-- Gambar Produk (Kalau ada) --}}
                    <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden">
                         @if($item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="flex items-center justify-center h-full text-xs text-gray-500">No img</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">{{ $item->product->name }}</h3>
                        <p class="text-gray-500 text-sm">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Form Review --}}
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Rating Bintang:</label>
                        <div class="flex gap-4">
                            {{-- Radio Button Bintang (Sederhana) --}}
                            <select name="rating" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 p-2">
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Kecewa)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Ulasan Kamu:</label>
                        <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring p-2" placeholder="Bagaimana kualitas produk ini?"></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

</body>
</html>
