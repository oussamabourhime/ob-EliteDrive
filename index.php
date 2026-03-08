<?php 
require_once 'setup.php'; 
include 'includes/header.php'; 
require_once 'includes/db.php';
?>

<!-- Fleet CSS link instead of putting it inline -->
<link rel="stylesheet" href="assets/css/fleet.css">
<link rel="stylesheet" href="assets/css/booking.css">
<link rel="stylesheet" href="assets/css/contact.css">
<!-- CSS specifically for Homepage components -->
<style>
/* =========================================================================
   HERO SECTION
   ========================================================================= */
.hero {
  position: relative;
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.hero-video-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -2;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(17,17,17,0.8) 0%, rgba(17,17,17,0.4) 50%, rgba(17,17,17,0.9) 100%);
  z-index: -1;
}

.hero-content {
  text-align: center;
  color: var(--clr-white);
  max-width: 800px;
  padding: 0 20px;
  z-index: 1;
}

.hero-subtitle {
  font-size: 1.25rem;
  font-weight: 500;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 20px;
  color: #ccc;
  display: block;
}

.hero-title {
  font-size: clamp(3rem, 6vw, 5rem);
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 30px;
  text-shadow: 0 5px 15px rgba(0,0,0,0.5);
}

.hero-title span {
  color: var(--clr-primary-red);
}

/* Animations for Hero Content */
.hero-content .fade-in-up {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 1s ease forwards;
}

.hero-content .delay-1 { animation-delay: 0.3s; }
.hero-content .delay-2 { animation-delay: 0.6s; }
.hero-content .delay-3 { animation-delay: 0.9s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Scroll Down Indicator */
.scroll-indicator {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  color: var(--clr-white);
  opacity: 0.7;
  transition: opacity var(--transition-fast);
  cursor: pointer;
  z-index: 10;
}

.scroll-indicator:hover {
  opacity: 1;
}

.mouse {
  width: 26px;
  height: 42px;
  border: 2px solid var(--clr-white);
  border-radius: 15px;
  position: relative;
  margin-bottom: 10px;
}

.mouse::before {
  content: '';
  position: absolute;
  top: 8px;
  left: 50%;
  transform: translateX(-50%);
  width: 4px;
  height: 8px;
  background-color: var(--clr-white);
  border-radius: 2px;
  animation: scrollWheel 2s infinite ease-in-out;
}

@keyframes scrollWheel {
  0% { transform: translate(-50%, 0); opacity: 1; }
  100% { transform: translate(-50%, 15px); opacity: 0; }
}
</style>

<!-- Hero Section -->
<section id="home" class="hero">
    <video class="hero-video-bg" autoplay muted loop playsinline poster="assets/videos/hero-poster.mp4">
        <source src="assets/videos/hero-bg.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <span class="hero-subtitle fade-in-up delay-1">Premium Nationwide Car Rental Experience.</span>
        <h1 class="hero-title fade-in-up delay-2">Drive Anywhere in <span>Morocco</span></h1>
        <a href="#fleet" class="btn btn-primary fade-in-up delay-3">Explore Fleet</a>
    </div>

    <a href="#about" class="scroll-indicator">
        <div class="mouse"></div>
        <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Scroll Down</span>
    </a>
</section>

<!-- Fleet Section -->
<section id="fleet" class="section">
    <div class="section-title animate-on-scroll">
        <h2>Our Premium <span>Fleet</span></h2>
        <p style="color: var(--clr-gray-dark); max-width: 600px; margin: 0 auto;">Select your perfect ride from our exclusively curated nationwide collection of luxury vehicles.</p>
    </div>

    <div class="fleet-grid">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM cars ORDER BY id ASC");
            $cars = $stmt->fetchAll();
            $delay = 100;

            foreach ($cars as $car) {
                echo '<div class="car-card animate-on-scroll delay-'.($delay).'">';
                echo '    <div class="car-card-img">';
                echo '        <img src="'.htmlspecialchars($car['image_url']).'" alt="'.htmlspecialchars($car['brand'].' '.$car['model']).'" loading="lazy">';
                echo '        <div class="car-price-badge">'.number_format($car['price_per_day']).' MAD / Day</div>';
                echo '    </div>';
                echo '    <div class="car-card-content">';
                echo '        <div class="car-brand">'.htmlspecialchars($car['brand']).'</div>';
                echo '        <h3 class="car-model">'.htmlspecialchars($car['model']).'</h3>';
                echo '        <div class="car-specs">';
                echo '            <div class="spec-item"><i class="fas fa-calendar-alt"></i> '.htmlspecialchars($car['year']).'</div>';
                echo '            <div class="spec-item"><i class="fas fa-gas-pump"></i> '.htmlspecialchars($car['fuel']).'</div>';
                echo '            <div class="spec-item"><i class="fas fa-cogs"></i> '.htmlspecialchars($car['transmission']).'</div>';
                echo '            <div class="spec-item"><i class="fas fa-car-side"></i> '.htmlspecialchars($car['category']).'</div>';
                echo '        </div>';
                echo '        <div class="car-card-footer">';
                echo '            <a href="#booking" class="btn btn-primary btn-block" onclick="selectCar('.$car['id'].')">Book Now</a>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
                
                $delay += 100;
                if($delay > 300) $delay = 100; // Reset delay pattern
            }
        } catch (\PDOException $e) {
            echo '<p>Error loading fleet data. Database setup might still be running.</p>';
        }
        ?>
    </div>
