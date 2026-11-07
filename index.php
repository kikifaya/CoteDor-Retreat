<?php
session_start();
require "db_connect.php";

$pageTitle = "Luxury Boutique Hotel on Praslin";
$currentPage = "home";

$sql = "SELECT * FROM rooms ORDER BY price ASC LIMIT 3";
$rooms = $conn->query($sql);

include "header.php";
?>

    <header class="hero" id="home">
        <img class="hero-poster" src="images/hero.jpg" alt="">
        <video autoplay muted loop playsinline poster="images/hero.jpg">
            <source src="video/hero.mp4" type="video/mp4">
        </video>

        <div class="hero-content">
            <p class="eyebrow">Anse Volbert &middot; Praslin &middot; Seychelles</p>
            <h1 class="hero-title"><span class="hero-line">A window to</span> <em class="hero-line">paradise</em></h1>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="rooms.php" class="btn btn-lux-light">Discover the rooms</a>
                <a href="booking.php" class="btn btn-lux">Book your stay</a>
            </div>
        </div>

        <a href="#story" class="scroll-cue">Scroll</a>
    </header>

    <section class="intro-dark" id="story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-5">
                    <p class="eyebrow">Since 2025</p>
                    <p class="intro-text reveal-words">
                        A family-run retreat on the island of Praslin, a two-minute walk from the soft
                        white sand of Côte d'Or Beach. Twenty-two rooms and apartments, a quiet pool,
                        Creole cooking and the slow rhythm of island life.
                    </p>

                    <div class="intro-stats reveal">
                        <div><strong>22</strong><span>Rooms &amp; suites</span></div>
                        <div><strong>150 m</strong><span>To the beach</span></div>
                        <div><strong>6 km</strong><span>Vallée de Mai</span></div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="collage">
                        <div class="photo photo-1" data-speed="0.05"><img src="images/intro-1.jpg" alt="A guest relaxing on the terrace among palm trees"></div>
                        <div class="photo photo-2" data-speed="0.16"><img src="images/intro-2.jpg" alt="Waves breaking on the shore seen from above"></div>
                        <div class="photo photo-3" data-speed="0.28"><img src="images/intro-3.jpg" alt="Palm trees by the sea"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="strip">
        <div class="strip-photos">
            <div class="photo" data-speed="0.04"><img src="images/strip-1.jpg" alt="Granite rocks on a Praslin beach"></div>
            <div class="photo" data-speed="0.14"><img src="images/strip-2.jpg" alt="Tropical breakfast with fresh fruit"></div>
            <div class="photo" data-speed="0.08"><img src="images/strip-3.jpg" alt="Turquoise bay with white sand"></div>
            <div class="photo" data-speed="0.18"><img src="images/strip-4.jpg" alt="A swimming pool lined with palm trees"></div>
        </div>
        <h2 class="strip-title reveal-words">An island<br>escape</h2>
    </section>

    <section class="section">
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-12 col-lg-7">
                    <p class="eyebrow">Stay</p>
                    <h2 class="section-title reveal-words">Rooms made for <em>slow</em> days</h2>
                </div>
                <div class="col-12 col-lg-5 text-lg-end">
                    <a href="rooms.php" class="btn btn-lux-outline">View all rooms</a>
                </div>
            </div>

            <div class="row g-4 g-lg-5">
                <?php
                if ($rooms && $rooms->num_rows > 0) {
                    while ($row = $rooms->fetch_assoc()) {
                ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card room-card h-100 reveal">
                            <div class="photo">
                                <img src="images/<?php echo htmlspecialchars($row["image"]); ?>" alt="<?php echo htmlspecialchars($row["room_name"]); ?>">
                            </div>
                            <div class="card-body">
                                <p class="room-meta"><?php echo htmlspecialchars($row["size_m2"]); ?> m² &middot; Up to <?php echo htmlspecialchars($row["max_guests"]); ?> guests</p>
                                <h3 class="card-title"><?php echo htmlspecialchars($row["room_name"]); ?></h3>
                                <p class="tagline"><?php echo htmlspecialchars($row["tagline"]); ?></p>
                                <p class="room-price">From $<?php echo number_format($row["price"], 0); ?> <small class="text-muted">/ night</small></p>
                                <a href="rooms.php#room-<?php echo $row["id"]; ?>" class="stretched-link" aria-label="View <?php echo htmlspecialchars($row["room_name"]); ?>"></a>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info">No rooms found.</div></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="feature-banner">
        <div class="banner-bg" data-speed="0.08" style="background-image: url('images/spa.jpg');"></div>
        <div class="container">
            <p class="eyebrow">Wellness</p>
            <h2 class="reveal-words">The spa</h2>
            <p class="mb-4">Massages with coconut oil and island herbs, in a quiet garden pavilion. Time slows down here.</p>
            <a href="experiences.html#spa" class="btn btn-lux-light">Explore</a>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-5">
                    <p class="eyebrow">Dining</p>
                    <h2 class="section-title reveal-words">Creole flavours, <em>island</em> evenings</h2>
                    <p class="lead-text">
                        Our restaurant cooks with the catch of the day, fresh fruit and spices from Praslin's
                        gardens, alongside international favourites. End the day at the bar with a cocktail
                        as the sun sets over the bay.
                    </p>
                    <a href="experiences.html#dining" class="btn btn-lux-outline mt-3">Explore dining</a>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row g-3 magazine">
                        <div class="col-7">
                            <div class="photo photo-tall"><img src="images/restaurant.jpg" alt="Dinner table set by the sea"></div>
                            <p class="caption">Grilled fish, fresh from the morning boats.</p>
                        </div>
                        <div class="col-5 pt-5">
                            <div class="photo" data-speed="0.12"><img src="images/story-1.jpg" alt="Cocktail at the bar"></div>
                            <p class="caption">Sunset at the bar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stack-section">
        <div class="container text-center">
            <p class="eyebrow">Experiences</p>
            <h2 class="section-title reveal-words">Spa, dining &amp; <em>island days</em></h2>
        </div>

        <a href="experiences.html" class="stack" id="experienceStack">
            <div class="stack-card stack-back photo"><img src="images/pool.jpg" alt="The hotel pool"></div>
            <div class="stack-card stack-middle photo"><img src="images/restaurant.jpg" alt="Dinner by the sea"></div>
            <div class="stack-card stack-front photo">
                <img src="images/spa.jpg" alt="The spa">
                <div class="stack-label">
                    <span class="stack-name">Côte d'Or Experiences</span>
                    <span class="stack-link">Discover</span>
                </div>
            </div>
        </a>
    </section>

    <section class="moments">
        <div id="momentsCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#momentsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#momentsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#momentsCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#momentsCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <div class="photo"><img src="images/moment-1.jpg" alt="Morning swim in clear water"></div>
                    <div class="carousel-caption">
                        <p>Moments to share</p>
                        <h5>Morning swims at Côte d'Or</h5>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <div class="photo"><img src="images/pool.jpg" alt="The hotel pool"></div>
                    <div class="carousel-caption">
                        <p>Moments to share</p>
                        <h5>Long afternoons by the pool</h5>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <div class="photo"><img src="images/moment-2.jpg" alt="Giant palm forest"></div>
                    <div class="carousel-caption">
                        <p>Moments to share</p>
                        <h5>Walks through the Vallée de Mai</h5>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <div class="photo"><img src="images/moment-3.jpg" alt="Sunset over the ocean"></div>
                    <div class="carousel-caption">
                        <p>Moments to share</p>
                        <h5>Sunsets over the Indian Ocean</h5>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#momentsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#momentsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="section bg-sand">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-6 order-lg-2">
                    <p class="eyebrow">Location</p>
                    <h2 class="section-title reveal-words">Everything Praslin, <em>close by</em></h2>
                    <ul class="distance-list mt-4">
                        <li><strong>Côte d'Or Beach</strong><span>150 m</span></li>
                        <li><strong>Praslin ferry jetty</strong><span>3 km</span></li>
                        <li><strong>Vallée de Mai</strong><span>6 km</span></li>
                        <li><strong>Anse Lazio</strong><span>7 km</span></li>
                    </ul>
                </div>
                <div class="col-12 col-lg-6 order-lg-1">
                    <p class="quote reveal-words">&ldquo;The kind of place where the only plan is the tide.&rdquo;</p>
                    <div class="photo mt-4" style="aspect-ratio: 16 / 11;"><img src="images/beach.jpg" alt="Côte d'Or Beach at Anse Volbert"></div>
                    <p class="caption">Côte d'Or Beach, Anse Volbert.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="story-mark">
        <span class="mark-letter" data-speed="0.1">C</span>
        <div class="container">
            <p class="story-line reveal-words">Côte d'Or Retreat, a family-run home on Praslin.<br>Made for slow island days.</p>
        </div>
        <span class="mark-year" data-speed="-0.06">2025</span>
    </section>

    <section class="marquee" aria-label="Places around Côte d'Or Retreat">
        <div class="marquee-track">
            <span>Praslin</span><span>Côte d'Or Beach</span><span>Anse Lazio</span><span>Vallée de Mai</span><span>La Digue</span><span>Curieuse</span>
            <span>Praslin</span><span>Côte d'Or Beach</span><span>Anse Lazio</span><span>Vallée de Mai</span><span>La Digue</span><span>Curieuse</span>
        </div>
    </section>

    <section class="cta-band">
        <div class="container">
            <p class="eyebrow">Your stay</p>
            <h2 class="section-title reveal-words">Ready for the island?</h2>
            <p class="mb-4">Choose your room, pick your dates and we'll take care of the rest.</p>
            <a href="booking.php" class="btn btn-lux-light">Book now</a>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
