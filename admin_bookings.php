<?php
session_start();
require "admin_check.php";
require "db_connect.php";

$pageTitle = "Bookings";
$currentPage = "admin";
$adminTab = "bookings";
$solidNav = true;

$successMessage = "";
$errorMessage = "";
$statuses = ["Pending", "Confirmed", "Cancelled"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingId = (int) $_POST["booking_id"];
    $action = $_POST["action"];

    if ($action == "confirm" || $action == "cancel") {
        $newStatus = ($action == "confirm") ? "Confirmed" : "Cancelled";

        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $bookingId);

        if ($stmt->execute()) {
            $successMessage = "Booking #" . $bookingId . " is now " . $newStatus . ".";
        } else {
            $errorMessage = "Unable to update booking #" . $bookingId . ".";
        }

        $stmt->close();
    } elseif ($action == "delete") {
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $bookingId);

        if ($stmt->execute()) {
            $successMessage = "Booking #" . $bookingId . " has been deleted.";
        } else {
            $errorMessage = "Unable to delete booking #" . $bookingId . ".";
        }

        $stmt->close();
    }
}

$filter = "";
if (isset($_GET["status"]) && in_array($_GET["status"], $statuses)) {
    $filter = $_GET["status"];
}

$sql = "SELECT bookings.*, users.full_name, users.email, rooms.room_name FROM bookings JOIN users ON bookings.user_id = users.id JOIN rooms ON bookings.room_id = rooms.id";

if ($filter != "") {
    $stmt = $conn->prepare($sql . " WHERE bookings.status = ? ORDER BY bookings.check_in ASC");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $bookings = $stmt->get_result();
} else {
    $bookings = $conn->query($sql . " ORDER BY bookings.check_in ASC");
}

include "header.php";
?>

    <section class="admin-wrap">
        <div class="container">
            <?php include "admin_nav.php"; ?>

            <?php
            if ($successMessage != "") {
                echo '<div class="alert alert-success">' . htmlspecialchars($successMessage) . '</div>';
            }
            if ($errorMessage != "") {
                echo '<div class="alert alert-danger">' . htmlspecialchars($errorMessage) . '</div>';
            }
            ?>

            <div class="filter-pills mb-4">
                <a href="admin_bookings.php" class="btn btn-sm btn-lux-outline <?php if ($filter == "") { echo "active"; } ?>">All</a>
                <?php
                foreach ($statuses as $item) {
                    $activeClass = ($filter == $item) ? "active" : "";
                    echo '<a href="admin_bookings.php?status=' . $item . '" class="btn btn-sm btn-lux-outline ' . $activeClass . '">' . $item . '</a>';
                }
                ?>
            </div>

            <div class="table-responsive">
                <table class="table table-lux mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Guests</th>
                            <th>Meals</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bookings->num_rows > 0) {
                            while ($row = $bookings->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row["full_name"]); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row["email"]); ?> &middot; <?php echo htmlspecialchars($row["phone"]); ?></small>
                                    <?php if ($row["special_request"] != "") { ?>
                                        <br><small class="fst-italic">&ldquo;<?php echo htmlspecialchars($row["special_request"]); ?>&rdquo;</small>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($row["room_name"]); ?></td>
                                <td><?php echo date("d M Y", strtotime($row["check_in"])); ?></td>
                                <td><?php echo date("d M Y", strtotime($row["check_out"])); ?></td>
                                <td><?php echo $row["guests"]; ?></td>
                                <td><?php echo htmlspecialchars($row["meal_plan"]); ?></td>
                                <td>$<?php echo number_format($row["total_price"], 2); ?></td>
                                <td><span class="badge badge-status status-<?php echo strtolower($row["status"]); ?>"><?php echo htmlspecialchars($row["status"]); ?></span></td>
                                <td>
                                    <form action="admin_bookings.php<?php if ($filter != "") { echo "?status=" . $filter; } ?>" method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="booking_id" value="<?php echo $row["id"]; ?>">
                                        <?php if ($row["status"] != "Confirmed") { ?>
                                            <button type="submit" name="action" value="confirm" class="btn btn-sm btn-lux">Confirm</button>
                                        <?php } ?>
                                        <?php if ($row["status"] != "Cancelled") { ?>
                                            <button type="submit" name="action" value="cancel" class="btn btn-sm btn-lux-outline">Cancel</button>
                                        <?php } ?>
                                        <button type="submit" name="action" value="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete booking #<?php echo $row["id"]; ?>? This cannot be undone.')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="10">No bookings found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
