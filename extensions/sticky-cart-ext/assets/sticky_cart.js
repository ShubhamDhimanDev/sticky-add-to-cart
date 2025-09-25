document.addEventListener('DOMContentLoaded', function () {
    const bar = document.querySelector('.sticky-add-to-cart-v1');
    if (!bar) return;

    const decrementBtn = bar.querySelector('.qty-decrement');
    const incrementBtn = bar.querySelector('.qty-increment');
    const qtyInput = bar.querySelector('.qty-selector input[type="number"]');
    const hiddenQty = bar.querySelectorAll('.qty-input-hidden');

    function sanitizeQty() {
        let val = parseInt(qtyInput.value, 10);
        if (isNaN(val) || val < 1) {
            val = 1;
        }
        qtyInput.value = val;
        hiddenQty.forEach(qty => {
            qty.value = val;
        });
    }

    decrementBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let current = parseInt(qtyInput.value, 10) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
            document.querySelector('input[name="quantity"]').value = current - 1;
            hiddenQty.forEach(qty => {
                qty.value = current - 1;
            });
        }
    });

    incrementBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let current = parseInt(qtyInput.value, 10) || 1;
        qtyInput.value = current + 1;
        document.querySelector('input[name="quantity"]').value = current + 1;
        hiddenQty.forEach(qty => {
            qty.value = current + 1;
        });
    });

    qtyInput.addEventListener('change', sanitizeQty);
    qtyInput.addEventListener('blur', sanitizeQty);

    const stickyBar = document.querySelector('.sticky-add-to-cart-v1');
    if (!stickyBar) return;

    if (window.Shopify && Shopify.designMode) {
        stickyBar.classList.add('visible');
    } else {

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
    }

    function setLoading(btn, isLoading) {
        if (isLoading) {
            btn.querySelector('span').classList.remove('sticky__cart__hidden');
        } else {
            btn.querySelector('span').classList.add('sticky__cart__hidden');
        }
    }

    function clickButtonByKeyword(formSelector, keyword) {
        const re = new RegExp(`\\b${keyword}\\b`, 'i');
        const buttons = Array.from(document.querySelectorAll(`${formSelector} button`));

        let btn = null;

        for (const b of buttons) {
            const text = b.textContent.replace(/\s+/g, ' ').trim();
            if (re.test(text)) {
                const style = window.getComputedStyle(b);
                const isVisible =
                    style.display !== "none" &&
                    style.visibility !== "hidden" &&
                    style.opacity !== "0" &&
                    b.offsetParent !== null;

                if (isVisible) {
                    btn = b;
                    break; // stop at the first visible button
                }
            }
        }

        if (btn) {
            console.log("Clicking visible button:", btn);
            btn.click();
        } else if (keyword.toLowerCase() === 'buy') {
            const frame = document.querySelector('iframe[title="PayPal"]');
            if (!frame) {
                return console.warn('PayPal iframe not found');
            }
        } else {
            console.warn(`No visible button containing the word "${keyword}" was found in ${formSelector}`);
        }
    }

    document.getElementById('buy__now').addEventListener('click', (e) => {
        clickButtonByKeyword('form[action="/cart/add"]', 'buy');
    });

    document.getElementById('cart__now').addEventListener('click', (e) => {
        setLoading(e.target, true);
        clickButtonByKeyword('form[action="/cart/add"]', 'cart');
        setTimeout(() => {
            setLoading(document.getElementById('cart__now'), false);
        }, 3000);
    });

    document.getElementById('sticky__quantity').addEventListener('change', (e)=>{
        document.querySelector('input[name="quantity"]').value = e.target.value;
    });

    document.querySelector('input[name="quantity"]').addEventListener('change', (e)=>{
        document.getElementById('sticky__quantity').value = e.target.value;
    });

    // console.log(document.referrer);

    // const ref = encodeURIComponent(document.referrer);
    // fetch(`/apps/sticky-cart-proxy/?ref=${ref}`, {
    //     method: 'GET',
    //     headers: { 'Accept': 'application/json' }
    // })
    // .then(res => {
    //     if (!res.ok) throw new Error(res.status);
    //     return res.json();
    // })
    // .then(data => console.log('proxy data', data))
    // .catch(err => console.error(err));

});
