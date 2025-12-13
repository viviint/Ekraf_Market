<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-gray-900 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-yellow-400">ADMIN PANEL 🔥</h1>
            <div class="flex gap-4 items-center">
                <span class="text-sm text-gray-300">Halo, Admin</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs bg-red-600 px-3 py-1 rounded hover:bg-red-700 font-bold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pesanan Masuk</h2>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline text-sm">Lihat Website Utama &rarr;</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-800 text-white uppercase text-xs font-bold">
                    <tr>
                        <th class="p-4">Invoice</th>
                        <th class="p-4">Pembeli</th>
                        <th class="p-4">Total</th>
                        <th class="p-4 text-center">Bukti</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach($orders as $order)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-mono font-bold">#{{ $order->invoice_number }}</td>
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $order->shipping_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="p-4 font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>

                        <td class="p-4 text-center">
                            @if($order->payment_proof)
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                   class="inline-block bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold hover:bg-blue-200">
                                    📷 Lihat Foto
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">Belum upload</span>
                            @endif
                        </td>

                        <td class="p-4 text-center">
                             <span class="px-2 py-1 rounded text-xs font-bold border
                                {{ $order->status == 'Menunggu Pembayaran' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                {{ $order->status == 'Menunggu Verifikasi' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                {{ $order->status == 'Diproses' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                            ">
                                {{ $order->status }}
                            </span>
                        </td>

                        <td class="p-4 text-center">
                            @if($order->status == 'Menunggu Verifikasi')
                                <form action="{{ route('admin.orders.verify', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 text-xs font-bold transition transform hover:scale-105">
                                        ✅ Verifikasi
                                    </button>
                                </form>
                            @elseif($order->status == 'Diproses')
                                <span class="text-green-600 font-bold text-xs">✓ Sudah OK</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($orders->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Belum ada pesanan masuk.
                </div>
            @endif
        </div>
    </main>
</body>
</html>
