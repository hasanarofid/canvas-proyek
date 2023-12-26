<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>Canvas</title>
<link rel="icon" href="../img/logo.png" type="image/x-icon">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="../vendor/fontawesome-free/css/fontawesome.css">
<script src="../vendor/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="../vendor/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-MzO/FmvDfVUZhL9g2Q/nDsZo+3IVhO6ZDVkoN+L2z6m6veZyeBEarhtOfUId04EJztA4QiaVqNMR1ARL4Ml8pA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php

session_start();
include '../functions.php';

if (!isset($_SESSION["mentor"])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$queryGetmentorId = "SELECT id FROM mentor WHERE email = '$email'";
$resultGetmentorId = mysqli_query($conn, $queryGetmentorId);

if (mysqli_num_rows($resultGetmentorId) === 1) {
    $row = mysqli_fetch_assoc($resultGetmentorId);
    $mentor_id = $row['id'];
}

// error_reporting(0);

?>