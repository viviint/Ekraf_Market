<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Checkout Pesanan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Ringkasan Pesanan</h3>
                    @php $total = 0; @endphp
                    @foreach($carts as $cart)
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('storage/' . $cart->product->image) }}" class="w-12 h-12 object-cover rounded">
                            <div>
                                <h4 class="font-bold">{{ $cart->product->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $cart->quantity }} x Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <span class="font-bold">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                    </div>
                    @php $total += $cart->product->price * $cart->quantity; @endphp
                    @endforeach

                    <div class="border-t pt-4 flex justify-between items-center text-xl font-bold text-red-600">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md h-fit">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Metode Pembayaran</h3>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="space-y-3 mb-6">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-red-600 focus:ring-red-500" checked>
                                <span class="ml-3 font-medium">COD (Bayar di Tempat)</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="qris" class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <span class="ml-3 font-medium">QRIS (Scan Barcode)</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank" class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <span class="ml-3 font-medium">Transfer Bank (BCA/Mandiri)</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="ewallet" class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <span class="ml-3 font-medium">E-Wallet (Gopay/OVO)</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
