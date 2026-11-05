<?php
session_start();
require "admin_check.php";
require "db_connect.php";

$pageTitle = "Users";
$currentPage = "admin";
$adminTab = "users";
$solidNav = true;

$sql = "SELECT users.id, users.full_name, users.username, users.email, users.phone, users.role, users.created_at, COUNT(bookings.id) AS booking_total FROM users LEFT JOIN bookings ON bookings.user_id = users.id GROUP BY users.id ORDER BY users.created_at DESC";
$users = $conn->query($sql);

include "header.php";
?>

    <section class="admin-wrap">
        <div class="container">
            <?php include "admin_nav.php"; ?>

            <p class="text-muted"><?php echo $users->num_rows; ?> account(s) in total.</p>

            <div class="table-responsive">
                <table class="table table-lux mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Bookings</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($users->num_rows > 0) {
                            while ($row = $users->fetch_assoc()) {
                                $roleClass = ($row["role"] == "admin") ? "status-confirmed" : "status-pending";
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . htmlspecialchars($row["full_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                                echo '<td><span class="badge badge-status ' . $roleClass . '">' . htmlspecialchars($row["role"]) . "</span></td>";
                                echo "<td>" . $row["booking_total"] . "</td>";
                                echo "<td>" . date("d M Y", strtotime($row["created_at"])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="8">No users found.</td></tr>';
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
