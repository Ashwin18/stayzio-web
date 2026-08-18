<footer class="stayzio-footer">
    <div class="stayzio-footer-inner">
        <div class="stayzio-footer-brand">
            <a href="{{ route('index') }}" class="stayzio-footer-logo" aria-label="StayZio Home">
                <img src="{{ asset('stayzio/images/stayzio-logo.png') }}" alt="StayZio Logo">
            </a>

            <p>Hotels made simple with flexible short-stay booking for the modern traveler.</p>

            <div class="stayzio-socials" aria-label="Social links">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="X"><i class="fab fa-x-twitter"></i></a>
            </div>
        </div>

        <div class="stayzio-footer-col">
            <h5>Quick Links</h5>
            <a href="#">Why StayZio</a>
            <a href="#">Stay Options</a>
            <a href="#">Corporate Bookings</a>
            <a href="#">FAQ</a>
            <a href="#">App Download</a>
        </div>

        <div class="stayzio-footer-col">
            <h5>Company</h5>
            <a href="#">Contact</a>
            <a href="{{ route('vendor.signup') }}">Partner With Us</a>
            <a href="#">About Us</a>
            <a href="#">Careers</a>
        </div>

        <div class="stayzio-footer-col">
            <h5>Support</h5>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms &amp; Conditions</a>
            <a href="#">Help Center</a>
            <a href="#">Cancellation Policy</a>
        </div>
    </div>
</footer>

<div id="toast-message" style="display:none;"></div>
