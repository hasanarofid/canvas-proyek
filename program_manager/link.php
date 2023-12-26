<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Canvas</title>
<link rel="icon" href="../img/logo.png" type="image/x-icon">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="../css/sb-admin-2.min.css" rel="stylesheet">
<!-- <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="../vendor/fontawesome-free/css/fontawesome.css">
<!-- <script src="../vendor/jquery/jquery.min.js"></script> -->
<link rel="stylesheet" href="../vendor/sweetalert2/dist/sweetalert2.min.css">
<script src="https://code.highcharts.com/highcharts.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.10/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">

<?php

session_start();
include '../functions.php';

if (!isset($_SESSION["program_manager"])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$queryGetmanagerId = "SELECT id FROM program_manager WHERE email = '$email'";
$resultGetmanagerId = mysqli_query($conn, $queryGetmanagerId);

if (mysqli_num_rows($resultGetmanagerId) === 1) {
    $row = mysqli_fetch_assoc($resultGetmanagerId);
    $manager_id = $row['id'];
}

// error_reporting(0);

?>


