<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pemesanan Menu')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #d4a574;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: #f5f6f8;
            padding-bottom: 80px;
            /* Space for fixed cart button */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* HEADER */
        .navbar {
            /* background: linear-gradient(135deg, var(--primary-color) 0%, #d4a574 100%); */
            background: rgba(44, 32, 19, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .table-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* CATEGORY FILTER */
        .category-filter {
            background: white;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .category-filter::-webkit-scrollbar {
            display: none;
        }

        .category-btn {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            margin-right: 0.5rem;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6c757d;
            transition: all 0.2s;
            cursor: pointer;
        }

        .category-btn:hover {
            background: #e9ecef;
        }

        .category-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* MENU CARD */
        .menu-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            border: none;
        }

        .menu-card:active {
            transform: scale(0.98);
        }

        .menu-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .menu-card-img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .menu-card-body {
            padding: 0.875rem;
        }

        .menu-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.375rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-category {
            font-size: 0.75rem;
            color: #718096;
            margin-bottom: 0.5rem;
        }

        .menu-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--success-color);
            margin-bottom: 0.625rem;
        }

        /* QUANTITY SELECTOR */
        .qty-selector {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 0.25rem;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: white;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .qty-btn:active {
            transform: scale(0.95);
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .qty-display {
            font-weight: 700;
            font-size: 1rem;
            color: #2d3748;
            min-width: 30px;
            text-align: center;
        }

        /* CART BUTTON (FIXED BOTTOM) */
        .cart-fixed-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .cart-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #d4a574 100%);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
            transition: all 0.2s;
        }

        .cart-btn:active {
            transform: scale(0.98);
        }

        .cart-btn:disabled {
            background: #cbd5e0;
            box-shadow: none;
            cursor: not-allowed;
        }

        .cart-badge {
            background: rgba(255, 255, 255, 0.3);
            padding: 0.25rem 0.625rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .cart-total {
            font-size: 1.125rem;
            font-weight: 700;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* LOADING SKELETON */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* SEARCH BAR */
        .search-bar {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.25rem;
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .menu-card-img {
                height: 120px;
            }

            .menu-name {
                font-size: 0.85rem;
            }

            .menu-price {
                font-size: 0.9rem;
            }
        }

        /* ANIMATIONS */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu-card {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- HEADER --}}
    <nav class="navbar sticky-top">
        <div class="container">
            <span class="navbar-brand">
                🍽️ {{ config('app.name', 'Restaurant') }}
            </span>
            <span class="table-badge">
                <i class="mdi mdi-table-furniture"></i> @yield('table-info', 'Meja')
            </span>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="py-3">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
