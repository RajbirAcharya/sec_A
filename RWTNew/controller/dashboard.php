<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";
$user_id = $_SESSION["user_id"];

// Latest measurement
$sql = "SELECT * FROM body_measurements WHERE user_id=? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$measurement = $stmt->get_result()->fetch_assoc();

// Latest goal
$sql = "SELECT * FROM goals WHERE user_id=? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$goal = $stmt->get_result()->fetch_assoc();

// Notification count
$sql = "SELECT COUNT(*) as cnt FROM notifications WHERE user_id=? AND is_read=0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

<h3>Latest Measurement</h3>
<?php if ($measurement): ?>
<p>Weight: <?php echo $measurement['weight']; ?> | Height: <?php echo $measurement['height']; ?></p>
<?php else: ?>
<p>No measurements found.</p>
<?php endif; ?>

<h3>Latest Goal</h3>
<?php if ($goal): ?>
<p>Target Weight: <?php echo $goal['target_weight']; ?> | Steps: <?php echo $goal['daily_steps']; ?></p>
<?php else: ?>
<p>No goals set.</p>
<?php endif; ?>

<h3>Notifications</h3>
<p>You have <?php echo $notif['cnt']; ?> unread notifications.</p>

<a href="logout.php">Logout</a>
</body>
</html>
