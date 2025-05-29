// Carousel horizontal scroll with mouse drag & touch
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.movies-carousel');
    if (carousel) {
        let isDown = false;
        let startX;
        let scrollLeft;

        carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            carousel.classList.add('dragging');
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('mouseleave', () => {
            isDown = false;
            carousel.classList.remove('dragging');
        });

        carousel.addEventListener('mouseup', () => {
            isDown = false;
            carousel.classList.remove('dragging');
        });

        carousel.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2; // scroll-fast
            carousel.scrollLeft = scrollLeft - walk;
        });

        // Touch support
        carousel.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('touchend', () => {
            isDown = false;
        });

        carousel.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });
    }

    // Password show/hide toggle for forms
    document.querySelectorAll('.form-group input[type="password"]').forEach(function(input) {
        const wrapper = input.parentElement;
        if (!wrapper.querySelector('.toggle-password')) {
            const toggle = document.createElement('span');
            toggle.className = 'toggle-password';
            toggle.style.cssText = 'margin-left:8px;cursor:pointer;color:#ff9800;font-size:1.1em;';
            toggle.innerHTML = '<i class="fa fa-eye"></i>';
            toggle.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    toggle.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
            wrapper.appendChild(toggle);
        }
    });

    // Star rating interaction for review forms
    const ratingInputs = document.querySelectorAll('.review-form .star-rating input[type="radio"]');
    if (ratingInputs.length) {
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const stars = this.closest('.star-rating').querySelectorAll('label');
                stars.forEach(label => label.classList.remove('selected'));
                for (let i = 0; i < this.value; i++) {
                    stars[i].classList.add('selected');
                }
            });
        });
    }

    // Admin: Confirm delete movie
    document.querySelectorAll('.admin-actions .delete-movie').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to permanently delete this movie?')) {
                e.preventDefault();
            }
        });
    });

    // Basic form validation feedback
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let valid = true;
            form.querySelectorAll('input[required], textarea[required]').forEach(function(input) {
                if (!input.value.trim()) {
                    input.style.borderColor = '#ff5252';
                    valid = false;
                } else {
                    input.style.borderColor = '';
                }
            });
            if (!valid) {
                e.preventDefault();
            }
        });
    });
});