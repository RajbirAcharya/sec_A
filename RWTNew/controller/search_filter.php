<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";
$user_id = $_SESSION["user_id"];

$search = $_GET["search"] ?? "";

$sql = "SELECT * FROM body_measurements WHERE user_id=? AND (height LIKE ? OR weight LIKE ?) ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("iss", $user_id, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><title>Search Measurements</title></head>
<body>
<h2>Search Measurements</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search height/weight" value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li><?php echo $row['created_at'] . " - Height: " . $row['height'] . ", Weight: " . $row['weight']; ?></li>
<?php endwhile; ?>
</ul>
</body>
</html>
