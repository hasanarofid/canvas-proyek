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

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nohp = mysqli_real_escape_string($conn, $_POST['nohp']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM mentor WHERE email = '$email'";
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
        $query = "INSERT INTO mentor (email, password, nama, nohp) 
                  VALUES ('$email', '$hashedPassword', '$nama', '$nohp')";
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
            <div class="d-flex flex-column mb-1">
                <h4 class="mt-5 text-center">Canvas - Registrasi Akun Mentor</h4>
                <img src="../img/logocanvas.png" alt="Logo"  width="600px" height="200px" >

            </div>
            <div class="col-xl-10 col-lg-12 col-md-9 col-sm-12">

                <div class="card o-hidden border-0">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="p-2">
                                    <form class="user" method="POST" action="">
                                    <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Masukkan Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="nama" placeholder="Masukkan Nama" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="nohp" placeholder="Masukkan No. HP" required>
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
                                            <span id="error-message1" style="color: red;"></span>

                                        </div>

                                        

                                        <div class="form-group">
                                        <div class="input-group">
                                        <input type="password" id="password-ulang" class="form-control form-control-user" name="password-ulang" placeholder="Ulangi Password Baru" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="showPasswordToggle2">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span id="error-message" style="color: red;"></span>
                                        </div>

                                        <!-- <div class="form-group">
                                            <textarea class="form-control form-control-user" name="deskripsi" placeholder="Masukkan Deskripsi" required></textarea>
                                        </div> -->
                                        <!-- <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="instagram_link" placeholder="Masukkan Instagram Link" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="tiktok_link" placeholder="Masukkan TikTok Link" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="facebook_link" placeholder="Masukkan Facebook Link" required>
                                        </div> -->
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
 
    function togglePasswordVisibility2() {
        var passwordInput = document.getElementById('password-ulang');
        var showPasswordToggle = document.getElementById('showPasswordToggle2');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPasswordToggle.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
        } else {
            passwordInput.type = 'password';
            showPasswordToggle.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
        }
    }

    // Add click event listener to the eye icon
    document.getElementById('showPasswordToggle2').addEventListener('click', togglePasswordVisibility2);
 
    
            $(document).ready(function () {
            // Function to validate password format
            function validatePassword(password) {
                // Password should be at least 8 characters and contain a combination of letters and numbers
                var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
                return passwordRegex.test(password);
            }

            // Function to display error message
            function displayErrorMessage(message, elementId) {
                $("#" + elementId).text(message);
            }

            // Event handler for password input
            $("#password").on("input", function () {
                var password = $(this).val();
                var errorMessageId = "error-message1";

                if (validatePassword(password)) {
                    displayErrorMessage("", errorMessageId);
                } else {
                    displayErrorMessage("Password harus minimal 8 karakter dan mengandung kombinasi huruf dan angka.", errorMessageId);
                }
            });

            // Event handler for password-ulang input
            $("#password-ulang").on("input", function () {
                var confirmPassword = $(this).val();
                var password = $("#password").val();
                var errorMessageId = "error-message";

                if (password === confirmPassword) {
                    displayErrorMessage("", errorMessageId);
                } else {
                    displayErrorMessage("Ulangi password harus sama dengan password baru di atas.", errorMessageId);
                }
            });
        });
</script>

    <?php include "footer.php"; ?>
    <?php include "plugin.php"; ?>

    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>

</body>

</html>