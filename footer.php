    <footer class="site-footer" id="newsletter">
        <div class="container">
            <div class="row g-5">
                <div class="col-12 col-lg-5">
                    <p class="eyebrow">Stay in touch</p>
                    <h3 class="footer-title">Island news, seasonal offers and quiet escapes.</h3>

                    <form class="newsletter-form mt-4" action="subscribe.php" method="POST">
                        <input type="email" class="form-control" name="email" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-lux">Subscribe</button>
                    </form>
                </div>

                <div class="col-6 col-lg-2 offset-lg-1">
                    <h6 class="footer-heading">Explore</h6>
                    <ul class="footer-links">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="rooms.php">Rooms</a></li>
                        <li><a href="experiences.html">Experiences</a></li>
                        <li><a href="booking.php">Book a stay</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="footer-heading">Guests</h6>
                    <ul class="footer-links">
                        <li><a href="login.php">Log in</a></li>
                        <li><a href="register.php">Create account</a></li>
                        <li><a href="my_bookings.php">My bookings</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>

                <div class="col-12 col-lg-2">
                    <h6 class="footer-heading">Visit</h6>
                    <p class="footer-text">
                        Anse Volbert, Praslin<br>
                        Seychelles<br>
                        +248 4 000 000<br>
                        stay@cotedorretreat.com
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; <?php echo date("Y"); ?> Côte d'Or Retreat. All rights reserved.</span>
                <span>Check-in 12:00 &middot; Check-out 10:00</span>
            </div>
        </div>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
