<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Customer - Test Order</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }

        .card {
            background: white;
            padding: 24px;
            max-width: 420px;
            margin: auto;
            border-radius: 10px;
        }

        input,
        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .success {
            margin-top: 15px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Buat Order Baru</h1>
                <p class="text-gray-600 mt-2">Isi form di bawah untuk membuat order baru</p>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('orders.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
                @csrf

                <!-- Customer Information Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">
                        👤 Informasi Customer
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Name -->
                        <div class="md:col-span-2">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Customer <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name', 'John Doe') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                                placeholder="Masukkan nama customer" required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Email -->
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="customer_email" id="customer_email"
                                value="{{ old('customer_email', 'customer@example.com') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                                placeholder="customer@email.com">
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Phone -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                No. Telepon
                            </label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone', '081234567890') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_phone') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx">
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Table Selection -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">
                        🪑 Pilih Meja
                    </h2>

                    <div>
                        <label for="table_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Meja <span class="text-red-500">*</span>
                        </label>
                        <select name="table_id" id="table_id"
                            class="form-control w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('table_id') border-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Meja --</option>
                            <option value="1" selected>Meja 1</option>
                            {{-- @foreach ($tables as $table)
                                <option value="{{ $table->id }}"
                                    {{ old('table_id') == $table->id || ($loop->first && !old('table_id')) ? 'selected' : '' }}>
                                    {{ $table->name }} (Kapasitas: {{ $table->table_kode ?? 'N/A' }})
                                </option>
                            @endforeach --}}
                        </select>
                        @error('table_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price Calculation Section -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">
                        💰 Kalkulasi Harga
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subtotal -->
                        <div>
                            <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">
                                Subtotal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="subtotal" id="subtotal"
                                    value="{{ old('subtotal', 150000) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subtotal') border-red-500 @enderror"
                                    placeholder="0.00" required onchange="calculateTotal()">
                            </div>
                            @error('subtotal')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Amount -->
                        <div>
                            <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Pajak (Tax)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="tax_amount" id="tax_amount"
                                    value="{{ old('tax_amount', 15000) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tax_amount') border-red-500 @enderror"
                                    placeholder="0.00" onchange="calculateTotal()">
                            </div>
                            @error('tax_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div>
                            <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Diskon
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="discount_amount" id="discount_amount"
                                    value="{{ old('discount_amount', 10000) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('discount_amount') border-red-500 @enderror"
                                    placeholder="0.00" onchange="calculateTotal()">
                            </div>
                            @error('discount_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Service Fee -->
                        <div>
                            <label for="service_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Biaya Layanan
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="service_fee" id="service_fee"
                                    value="{{ old('service_fee', 5000) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_fee') border-red-500 @enderror"
                                    placeholder="0.00" onchange="calculateTotal()">
                            </div>
                            @error('service_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Price (Read-only) -->
                        <div class="md:col-span-2">
                            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Harga <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="total_price" id="total_price"
                                    value="{{ old('total_price', 0) }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-bold text-lg @error('total_price') border-red-500 @enderror"
                                    placeholder="0.00" readonly required>
                            </div>
                            @error('total_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">
                        📋 Status Order
                    </h2>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="#"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Buat Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
            const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const serviceFee = parseFloat(document.getElementById('service_fee').value) || 0;

            const total = subtotal + taxAmount + serviceFee - discountAmount;

            document.getElementById('total_price').value = total.toFixed(2);
        }

        // Calculate total on page load
        window.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>




</body>

</html>
