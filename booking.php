<?php
session_start();
require "auth_check.php";
require "db_connect.php";

$pageTitle = "Book your stay";
$currentPage = "booking";

$successMessage = "";
$errorMessage = "";
$mealPlans = ["Room only", "Breakfast", "Half board"];

$roomId = 0;
$checkIn = "";
$checkOut = "";
$guests = 2;
$mealPlan = "Breakfast";
$phone = "";
$specialRequest = "";

if (isset($_GET["room_id"])) {
    $roomId = (int) $_GET["room_id"];
}

$userStmt = $conn->prepare("SELECT phone FROM users WHERE id = ?");
$userStmt->bind_param("i", $_SESSION["user_id"]);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($userRow = $userResult->fetch_assoc()) {
    $phone = $userRow["phone"];
}
$userStmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomId = (int) $_POST["room_id"];
    $checkIn = $_POST["check_in"];
    $checkOut = $_POST["check_out"];
    $guests = (int) $_POST["guests"];
    $mealPlan = $_POST["meal_plan"];
    $phone = trim($_POST["phone"]);
    $specialRequest = trim($_POST["special_request"]);
    $today = date("Y-m-d");

    $roomStmt = $conn->prepare("SELECT room_name, price, max_guests FROM rooms WHERE id = ?");
    $roomStmt->bind_param("i", $roomId);
    $roomStmt->execute();
    $room = $roomStmt->get_result()->fetch_assoc();
    $roomStmt->close();

    if (!$room) {
        $errorMessage = "Please choose a room.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $checkIn) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $checkOut)) {
        $errorMessage = "Please choose your check-in and check-out dates.";
    } elseif ($checkIn < $today) {
        $errorMessage = "The check-in date can't be in the past.";
    } elseif ($checkOut <= $checkIn) {
        $errorMessage = "The check-out date must be after the check-in date.";
    } elseif ($guests < 1 || $guests > $room["max_guests"]) {
        $errorMessage = "The " . $room["room_name"] . " allows 1 to " . $room["max_guests"] . " guests.";
    } elseif (!in_array($mealPlan, $mealPlans)) {
        $errorMessage = "Please choose a meal plan.";
    } elseif (!preg_match("/^[0-9+ ]{7,20}$/", $phone)) {
        $errorMessage = "Please enter a valid phone number.";
    } else {
        $nights = (int) round((strtotime($checkOut) - strtotime($checkIn)) / 86400);

        if ($mealPlan == "Breakfast") {
            $mealPrice = 22;
        } elseif ($mealPlan == "Half board") {
            $mealPrice = 78;
        } else {
            $mealPrice = 0;
        }

        $totalPrice = ($nights * $room["price"]) + ($nights * $guests * $mealPrice);
        $userId = $_SESSION["user_id"];

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, meal_plan, phone, special_request, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iississsd", $userId, $roomId, $checkIn, $checkOut, $guests, $mealPlan, $phone, $specialRequest, $totalPrice);

        if ($stmt->execute()) {
            $successMessage = "Thank you! Your booking for the " . $room["room_name"] . " (" . $nights . " nights, $" . number_format($totalPrice, 2) . ") has been received. Its status is Pending until the hotel confirms it.";
            $checkIn = "";
            $checkOut = "";
            $specialRequest = "";
        } else {
            $errorMessage = "Sorry, your booking could not be saved. Please try again.";
        }

        $stmt->close();
    }
}

$rooms = $conn->query("SELECT id, room_name, price, max_guests FROM rooms ORDER BY price ASC");

include "header.php";
?>

    <section class="page-hero" style="background-image: url('images/pool.jpg');">
        <div class="container">
            <p class="eyebrow">Reservations</p>
            <h1>Book your <em>stay</em></h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row g-5">
                <div class="col-12 col-lg-7">
                    <div class="form-panel">
                        <div class="progress-label">
                            <span>Booking progress</span>
                            <span id="progressText">0%</span>
                        </div>
                        <div class="progress mb-4" role="progressbar" aria-label="Booking form progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" id="bookingProgress" style="width: 0%"></div>
                        </div>

                        <?php
                        if ($successMessage != "") {
                            echo '<div class="alert alert-success">' . htmlspecialchars($successMessage) . ' <a href="my_bookings.php" class="alert-link">View my bookings</a></div>';
                        }
                        if ($errorMessage != "") {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($errorMessage) . '</div>';
                        }
                        ?>
                        <div id="formErrors"></div>

                        <form id="bookingForm" action="booking.php" method="POST" onsubmit="return validateBooking(event)" novalidate>
                            <div class="mb-3">
                                <label for="room_id" class="form-label">Room</label>
                                <select class="form-select" id="room_id" name="room_id" onchange="showRoomInfo()" required>
                                    <option value="" data-price="0" data-guests="0">Choose a room</option>
                                    <?php
                                    if ($rooms && $rooms->num_rows > 0) {
                                        while ($row = $rooms->fetch_assoc()) {
                                            $selected = ($row["id"] == $roomId) ? "selected" : "";
                                            echo '<option value="' . $row["id"] . '" data-price="' . $row["price"] . '" data-guests="' . $row["max_guests"] . '" ' . $selected . '>' . htmlspecialchars($row["room_name"]) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="form-text" id="roomInfo"></div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="check_in" class="form-label">Check-in</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" min="<?php echo date("Y-m-d"); ?>" value="<?php echo htmlspecialchars($checkIn); ?>" onchange="calculateTotal()" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="check_out" class="form-label">Check-out</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" min="<?php echo date("Y-m-d"); ?>" value="<?php echo htmlspecialchars($checkOut); ?>" onchange="calculateTotal()" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="guests" class="form-label">Guests</label>
                                    <input type="number" class="form-control" id="guests" name="guests" min="1" max="4" value="<?php echo htmlspecialchars($guests); ?>" onchange="calculateTotal()" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="meal_plan" class="form-label">Meal plan</label>
                                    <select class="form-select" id="meal_plan" name="meal_plan" onchange="calculateTotal()" required>
                                        <?php
                                        foreach ($mealPlans as $plan) {
                                            $selected = ($plan == $mealPlan) ? "selected" : "";
                                            echo '<option value="' . $plan . '" ' . $selected . '>' . $plan . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+248 2 500 000" value="<?php echo htmlspecialchars($phone); ?>" onchange="updateProgress()" required>
                            </div>

                            <div class="mb-4">
                                <label for="special_request" class="form-label">Special requests (optional)</label>
                                <textarea class="form-control" id="special_request" name="special_request" rows="3" placeholder="Arrival time, celebrations, dietary needs..."><?php echo htmlspecialchars($specialRequest); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-lux w-100">Confirm booking</button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="summary-panel">
                        <p class="eyebrow">Your stay</p>
                        <h3>Booking summary</h3>
                        <div class="summary-row"><span>Room</span><span id="summaryRoom">Not selected</span></div>
                        <div class="summary-row"><span>Nights</span><span id="summaryNights">0</span></div>
                        <div class="summary-row"><span>Guests</span><span id="summaryGuests">0</span></div>
                        <div class="summary-row"><span>Room cost</span><span id="summaryRoomCost">$0.00</span></div>
                        <div class="summary-row"><span>Meals</span><span id="summaryMeals">-</span></div>
                        <div class="summary-total" id="summaryTotal">$0.00</div>
                        <p class="small mt-2 mb-0">Breakfast $22 and half board $78 per person, per night. Check-in from 12:00, check-out by 10:00.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
