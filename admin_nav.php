            <p class="eyebrow">Admin</p>
            <h1 class="section-title"><?php echo htmlspecialchars($pageTitle); ?></h1>

            <div class="admin-tabs">
                <a href="admin_dashboard.php" class="<?php if ($adminTab == "dashboard") { echo "active"; } ?>">Dashboard</a>
                <a href="admin_bookings.php" class="<?php if ($adminTab == "bookings") { echo "active"; } ?>">Bookings</a>
                <a href="admin_rooms.php" class="<?php if ($adminTab == "rooms") { echo "active"; } ?>">Rooms</a>
                <a href="admin_users.php" class="<?php if ($adminTab == "users") { echo "active"; } ?>">Users</a>
                <a href="admin_messages.php" class="<?php if ($adminTab == "messages") { echo "active"; } ?>">Messages</a>
            </div>
