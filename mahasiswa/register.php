<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Canvas</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <style>
        <style>body,
        footer,
        card {
            background-color: #749BC2 !important;
        }
    </style>
    </style>
</head>
<?php

session_start();
include '../functions.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nohp = mysqli_real_escape_string($conn, $_POST['nohp']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $instagram_link = mysqli_real_escape_string($conn, $_POST['instagram_link']);
    $tiktok_link = mysqli_real_escape_string($conn, $_POST['tiktok_link']);
    $facebook_link = mysqli_real_escape_string($conn, $_POST['facebook_link']);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM mahasiswa WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Email sudah digunakan!',
                text: 'Mohon gunakan email lain.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $query = "INSERT INTO mahasiswa (email, password, nama, nohp, deskripsi, instagram_link, tiktok_link, facebook_link) 
                  VALUES ('$email', '$hashedPassword', '$nama', '$nohp', '$deskripsi', '$instagram_link', '$tiktok_link', '$facebook_link')";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Gagal!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}


?>

<body>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="d-flex flex-column">
                <h4 class="mt-5 text-center">Canvas - Registrasi Akun Mahasiswa</h4>
                <div class="d-flex justify-content-center">
                    <img src="../img/logo.png" class="img-fluid" width="100" alt="">
                </div>
            </div>
            <div class="col-xl-10 col-lg-12 col-md-9 col-sm-12">

                <div class="card o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="p-2">
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Masukkan Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Masukkan Password" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="nama" placeholder="Masukkan Nama" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="nohp" placeholder="Masukkan No. HP" required>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control form-control-user" name="deskripsi" placeholder="Masukkan Deskripsi" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="instagram_link" placeholder="Masukkan Instagram Link" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="tiktok_link" placeholder="Masukkan TikTok Link" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="facebook_link" placeholder="Masukkan Facebook Link" required>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-secondary btn-user btn-block">
                                            Register
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <br>
                        <center>
                            <a href="login.php">Login</a>
                        </center>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include "footer.php"; ?>
    <?php include "plugin.php"; ?>

    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>

</body>

</html>