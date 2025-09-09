<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";

$user_id = $_SESSION["user_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = $_POST["progress_note"];
    $date = $_POST["progress_date"];

    $sql = "INSERT INTO progress (user_id, progress_note, progress_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $note, $date);
    if ($stmt->execute()) {
        $message = "Progress saved!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM progress WHERE user_id=? ORDER BY progress_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><title>Progress Charts</title></head>
<body>
<h2>Progress Tracking</h2>
<form method="POST">
    <textarea name="progress_note" placeholder="Progress note" required></textarea><br>
    <input type="date" name="progress_date" required><br>
    <button type="submit">Save</button>
</form>
<p><?php echo $message; ?></p>
<h3>Progress History</h3>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li><?php echo $row['progress_date'] . ": " . $row['progress_note']; ?></li>
<?php endwhile; ?>
</ul>
</body>
</html>
