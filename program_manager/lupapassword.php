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
if (isset($_POST['submit'])) {
    // Get form data
    $email = $_POST['email'];
    $nohp = $_POST['nohp'];
    $password = $_POST['password'];
    $passwordUlang = $_POST['password-ulang'];

    // Validate the form data if needed

    // Check if the passwords match
    if ($password !== $passwordUlang) {
        // Passwords do not match, handle the error
        echo "Passwords do not match!";
    } else {
        // Connect to your database and perform the necessary user validation
        // Example: Replace 'your_table' with your actual user table name
        $checkUserQuery = "SELECT * FROM program_manager WHERE email = ? AND nohp = ?";
        $stmtCheckUser = $conn->prepare($checkUserQuery);
        $stmtCheckUser->bind_param("ss", $email, $nohp);
        $stmtCheckUser->execute();
        $result = $stmtCheckUser->get_result();

        if ($result->num_rows > 0) {
            // User found, proceed with updating the password in the database

            // Example update query (replace with your actual query)
            $updateQuery = "UPDATE program_manager SET password = ? WHERE email = ? AND nohp = ?";
            $stmt = $conn->prepare($updateQuery);

            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Bind parameters
            $stmt->bind_param("sss", $hashedPassword, $email, $nohp);

            // Execute the query
            if ($stmt->execute()) {
                $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Ubah Password berhasil!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
            } else {
                $script = "
                Swal.fire({
                    icon: 'danger',
                    title: 'Ubah Password gagal!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
            }

            // Close the statement
            $stmt->close();
        } else {
            // User not found, display an error message
            $script = "
            Swal.fire({
                icon: 'danger',
                title: 'Email dan no hp Salah!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
        }

        // Close the statement for checking user
        $stmtCheckUser->close();
    }
}
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     error_reporting(0);
//     // mengambil data dari form login
//     $email = $_POST["email"];
//     $password = $_POST["password"];

//     // melindungi dari SQL injection
//     $email = mysqli_real_escape_string($conn, $email);
//     $password = mysqli_real_escape_string($conn, $password);

//     // mencari user di database
//     $sql = "SELECT * FROM mahasiswa WHERE email = '$email' LIMIT 1";
//     $result = mysqli_query($conn, $sql);
//     $user = mysqli_fetch_assoc($result);

//     // verifikasi password
//     if (password_verify($password, $user['password'])) {
//         // jika password benar, redirect ke halaman dashboard
//         $_SESSION["mahasiswa"] = true;
//         $_SESSION["email"] = $email;
//         header("Location: ./index.php");
//         exit();
//     } else {
//         // jika password salah, tampilkan pesan error
//         $error = "Email atau password salah";
//     }
// }
?>

<body>

    <!-- Modal -->
    <!-- <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark font-weight-bold" id="modalLabel">Tata Cara Penggunaan Website Canvas</h5>
                    <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <embed src="..//Tata Cara Penggunaan Website Canvas.pdf" type="application/pdf" width="100%" height="600px" />
                </div>
                <div class="modal-footer">
                    <a href="..//Tata Cara Penggunaan Website Canvas.pdf" download class="btn btn-primary">Download</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> -->

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
        <div class="d-flex flex-column mb-1">
                <h4 class="mt-5 text-center">Canvas - Lupa Password Program Manager</h4>
            <img src="../img/logocanvas.png" alt="Logo"  width="600px" height="200px" >
                
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
                                            <input type="text" class="form-control form-control-user" name="nohp" placeholder="Masukkan No. HP" required>
                                        </div>

                                        <div class="form-group">
                                            <input type="password" id="password" class="form-control form-control-user" name="password" placeholder="Masukkan Password Baru" required>
                                            <span id="error-message1" style="color: red;"></span>
                                        </div>
                                        

                                        <div class="form-group">
                                            <input type="password" id="password-ulang" class="form-control form-control-user" name="password-ulang" placeholder="Ulangi Password Baru" required>
                                            <span id="error-message" style="color: red;"></span>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-secondary btn-user btn-block">
                                            Ubah Password
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
                            <a href="login.php">Sudah punya Akun? Login</a>
                        </center>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include "footer.php"; ?>
    <?php include "plugin.php"; ?>


<script>
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
<script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>