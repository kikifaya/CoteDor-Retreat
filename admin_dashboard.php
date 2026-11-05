<?php
session_start();
require "admin_check.php";
require "db_connect.php";

$pageTitle = "Dashboard";
$currentPage = "admin";
$adminTab = "dashboard";
$solidNav = true;

$result = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$bookingCount = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT COUNT(*) AS total FROM rooms");
$roomCount = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'");
$guestCount = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT COUNT(*) AS total FROM messages");
$messageCount = $result->fetch_assoc()["total"];

$result = $conn->query("SELECT SUM(total_price) AS revenue FROM bookings WHERE status = 'Confirmed'");
$revenue = $result->fetch_assoc()["revenue"];
if ($revenue == null) {
    $revenue = 0;
}

$statusCounts = ["Pending" => 0, "Confirmed" => 0, "Cancelled" => 0];
$result = $conn->query("SELECT status, COUNT(*) AS total FROM bookings GROUP BY status");
while ($row = $result->fetch_assoc()) {
    $statusCounts[$row["status"]] = $row["total"];
}

$roomShares = $conn->query("SELECT rooms.room_name, COUNT(bookings.id) AS total FROM rooms LEFT JOIN bookings ON bookings.room_id = rooms.id AND bookings.status != 'Cancelled' GROUP BY rooms.id, rooms.room_name ORDER BY total DESC");

$latest = $conn->query("SELECT bookings.id, bookings.check_in, bookings.check_out, bookings.total_price, bookings.status, users.full_name, rooms.room_name FROM bookings JOIN users ON bookings.user_id = users.id JOIN rooms ON bookings.room_id = rooms.id ORDER BY bookings.created_at DESC LIMIT 5");

include "header.php";
?>

    <section class="admin-wrap">
        <div class="container">
            <?php include "admin_nav.php"; ?>

            <p class="welcome-message" id="welcomeMessage" data-name="<?php echo htmlspecialchars($_SESSION["full_name"]); ?>"></p>

            <?php
            if ($statusCounts["Pending"] > 0) {
                echo '<div class="alert alert-warning">You have ' . $statusCounts["Pending"] . ' pending booking(s) waiting for confirmation. <a href="admin_bookings.php?status=Pending" class="alert-link">Review them</a></div>';
            } else {
                echo '<div class="alert alert-success">All bookings have been reviewed.</div>';
            }
            ?>

            <div class="row g-4 my-2">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="number"><?php echo $bookingCount; ?></div>
                        <div class="label">Bookings</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="number"><?php echo $guestCount; ?></div>
                        <div class="label">Registered guests</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="number"><?php echo $roomCount; ?></div>
                        <div class="label">Room types</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="number">$<?php echo number_format($revenue, 0); ?></div>
                        <div class="label">Confirmed revenue</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-lg-6">
                    <div class="stat-card">
                        <h3 class="mb-4">Booking status</h3>
                        <?php
                        foreach ($statusCounts as $status => $count) {
                            $percent = $bookingCount > 0 ? round(($count / $bookingCount) * 100) : 0;
                        ?>
                            <div class="progress-label">
                                <span><?php echo $status; ?> (<?php echo $count; ?>)</span>
                                <span><?php echo $percent; ?>%</span>
                            </div>
                            <div class="progress mb-4" role="progressbar" aria-label="<?php echo $status; ?> bookings" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: <?php echo $percent; ?>%"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="stat-card">
                        <h3 class="mb-4">Most booked rooms</h3>
                        <?php
                        $activeBookings = $statusCounts["Pending"] + $statusCounts["Confirmed"];
                        while ($row = $roomShares->fetch_assoc()) {
                            $percent = $activeBookings > 0 ? round(($row["total"] / $activeBookings) * 100) : 0;
                        ?>
                            <div class="progress-label">
                                <span><?php echo htmlspecialchars($row["room_name"]); ?> (<?php echo $row["total"]; ?>)</span>
                                <span><?php echo $percent; ?>%</span>
                            </div>
                            <div class="progress mb-4" role="progressbar" aria-label="<?php echo htmlspecialchars($row["room_name"]); ?> share" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: <?php echo $percent; ?>%"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                <h3 class="mb-0">Latest bookings</h3>
                <a href="admin_bookings.php" class="btn btn-sm btn-lux-outline">All bookings</a>
            </div>

            <div class="table-responsive">
                <table class="table table-lux mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Dates</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($latest->num_rows > 0) {
                            while ($row = $latest->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . htmlspecialchars($row["full_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["room_name"]) . "</td>";
                                echo "<td>" . date("d M", strtotime($row["check_in"])) . " - " . date("d M Y", strtotime($row["check_out"])) . "</td>";
                                echo "<td>$" . number_format($row["total_price"], 2) . "</td>";
                                echo '<td><span class="badge badge-status status-' . strtolower($row["status"]) . '">' . htmlspecialchars($row["status"]) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="6">No bookings yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <p class="text-muted mt-4 mb-0">You also have <?php echo $messageCount; ?> message(s) from the contact form. <a href="admin_messages.php">Read them</a></p>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
