document.addEventListener('DOMContentLoaded', function () {
    const bar = document.querySelector('.sticky-add-to-cart-v1');
    if (!bar) return;

    const decrementBtn = bar.querySelector('.qty-decrement');
    const incrementBtn = bar.querySelector('.qty-increment');
    const qtyInput = bar.querySelector('.qty-selector input[type="number"]');
    const hiddenQty = bar.querySelector('.qty-input-hidden');

    function sanitizeQty() {
        let val = parseInt(qtyInput.value, 10);
        if (isNaN(val) || val < 1) {
            val = 1;
        }
        qtyInput.value = val;
        hiddenQty.value = val;
    }

    decrementBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let current = parseInt(qtyInput.value, 10) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
            hiddenQty.value = current - 1;
        }
    });

    incrementBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let current = parseInt(qtyInput.value, 10) || 1;
        qtyInput.value = current + 1;
        hiddenQty.value = current + 1;
    });

    qtyInput.addEventListener('change', sanitizeQty);
    qtyInput.addEventListener('blur', sanitizeQty);

    const stickyBar = document.querySelector('.sticky-add-to-cart-v1');
    if (!stickyBar) return;

    const originalAddToCart = document.querySelector('form[action="/cart/add"] button[type="submit"]');

    if (!originalAddToCart) {
        console.warn('Sticky bar: could not locate original Add To Cart button. Adjust the selector.');
        return;
    }

    let buttonInView = true;
    let atBottomOfPage = false;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                buttonInView = entry.isIntersecting;
                updateStickyBarVisibility();
            });
        },
        {
            root: null,
            threshold: 0,
        }
    );
    observer.observe(originalAddToCart);

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY || window.pageYOffset;
        const viewportHeight = window.innerHeight;
        const fullHeight = document.documentElement.scrollHeight;

        if (scrollY + viewportHeight >= fullHeight - 1) {
            atBottomOfPage = true;
        } else {
            atBottomOfPage = false;
        }
        updateStickyBarVisibility();
    });

    function updateStickyBarVisibility() {
        if (!buttonInView && !atBottomOfPage) {
            stickyBar.classList.add('visible');
        } else {
            stickyBar.classList.remove('visible');
        }
    }
});