</section>

<!-- Booking Section -->
<section id="booking" class="section booking-section">
    <div class="booking-container animate-on-scroll">
        <div class="booking-info">
            <h2>Book Your <span>Experience</span></h2>
            <p>Fill out the form to reserve your premium vehicle. We deliver anywhere in Morocco.</p>
            
            <div class="booking-summary" id="bookingSummary">
                <div class="summary-row">
                    <span>Selected Car:</span>
                    <strong id="summaryCar">-</strong>
                </div>
                <div class="summary-row">
                    <span>Duration:</span>
                    <strong id="summaryDays">0 Days</strong>
                </div>
                <div class="summary-row total-row">
                    <span>Total Price:</span>
                    <strong class="animated-price" id="summaryPrice">0 MAD</strong>
                </div>
            </div>
            
            <div class="success-message" id="successMessage" style="display:none;">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--clr-primary-green); margin-bottom: 20px;"></i>
                <h3>Booking Confirmed!</h3>
                <p>Your reservation Request has been received. Our team will contact you shortly.</p>
            </div>
        </div>

        <div class="booking-form-wrapper" id="bookingFormWrapper">
            <form id="bookingForm" class="booking-form">
                
                <div class="form-group floating">
                    <select id="car_id" name="car_id" required class="form-control" onchange="calculatePrice()">
                        <option value="" disabled selected>Select a Vehicle</option>
                        <?php
                        if(isset($cars)) {
                            foreach($cars as $c) {
                                echo '<option value="'.$c['id'].'" data-price="'.$c['price_per_day'].'" data-name="'.htmlspecialchars($c['brand'].' '.$c['model']).'">'.htmlspecialchars($c['brand'].' '.$c['model']).' - '.number_format($c['price_per_day']).' MAD/day</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <input type="text" id="full_name" name="full_name" class="form-control" required placeholder=" ">
                        <label for="full_name">Full Name</label>
                    </div>
                    <div class="form-group floating">
                        <input type="tel" id="phone" name="phone" class="form-control" required placeholder=" ">
                        <label for="phone">Phone Number</label>
                    </div>
                </div>
                
                <div class="form-group floating">
                    <input type="email" id="email" name="email" class="form-control" required placeholder=" ">
                    <label for="email">Email Address</label>
                </div>

                <div class="form-row">
                    <div class="form-group floating">
                        <input type="text" id="pickup_location" name="pickup_location" class="form-control" required placeholder=" ">
                        <label for="pickup_location">Pickup City/Location</label>
                    </div>
                    <div class="form-group floating">
                        <input type="text" id="dropoff_location" name="dropoff_location" class="form-control" required placeholder=" ">
                        <label for="dropoff_location">Drop-off City/Location</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group floating focused">
                        <input type="datetime-local" id="pickup_date" name="pickup_date" class="form-control" required onchange="calculatePrice()">
                        <label class="active" for="pickup_date">Pickup Date</label>
                    </div>
                    <div class="form-group floating focused">
                        <input type="datetime-local" id="dropoff_date" name="dropoff_date" class="form-control" required onchange="calculatePrice()">
                        <label class="active" for="dropoff_date">Drop-off Date</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                    <span>Confirm Booking</span>
                    <div class="btn-loader"></div>
                </button>
                <div id="formError" class="form-error"></div>
            </form>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section contact-section">
    <div class="contact-container animate-on-scroll">
        <div class="contact-info-block">
            <h3>Get In <span>Touch</span></h3>
            <p>Have questions about our fleet or special requirements? Our nationwide support team is ready to assist you 24/7.</p>
            
            <div class="contact-detail">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-text">
                    <h4>Location</h4>
                    <p>Nationwide Service, Morocco</p>
                </div>
            </div>
            
            <div class="contact-detail">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="contact-text">
                    <h4>Phone</h4>
                    <p>+212 69180-4407</p>
                </div>
            </div>
            
            <div class="contact-detail">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div class="contact-text">
                    <h4>Email</h4>
                    <p>bourhimeoussama@hotmail.com</p>
                </div>
            </div>
        </div>

        <div class="contact-form-block">
            <h3>Send a <span>Message</span></h3>
            <p>We'll respond to your inquiry within 2 hours.</p>
            
            <form id="contactForm" class="contact-form">
                <div class="form-group floating">
                    <input type="text" id="contact_name" name="name" class="form-control" required placeholder=" ">
                    <label for="contact_name">Your Name</label>
                </div>
                
                <div class="form-group floating">
                    <input type="email" id="contact_email" name="email" class="form-control" required placeholder=" ">
                    <label for="contact_email">Email Address</label>
                </div>
                
                <div class="form-group floating">
                    <textarea id="contact_message" name="message" class="form-control" required placeholder=" "></textarea>
                    <label for="contact_message">Your Message</label>
                </div>
                
                <button type="submit" class="btn btn-primary" id="contactSubmitBtn" style="position: relative; padding: 16px 32px;">
                    <span>Send Message</span>
                    <div class="btn-loader"></div>
                </button>
                <div id="contactFormResponse"></div>
            </form>
        </div>
    </div>
</section>

<script>
function selectCar(id) {
    const selector = document.getElementById('car_id');
    if (selector) {
        selector.value = id;
        calculatePrice();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
