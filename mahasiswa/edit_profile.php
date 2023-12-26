<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    $upload_dir = "../img/" ;
    // $upload_dir = 'img/'; // Change this to your upload directory
    $filename = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];

    // Validate file type if needed
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_types)) {
        $program_manager_id = $_GET['id']; // Replace with the actual program_manager ID
        $new_filename = 'profile_' . $program_manager_id . '_' . time() . '.' . $file_ext;

        $destination = $upload_dir . $new_filename;

        // Check if the directory exists, create it if not
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the destination
        move_uploaded_file($file_tmp, $destination);

        // Update the program_manager in the database
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $nohp = mysqli_real_escape_string($conn, $_POST['nohp']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the program_manager in the database
        $update_query = "UPDATE mahasiswa 
        SET nama = ?, email = ?, nohp = ?, foto = ?, password = ? 
        WHERE id = ?";

// Prepare the statement
$stmt = $conn->prepare($update_query);

// Bind parameters
$stmt->bind_param("sssssi", $nama, $email, $nohp, $new_filename, $hashedPassword, $program_manager_id);
// $stmt->execute(); // Execute the update query

        // var_dump($stmt);die;
        if ($stmt->execute()) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Berhasil di Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Data Gagal Di-Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }

        $stmt->close();
    } else {
        echo "Invalid file type. Allowed types: " . implode(', ', $allowed_types);
    }
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topbar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class=" mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
                        <div style="border-bottom: 2px solid black;"> </div>
                    </div>

                    <div class="row">
                    <div class="container mt-5">
                    <?php 
                    $user_id = mysqli_real_escape_string($conn, $_GET['id']);
                    $user_data_query = mysqli_query($conn, "SELECT * FROM mahasiswa where id = '$user_id' ");
                    if (!$user_data_query) {
                        die("Error in the query: " . mysqli_error($conn));
                    }
                    
                    // Check if a user with the specified ID exists
                    if (mysqli_num_rows($user_data_query) > 0) {
                        $user_data = mysqli_fetch_assoc($user_data_query);
                    
                        // Now $user_data contains the user information
                    } else {
                        // Handle the case where the user with the specified ID does not exist
                        echo "User not found";
                    }
                    
                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <h2>Edit Profile</h2>
                                <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                        <label for="foto">Change Profile Picture:</label>
                                        <input class="form-control" type="file" name="foto" id="foto" accept="image/*" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_name">Full Name:</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $user_data['nama'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= $user_data['email'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_phone">Phone:</label>
                                        <input type="text" class="form-control" id="nohp" name="nohp" value="<?= $user_data['nohp'] ?>" required>
                                    </div>

                                    <div class="form-group">
                                            <input type="password" id="password" class="form-control form-control-user" name="password" placeholder="Masukkan Password Baru" >
                                            <span id="error-message1" style="color: red;"></span>
                                        </div>
                                        

                                        <div class="form-group">
                                            <input type="password" id="password-ulang" class="form-control form-control-user" name="password-ulang" placeholder="Ulangi Password Baru" >
                                            <span id="error-message" style="color: red;"></span>
                                        </div>


                                    <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Canvas 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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


    <?php include "plugin.php"; ?>
    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>