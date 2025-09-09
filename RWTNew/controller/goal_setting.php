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
    $target_weight = $_POST["target_weight"];
    $daily_steps = $_POST["daily_steps"];
    $calories_goal = $_POST["calories_goal"];

    $sql = "INSERT INTO goals (user_id, target_weight, daily_steps, calories_goal) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idii", $user_id, $target_weight, $daily_steps, $calories_goal);
    if ($stmt->execute()) {
        $message = "Goal saved successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM goals WHERE user_id=? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$goal = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Goal Setting</title></head>
<body>
<h2>Set Your Goals</h2>
<form method="POST">
    <input type="number" step="0.01" name="target_weight" placeholder="Target Weight" required><br>
    <input type="number" name="daily_steps" placeholder="Daily Steps" required><br>
    <input type="number" name="calories_goal" placeholder="Calories Goal" required><br>
    <button type="submit">Save Goal</button>
</form>
<p><?php echo $message; ?></p>
<?php if ($goal): ?>
<h3>Latest Goal</h3>
<p>Target Weight: <?php echo $goal['target_weight']; ?> | Steps: <?php echo $goal['daily_steps']; ?> | Calories: <?php echo $goal['calories_goal']; ?></p>
<?php endif; ?>
</body>
</html>
