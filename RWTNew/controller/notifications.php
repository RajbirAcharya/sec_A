<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";

$user_id = $_SESSION["user_id"];

// Fetch notifications
$sql = "SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><title>Notifications</title></head>
<body>
<h2>Your Notifications</h2>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li><?php echo $row['message'] . ($row['is_read'] ? "" : " (new)"); ?></li>
<?php endwhile; ?>
</ul>
</body>
</html>
