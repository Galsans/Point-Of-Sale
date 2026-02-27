<style>
    .pkg-section {
        margin: 16px 0 8px;
    }

    .pkg-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px 12px;
    }

    .pkg-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pkg-icon {
        font-size: 28px;
        line-height: 1;
    }

    .pkg-title {
        font-size: 17px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        line-height: 1.2;
    }

    .pkg-subtitle {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .pkg-nav {
        display: flex;
        gap: 6px;
    }

    .pkg-nav-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1.5px solid #e0e0e0;
        background: #fff;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all .2s;
        -webkit-tap-highlight-color: transparent;
    }

    .pkg-nav-btn:hover,
    .pkg-nav-btn:active {
        background: #ff6b35;
        border-color: #ff6b35;
        color: #fff;
    }

    .pkg-nav-btn:disabled {
        opacity: .35;
        cursor: default;
    }

    .pkg-track-wrapper {
        position: relative;
        overflow: hidden;
    }

    .pkg-fade {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 24px;
        pointer-events: none;
        z-index: 2;
    }

    .pkg-fade-left {
        left: 0;
        background: linear-gradient(to right, #f5f5f5, transparent);
    }

    .pkg-fade-right {
        right: 0;
        background: linear-gradient(to left, #f5f5f5, transparent);
    }

    .pkg-track {
        display: flex;
        gap: 12px;
        padding: 4px 16px 12px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }

    .pkg-track::-webkit-scrollbar {
        display: none;
    }

    .pkg-card {
        flex: 0 0 220px;
        min-width: 220px;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        scroll-snap-align: start;
        position: relative;
        transition: transform .2s, box-shadow .2s;
        border: 1.5px solid #f0f0f0;
        cursor: pointer;
    }

    .pkg-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .12);
    }

    .pkg-card:active {
        transform: scale(.98);
    }

    .pkg-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 3;
        background: #ff3b30;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .pkg-img-wrap {
        position: relative;
        height: 120px;
        overflow: hidden;
        background: linear-gradient(135deg, #fff3e0, #ffe0b2);
    }

    .pkg-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pkg-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }

    .pkg-savings-chip {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: #00c853;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .pkg-body {
        padding: 12px 12px 10px;
    }

    .pkg-name {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pkg-items {
        list-style: none;
        padding: 0;
        margin: 0 0 10px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .pkg-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #555;
        min-width: 0;
    }

    .pkg-item-dot {
        flex-shrink: 0;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #ff6b35;
    }

    .pkg-item-name {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pkg-item-cat {
        flex-shrink: 0;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .3px;
        text-transform: uppercase;
        padding: 1px 5px;
        border-radius: 4px;
    }

    .pkg-item-cat.makanan {
        background: #fff3e0;
        color: #e65100;
    }

    .pkg-item-cat.minuman {
        background: #e3f2fd;
        color: #1565c0;
    }

    .pkg-item-cat.dessert {
        background: #fce4ec;
        color: #880e4f;
    }

    .pkg-item-cat:not(.makanan):not(.minuman):not(.dessert) {
        background: #f3f3f3;
        color: #666;
    }

    .pkg-footer {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding-top: 8px;
        border-top: 1px solid #f5f5f5;
    }

    .pkg-price-block {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .pkg-price-original {
        font-size: 11px;
        color: #aaa;
        text-decoration: line-through;
        line-height: 1;
    }

    .pkg-price-main {
        font-size: 15px;
        font-weight: 800;
        color: #ff6b35;
        line-height: 1;
    }

    .pkg-add-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #ff6b35;
        border: none;
        color: #fff;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .2s, transform .15s;
        box-shadow: 0 2px 8px rgba(255, 107, 53, .35);
        flex-shrink: 0;
        -webkit-tap-highlight-color: transparent;
    }

    .pkg-add-btn:active {
        background: #e55a20;
        transform: scale(.9);
    }

    .pkg-add-btn.added {
        background: #00c853;
        box-shadow: 0 2px 8px rgba(0, 200, 83, .35);
    }

    .pkg-dots {
        display: flex;
        justify-content: center;
        gap: 5px;
        padding: 2px 0 12px;
    }

    .pkg-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        border: none;
        background: #ddd;
        cursor: pointer;
        padding: 0;
        transition: all .25s;
    }

    .pkg-dot.active {
        background: #ff6b35;
        width: 20px;
        border-radius: 3px;
    }

    .pkg-modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    .pkg-modal-img-wrap {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #fff3e0, #ffe0b2);
        flex-shrink: 0;
    }

    .pkg-modal-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pkg-modal-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
    }

    .pkg-modal-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, .25), transparent);
    }

    .pkg-modal-close {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(0, 0, 0, .4);
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
    }

    .pkg-modal-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 10;
        background: #ff3b30;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .pkg-modal-body {
        padding: 20px 20px 8px;
    }

    .pkg-modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 6px;
    }

    .pkg-modal-desc {
        font-size: 14px;
        color: #777;
        margin: 0 0 16px;
        line-height: 1.5;
    }

    .pkg-modal-price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .pkg-modal-price-original {
        font-size: 13px;
        color: #aaa;
        text-decoration: line-through;
    }

    .pkg-modal-price-main {
        font-size: 24px;
        font-weight: 800;
        color: #ff6b35;
    }

    .pkg-modal-savings-badge {
        background: #e8f5e9;
        color: #2e7d32;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .pkg-modal-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        color: #999;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .pkg-modal-divider::before,
    .pkg-modal-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f0f0f0;
    }

    .pkg-modal-items {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pkg-modal-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        background: #fafafa;
        border-radius: 12px;
    }

    .pkg-modal-item-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .pkg-modal-item-icon.makanan {
        background: #fff3e0;
    }

    .pkg-modal-item-icon.minuman {
        background: #e3f2fd;
    }

    .pkg-modal-item-icon.dessert {
        background: #fce4ec;
    }

    .pkg-modal-item-icon.other {
        background: #f3f3f3;
    }

    .pkg-modal-item-info {
        flex: 1;
        min-width: 0;
    }

    .pkg-modal-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .pkg-modal-item-price {
        font-size: 12px;
        color: #888;
        margin: 0;
    }

    .pkg-modal-item-qty {
        font-size: 12px;
        font-weight: 700;
        color: #ff6b35;
        background: #fff3f0;
        padding: 2px 8px;
        border-radius: 20px;
        flex-shrink: 0;
    }

    .pkg-modal-footer {
        padding: 12px 20px 16px;
        border-top: 1px solid #f5f5f5;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pkg-modal-qty {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f5f5f5;
        border-radius: 50px;
        padding: 6px 10px;
    }

    .pkg-qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #333;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        -webkit-tap-highlight-color: transparent;
    }

    .pkg-qty-btn:active {
        transform: scale(.9);
    }

    .pkg-qty-value {
        font-size: 16px;
        font-weight: 700;
        min-width: 24px;
        text-align: center;
        color: #1a1a1a;
    }

    .pkg-modal-add-btn {
        flex: 1;
        height: 48px;
        border-radius: 50px;
        border: none;
        background: #ff6b35;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background .2s, transform .15s;
        box-shadow: 0 4px 12px rgba(255, 107, 53, .35);
    }

    .pkg-modal-add-btn:active {
        background: #e55a20;
        transform: scale(.98);
    }

    .cart-fixed-bottom {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
    }

    .btn-clear-cart {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #dc3545;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 2px 8px rgba(220, 53, 69, .2);
    }

    .btn-clear-cart:hover {
        background: #dc3545;
        color: #fff;
    }

    .cart-btn {
        flex: 1;
    }

    @media(max-width:400px) {
        .pkg-card {
            flex: 0 0 185px;
            min-width: 185px;
        }

        .pkg-img-wrap {
            height: 100px;
        }

        .pkg-name {
            font-size: 13px;
        }

        .pkg-modal-img-wrap {
            height: 160px;
        }
    }

    @media(min-width:640px) {
        .pkg-card {
            flex: 0 0 240px;
            min-width: 240px;
        }
    }

    @media(min-width:1024px) {
        .pkg-card {
            flex: 0 0 260px;
            min-width: 260px;
        }

        .pkg-track {
            padding: 4px 24px 12px;
        }

        .pkg-header {
            padding: 0 24px 12px;
        }
    }
</style>
