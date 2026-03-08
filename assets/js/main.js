document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================================
       PRELOADER
       ========================================================================= */
    const preloader = document.getElementById('preloader');
    window.addEventListener('load', () => {
        setTimeout(() => {
            preloader.classList.add('fade-out');
            setTimeout(() => { preloader.style.display = 'none'; }, 500);
        }, 800); // slight delay to show the animation
    });

    /* =========================================================================
       STICKY NAVBAR & SCROLL TRANSITIONS
       ========================================================================= */
    const navbar = document.getElementById('navbar');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    /* =========================================================================
       MOBILE MENU TOGGLE
       ========================================================================= */
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    mobileMenuBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        const icon = mobileMenuBtn.querySelector('i');
        if (navLinks.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });

    // Close mobile menu on link click
    const links = document.querySelectorAll('.nav-links a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        });
    });

    /* =========================================================================
       INTERSECTION OBSERVER (ANIMATE ON SCROLL)
       ========================================================================= */
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const animateElements = document.querySelectorAll('.animate-on-scroll');
    animateElements.forEach(el => scrollObserver.observe(el));

    /* =========================================================================
       PARALLAX EFFECT FOR HERO
       ========================================================================= */
    const heroContent = document.querySelector('.hero-content');
    window.addEventListener('scroll', () => {
        if (heroContent && window.scrollY < window.innerHeight) {
            const scrollPos = window.scrollY;
            heroContent.style.transform = `translateY(${scrollPos * 0.4}px)`;
            heroContent.style.opacity = 1 - (scrollPos / window.innerHeight) * 1.5;
        }
    }); // Fixed missing brace here

    // (Intersection observer part ends here)

    /* =========================================================================
       BOOKING FORM LOGIC (PRICE CALC & SUBMISSION)
       ========================================================================= */
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        // Form submission handling
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const pickup = new Date(document.getElementById('pickup_date').value);
            const dropoff = new Date(document.getElementById('dropoff_date').value);
            
            if (pickup >= dropoff) {
                showError('Drop-off date must be after pickup date.');
                return;
            }

            // UI feedback
            const btn = document.getElementById('submitBtn');
            const btnText = btn.querySelector('span');
            const btnLoader = btn.querySelector('.btn-loader');
            
            btnText.style.opacity = '0';
            btnLoader.style.display = 'block';
            btn.disabled = true;

            const formData = new FormData(bookingForm);

            fetch('api/submit_booking.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Success Animation
                    document.getElementById('bookingFormWrapper').style.display = 'none';
                    document.getElementById('bookingSummary').style.display = 'none';
                    const successMsg = document.getElementById('successMessage');
                    successMsg.style.display = 'block';
                    successMsg.classList.add('fade-in-up'); // Re-using our utility class
                } else {
                    showError(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(err => {
                showError('Network error. Please try again.');
            })
            .finally(() => {
                btnText.style.opacity = '1';
                btnLoader.style.display = 'none';
                btn.disabled = false;
            });
        });
    }

    function showError(msg) {
        const errDiv = document.getElementById('formError');
        errDiv.textContent = msg;
        errDiv.style.display = 'block';
        setTimeout(() => { errDiv.style.display = 'none'; }, 4000);
    }

    /* =========================================================================
       CONTACT FORM SUBMISSION
       ========================================================================= */
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('contactSubmitBtn');
            const btnText = btn.querySelector('span');
            const btnLoader = btn.querySelector('.btn-loader');
            
            btnText.style.opacity = '0';
            btnLoader.style.display = 'block';
            btn.disabled = true;

            const formData = new FormData(contactForm);

            fetch('api/submit_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const formResponse = document.getElementById('contactFormResponse');
                if(data.success) {
                    contactForm.reset();
                    formResponse.innerHTML = '<div style="color: var(--clr-primary-green); margin-top: 15px; font-weight: 500;"><i class="fas fa-check-circle"></i> Message sent successfully! We will get back to you soon.</div>';
                } else {
                    formResponse.innerHTML = `<div style="color: var(--clr-primary-red); margin-top: 15px; font-weight: 500;"><i class="fas fa-exclamation-circle"></i> ${data.message || 'Error occurred.'}</div>`;
                }
                setTimeout(() => { formResponse.innerHTML = ''; }, 5000);
            })
            .catch(err => {
                const formResponse = document.getElementById('contactFormResponse');
                formResponse.innerHTML = '<div style="color: var(--clr-primary-red); margin-top: 15px; font-weight: 500;"><i class="fas fa-exclamation-circle"></i> Network error. Please try again.</div>';
                setTimeout(() => { formResponse.innerHTML = ''; }, 5000);
            })
            .finally(() => {
                btnText.style.opacity = '1';
                btnLoader.style.display = 'none';
                btn.disabled = false;
            });
        });
    }
});

/* Global Helper to Calculate Price */
window.calculatePrice = function() {
    const sel = document.getElementById('car_id');
    const pickup = document.getElementById('pickup_date').value;
    const dropoff = document.getElementById('dropoff_date').value;
    
    if(!sel.value) return;

    const selectedOption = sel.options[sel.selectedIndex];
    const pricePerDay = parseFloat(selectedOption.getAttribute('data-price'));
    const carName = selectedOption.getAttribute('data-name');
    
    document.getElementById('summaryCar').textContent = carName;

    if(pickup && dropoff) {
        const d1 = new Date(pickup);
        const d2 = new Date(dropoff);
        
        let diffTime = d2.getTime() - d1.getTime();
        if(diffTime > 0) {
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            // Minimum 1 day rental
            diffDays = Math.max(1, diffDays);
            const total = diffDays * pricePerDay;
            
            document.getElementById('summaryDays').textContent = diffDays + ' Day' + (diffDays>1?'s':'');
            
            const priceEl = document.getElementById('summaryPrice');
            const newText = total.toLocaleString() + ' MAD';
            
            // Animate price update if changed
            if(priceEl.textContent !== newText) {
                priceEl.textContent = newText;
                priceEl.classList.remove('price-pulse');
                void priceEl.offsetWidth; // trigger reflow
                priceEl.classList.add('price-pulse');
            }
        } else {
            document.getElementById('summaryDays').textContent = 'Invalid Dates';
            document.getElementById('summaryPrice').textContent = '0 MAD';
        }
    }
};
