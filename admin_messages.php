<?php
session_start();
require "admin_check.php";
require "db_connect.php";

$pageTitle = "Messages";
$currentPage = "admin";
$adminTab = "messages";
$solidNav = true;

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $deleteId = (int) $_POST["delete_id"];

    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        $successMessage = "The message has been deleted.";
    } else {
        $errorMessage = "Unable to delete the message.";
    }

    $stmt->close();
}

$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
$subscribers = $conn->query("SELECT * FROM subscribers ORDER BY created_at DESC");

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

            <div class="row g-5">
                <div class="col-12 col-lg-8">
                    <h3 class="mb-3">Contact messages (<?php echo $messages->num_rows; ?>)</h3>

                    <?php
                    if ($messages->num_rows > 0) {
                        while ($row = $messages->fetch_assoc()) {
                    ?>
                        <div class="stat-card mb-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <p class="room-meta mb-1"><?php echo htmlspecialchars($row["subject"]); ?></p>
                                    <h4 class="mb-0"><?php echo htmlspecialchars($row["name"]); ?></h4>
                                    <a href="mailto:<?php echo htmlspecialchars($row["email"]); ?>"><?php echo htmlspecialchars($row["email"]); ?></a>
                                </div>
                                <small class="text-muted"><?php echo date("d M Y, H:i", strtotime($row["created_at"])); ?></small>
                            </div>
                            <p class="mt-3 mb-3"><?php echo nl2br(htmlspecialchars($row["message"])); ?></p>
                            <form action="admin_messages.php" method="POST">
                                <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')">Delete</button>
                            </form>
                        </div>
                    <?php
                        }
                    } else {
                        echo '<div class="alert alert-info">No messages yet.</div>';
                    }
                    ?>
                </div>

                <div class="col-12 col-lg-4">
                    <h3 class="mb-3">Newsletter (<?php echo $subscribers->num_rows; ?>)</h3>
                    <ul class="list-group list-group-flush table-lux">
                        <?php
                        if ($subscribers->num_rows > 0) {
                            while ($row = $subscribers->fetch_assoc()) {
                                echo '<li class="list-group-item d-flex justify-content-between"><span>' . htmlspecialchars($row["email"]) . '</span><small class="text-muted">' . date("d M", strtotime($row["created_at"])) . '</small></li>';
                            }
                        } else {
                            echo '<li class="list-group-item">No subscribers yet.</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php
$conn->close();
include "footer.php";
?>
