<?php
include "../functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_type = $_POST['activity_type'];
    $activity_detail = $_POST['activity_detail'];

    // Fungsi untuk log aktivitas
    logActivity($conn, $activity_type, $activity_detail);
}

function logActivity($conn, $activity_type, $activity_detail)
{
    $stmt = $conn->prepare("INSERT INTO activity_log (activity_type, activity_detail) VALUES (?, ?)");
    $stmt->bind_param("ss", $activity_type, $activity_detail);
    $stmt->execute();
}
