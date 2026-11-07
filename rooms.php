<?php
session_start();
require "db_connect.php";

$pageTitle = "Rooms & Apartments";
$currentPage = "rooms";

$allowedTypes = ["Double", "Self-Catering", "Apartment"];
$type = "";
$sort = "ASC";

if (isset($_GET["type"]) && in_array($_GET["type"], $allowedTypes)) {
    $type = $_GET["type"];
}

if (isset($_GET["sort"]) && $_GET["sort"] == "DESC") {
    $sort = "DESC";
}

if ($type != "") {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_type = ? ORDER BY price " . $sort);
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $rooms = $stmt->get_result();
} else {
    $sql = "SELECT * FROM rooms ORDER BY price " . $sort;
    $rooms = $conn->query($sql);
}

$roomPhotos = [];
$photoResult = $conn->query("SELECT * FROM room_photos ORDER BY room_id, id");

if ($photoResult && $photoResult->num_rows > 0) {
    while ($photo = $photoResult->fetch_assoc()) {
        $roomPhotos[$photo["room_id"]][] = $photo;
    }
}

$modalAction = "book";

include "header.php";
?>

    <section class="page-hero" style="background-image: url('images/room-double.jpg');">
        <div class="container">
            <p class="eyebrow">Rooms &amp; apartments</p>
            <h1>Your space by <em>the sea</em></h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-12 col-lg-8">
                    <p class="eyebrow">Filter</p>
                    <div class="filter-pills">
                        <a href="rooms.php?sort=<?php echo $sort; ?>" class="btn btn-lux-outline <?php if ($type == "") { echo "active"; } ?>">All</a>
                        <?php
                        foreach ($allowedTypes as $item) {
                            $activeClass = ($type == $item) ? "active" : "";
                            echo '<a href="rooms.php?type=' . urlencode($item) . '&sort=' . $sort . '" class="btn btn-lux-outline ' . $activeClass . '">' . htmlspecialchars($item) . '</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <form action="rooms.php" method="GET">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                        <label for="sort" class="form-label">Sort by price</label>
                        <select class="form-select" id="sort" name="sort" onchange="this.form.submit()">
                            <option value="ASC" <?php if ($sort == "ASC") { echo "selected"; } ?>>Lowest price first</option>
                            <option value="DESC" <?php if ($sort == "DESC") { echo "selected"; } ?>>Highest price first</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row g-4 g-lg-5">
                <?php
                if ($rooms && $rooms->num_rows > 0) {
                    while ($row = $rooms->fetch_assoc()) {
                        $roomId = $row["id"];
                ?>
                    <div class="col-12 col-md-6 col-lg-4" id="room-<?php echo $roomId; ?>">
                        <div class="card room-card h-100 reveal" data-bs-toggle="modal" data-bs-target="#roomModal<?php echo $roomId; ?>">
                            <div class="photo">
                                <img src="images/<?php echo htmlspecialchars($row["image"]); ?>" alt="<?php echo htmlspecialchars($row["room_name"]); ?>">
                                <?php if (isset($roomPhotos[$roomId])) { ?><span class="photo-count"><?php echo count($roomPhotos[$roomId]) + 1; ?> photos</span><?php } ?>
                            </div>
                            <div class="card-body">
                                <p class="room-meta"><?php echo htmlspecialchars($row["size_m2"]); ?> m² &middot; Up to <?php echo htmlspecialchars($row["max_guests"]); ?> guests</p>
                                <h3 class="card-title"><?php echo htmlspecialchars($row["room_name"]); ?></h3>
                                <p class="tagline"><?php echo htmlspecialchars($row["tagline"]); ?></p>
                                <p class="room-price mb-3">From $<?php echo number_format($row["price"], 0); ?> <small class="text-muted">/ night</small></p>
                                <button type="button" class="btn btn-lux-outline">Photos &amp; details</button>
                            </div>
                        </div>
                    </div>

                    <?php
                    $room = $row;
                    include "room_modal.php";
                    ?>
                <?php
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info">No rooms found for this filter.</div></div>';
                }
                ?>
            </div>

            <div class="row mt-5 pt-4 g-4">
                <div class="col-12 col-md-4">
                    <p class="room-meta mb-1">Breakfast</p>
                    <p class="room-price mb-0">$22 <small class="text-muted">per person / night</small></p>
                </div>
                <div class="col-12 col-md-4">
                    <p class="room-meta mb-1">Half board</p>
                    <p class="room-price mb-0">$78 <small class="text-muted">per person / night</small></p>
                </div>
                <div class="col-12 col-md-4">
                    <p class="room-meta mb-1">Check-in &middot; Check-out</p>
                    <p class="room-price mb-0">12:00 &middot; 10:00</p>
                </div>
            </div>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
