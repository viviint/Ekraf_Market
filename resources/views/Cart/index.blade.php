<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-red-700 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">EKRAF MARKET</h1>
            <a href="{{ route('home') }}" class="text-sm font-semibold hover:underline bg-red-800 px-4 py-2 rounded">
                &larr; Lanjut Belanja
            </a>
        </div>
    </nav>

    <main class="container mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2 border-gray-300">Keranjang Belanja</h2>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                <p class="text-gray-500 text-lg mb-4">Keranjang kamu masih kosong nih.</p>
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">Mulai Belanja</a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 border-b text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="p-4">Produk</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4 text-right">Subtotal</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($carts as $item)
                        @php
                            $subtotal = $item->product->price * $item->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $item->product->name }}</div>
                            </td>
                            <td class="p-4">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">{{ $item->quantity }}</td>
                            <td class="p-4 text-right font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-6 bg-gray-50 border-t flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-lg">Total Belanja: <span class="text-2xl font-bold text-red-700">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span></div>

                    <a href="{{ route('checkout.index') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition shadow-lg text-center">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        @endif
    </main>
    <footer class="bg-gray-80
