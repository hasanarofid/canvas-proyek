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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
</head>
<?php

session_start();
include '../functions.php';

// cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_reporting(0);
    // mengambil data dari form login
    $email = $_POST["email"];
    $password = $_POST["password"];

    // melindungi dari SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // mencari user di database
    $sql = "SELECT * FROM mentor WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // verifikasi password
    if (password_verify($password, $user['password'])) {
        // jika password benar, redirect ke halaman dashboard
        $_SESSION["mentor"] = true;
        $_SESSION["email"] = $email;
        $_SESSION["id"] = $user['id'];
        $_SESSION["foto"] = $user['foto'];
        $_SESSION["nama"] = $user['nama'];
        header("Location: ./index.php");
        exit();
    } else {
        // jika password salah, tampilkan pesan error
        $error = "Email atau password salah";
    }
}
?>

<body>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="modalLabel">Tata Cara Penggunaan Website Canvas</h5>
                    <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <!-- Tempatkan PDF di sini -->
                    <embed src="..//Tata Cara Penggunaan Website Canvas.pdf" type="application/pdf" width="100%" height="600px" />
                </div>
                <div class="modal-footer">
                    <!-- Tombol Download -->
                    <a href="..//Tata Cara Penggunaan Website Canvas.pdf" download class="btn btn-primary">Download</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="d-flex flex-column mb-1">
                <h4 class="mt-5 text-center">Canvas - Login Mentor</h4>
                <div class="d-flex justify-content-center">
                    <img src="../img/logocanvas.png" alt="Logo"  width="600px" height="200px" >
         
                </div>
            </div>
            <div class="col-xl-10 col-lg-12 col-md-9 col-sm-12">

                <div class="card o-hidden border-0">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col">
                                <div class="p-2">
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Masukkan Email" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Masukkan Password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showPasswordToggle">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-secondary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <center>
                            <a href="register.php">Registrasi Akun</a>
                        </center>
                        <hr>
                        <center>
                            <a href="lupapassword.php">Lupa Password</a>
                        </center>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include "footer.php"; ?>
    <?php include "plugin.php"; ?>
    

    <script>
                function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var showPasswordToggle = document.getElementById('showPasswordToggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPasswordToggle.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
        } else {
            passwordInput.type = 'password';
            showPasswordToggle.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
        }
    }

    // Add click event listener to the eye icon
    document.getElementById('showPasswordToggle').addEventListener('click', togglePasswordVisibility);
  
        $(document).ready(function() {
            // $("#myModal").modal('show');
        });
    </script>


</body>

</html>