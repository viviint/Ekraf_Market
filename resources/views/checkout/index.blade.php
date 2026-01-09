<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Checkout Pesanan</h2>

            {{-- Kita bungkus semua dalam form biar satu kali submit --}}
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- KOLOM KIRI: Ringkasan Produk --}}
                    <div class="bg-white p-6 rounded-lg shadow-md h-fit">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Ringkasan Pesanan</h3>
                        @php $total = 0; @endphp
                        @foreach($carts as $cart)
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . $cart->product->image) }}" class="w-12 h-12 object-cover rounded">
                                <div>
                                    <h4 class="font-bold text-sm">{{ $cart->product->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $cart->quantity }} x Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                    {{-- Info sisa stok biar user aware --}}
                                    <p class="text-xs text-red-500">Sisa Stok: {{ $cart->product->stock }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-sm">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                        </div>
                        @php $total += $cart->product->price * $cart->quantity; @endphp
                        @endforeach

                        <div class="border-t pt-4 flex justify-between items-center text-xl font-bold text-red-600">
                            <span>Total Bayar</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Form Alamat & Pembayaran --}}
                    <div class="space-y-6">

                        {{-- 1. FORM ALAMAT PENGIRIMAN (BARU) --}}
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">📍 Alamat Pengiriman</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Penerima</label>
                                    <input type="text" name="recipient_name"
                                           value="{{ auth()->user()->name }}"
                                           class="w-full border-gray-300 rounded-lg focus:ring-red-600 focus:border-red-600 text-sm"
                                           required placeholder="Nama lengkap penerima">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp / HP</label>
                                    <input type="text" name="phone_number"
                                           class="w-full border-gray-300 rounded-lg focus:ring-red-600 focus:border-red-600 text-sm"
                                           required placeholder="08xxxxxxxxxx">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="address" rows="3"
                                              class="w-full border-gray-300 rounded-lg focus:ring-red-600 focus:border-red-600 text-sm"
                                              required placeholder="Jalan, RT/RW, Nomor Rumah, Kecamatan..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- 2. METODE PEMBAYARAN --}}
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Metode Pembayaran</h3>
                            <div class="space-y-3 mb-6">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-red-600 focus:ring-red-500" checked>
                                    <span class="ml-3 font-medium text-sm">COD (Bayar di Tempat)</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="qris" class="h-4 w-4 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 font-medium text-sm">QRIS (Scan Barcode)</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank" class="h-4 w-4 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 font-medium text-sm">Transfer Bank</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition shadow-lg">
                                Bayar Sekarang 🚀
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
