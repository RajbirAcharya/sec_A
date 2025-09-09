<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";

$user_id = $_SESSION["user_id"];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $waist  = $_POST["waist"];
    $chest  = $_POST["chest"];

    $sql = "INSERT INTO body_measurements (user_id, height, weight, waist, chest) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idddd", $user_id, $height, $weight, $waist, $chest);
    if ($stmt->execute()) {
        $message = "Measurements saved successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch latest measurement
$sql = "SELECT * FROM body_measurements WHERE user_id=? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$latest = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Body Measurements</title></head>
<body>
<h2>Body Measurements</h2>
<form method="POST">
    <input type="number" step="0.01" name="height" placeholder="Height" required><br>
    <input type="number" step="0.01" name="weight" placeholder="Weight" required><br>
    <input type="number" step="0.01" name="waist" placeholder="Waist"><br>
    <input type="number" step="0.01" name="chest" placeholder="Chest"><br>
    <button type="submit">Save</button>
</form>
<p><?php echo $message; ?></p>
<?php if ($latest): ?>
<h3>Latest Measurement</h3>
<p>Height: <?php echo $latest['height']; ?> | Weight: <?php echo $latest['weight']; ?> | Waist: <?php echo $latest['waist']; ?> | Chest: <?php echo $latest['chest']; ?></p>
<?php endif; ?>
</body>
</html>
