<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include "db.php";
$user_id = $_SESSION["user_id"];

if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=data_export.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, ["Type", "Date", "Details"]);

    $sql = "SELECT created_at, height, weight FROM body_measurements WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, ["Measurement", $row['created_at'], "Height: ".$row['height']." Weight: ".$row['weight']]);
    }

    $sql = "SELECT created_at, target_weight FROM goals WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, ["Goal", $row['created_at'], "Target Weight: ".$row['target_weight']]);
    }

    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Data Export</title></head>
<body>
<h2>Export Your Data</h2>
<a href="data_export.php?export=true">Download CSV</a>
</body>
</html>
