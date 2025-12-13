<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Pembayaran - Ekraf Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md overflow-hidden">
            <div class="bg-red-700 p-4 text-white flex justify-between items-center">
                <h2 class="font-bold text-lg">Konfirmasi Pembayaran</h2>
                <a href="{{ route('orders.index') }}" class="text-sm hover:underline opacity-80">Batal</a>
            </div>

            <div class="p-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6 text-sm text-yellow-800">
                    <p class="font-bold mb-1">Silakan transfer ke:</p>
                    <p>Bank: <span class="font-bold">BCA (Bank Central Asia)</span></p>
                    <p>No. Rek: <span class="font-mono font-bold text-lg">123-456-7890</span></p>
                    <p>A.N: <span class="font-bold">BEM Tel-U Surabaya</span></p>
                    <div class="mt-2 border-t border-yellow-200 pt-2">
                        Total: <span class="font-bold text-red-600 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('orders.upload', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Upload Bukti Transfer (Foto/Screenshot)</label>
                        <input type="file" name="payment_proof" accept="image/*" required
                            class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        @error('payment_proof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded hover:bg-red-700 transition duration-200">
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
