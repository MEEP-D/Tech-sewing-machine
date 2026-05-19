<style>
    .cookie-consent {
        position: fixed;
        left: 16px;
        right: 16px;
        bottom: 16px;
        z-index: 1100;
        background: linear-gradient(120deg, #ffffff 0%, #f8fbff 100%);
        color: var(--text-main);
        border: 1px solid var(--border-gray);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        padding: 16px;
        display: none;
    }

    .cookie-consent.is-visible {
        display: block;
    }

    .cookie-consent__wrap {
        max-width: 1120px;
        margin: 0 auto;
        display: flex;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .cookie-consent__text {
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
        flex: 1 1 520px;
        color: var(--text-muted);
    }

    .cookie-consent__actions {
        display: flex;
        gap: 10px;
    }

    .cookie-consent__btn {
        border: 1px solid transparent;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 700;
        padding: 10px 14px;
        cursor: pointer;
        transition: var(--transition-base);
    }

    .cookie-consent__btn--accept {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: #fff;
    }

    .cookie-consent__btn--decline {
        background: #fff;
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .cookie-consent__btn:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .cookie-consent__btn--accept:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .cookie-consent__btn--decline:hover {
        background: var(--primary-light);
    }

    .cookie-consent__link {
        color: var(--primary-blue);
        text-decoration: underline;
        font-weight: 700;
    }

    @media (max-width: 640px) {
        .cookie-consent {
            left: 10px;
            right: 10px;
            bottom: 10px;
            padding: 14px;
        }

        .cookie-consent__actions {
            width: 100%;
        }

        .cookie-consent__btn {
            width: 100%;
        }
    }
</style>

<div id="cookie-consent" class="cookie-consent" role="dialog" aria-live="polite" aria-label="Thông báo cookie">
    <div class="cookie-consent__wrap">
        <p class="cookie-consent__text">
            Website sử dụng cookie để ghi nhớ tùy chọn và cải thiện hiệu năng. Bạn có thể đồng ý hoặc từ chối cookie không thiết yếu.
            <a class="cookie-consent__link" href="{{ url('/chinh-sach-cookie') }}">Xem chính sách cookie</a>.
        </p>
        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__btn cookie-consent__btn--decline" data-cookie-consent="decline">Từ chối</button>
            <button type="button" class="cookie-consent__btn cookie-consent__btn--accept" data-cookie-consent="accept">Đồng ý</button>
        </div>
    </div>
</div>

<script>
    (function () {
        var COOKIE_NAME = 'cookie_consent_status';
        var COOKIE_DAYS = 180;
        var banner = document.getElementById('cookie-consent');
        if (!banner) return;

        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }

        function setCookie(name, value, days) {
            var expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
        }

        function hideBanner() {
            banner.classList.remove('is-visible');
        }

        function showBanner() {
            banner.classList.add('is-visible');
        }

        if (!getCookie(COOKIE_NAME)) {
            showBanner();
        }

        banner.addEventListener('click', function (event) {
            var action = event.target && event.target.getAttribute('data-cookie-consent');
            if (!action) return;
            setCookie(COOKIE_NAME, action, COOKIE_DAYS);
            hideBanner();
        });
    })();
</script>
