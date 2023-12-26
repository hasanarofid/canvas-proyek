<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

$mahasiswa_id = $mahasiswa_id;

// Prepare and execute the UPDATE query
$update = $conn->prepare("UPDATE tasks SET task_status = ? WHERE class_id = ? AND mahasiswa_id = ? and task_status = 'To Do' ");
$new_status = 'Doing'; // Set the new status as a variable
$update->bind_param("sii", $new_status, $_GET['class_id'], $mahasiswa_id );

// Execute the query
$update->execute();

// // Check if the query was successful
// if ($update->affected_rows > 0) {
//     echo "Update successful!";
// } else {
//     echo "No rows were updated or an error occurred.";
// }

if (isset($_POST['upload_submission'])) {

    $update = $conn->prepare("UPDATE tasks SET task_status = ? WHERE class_id = ? AND mahasiswa_id = ?");
    $new_status = 'Done'; // Set the new status as a variable
    $update->bind_param("sii", $new_status, $_GET['class_id'], $mahasiswa_id);

    // Execute the query
    $update->execute();


    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    $mahasiswa_id = mysqli_real_escape_string($conn, $_POST['mahasiswa_id']);

    $submission_file = $_FILES['submission_file']['name'];
    $submission_tmp = $_FILES['submission_file']['tmp_name'];

    // Direktori penyimpanan file submission
    $submission_path = "../documents/" . $submission_file;

    // Periksa apakah submission sudah diunggah sebelumnya
    $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND mahasiswa_id = ?");
    $stmt->bind_param("ii", $assignment_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_submission = $result->fetch_assoc();

    if (!$existing_submission) {
        // Submission belum ada, unggah file
        if (move_uploaded_file($submission_tmp, $submission_path)) {
            // Insert data submission ke dalam tabel assignment_submissions
            $insert_submission_query = "INSERT INTO assignment_submissions (assignment_id, mahasiswa_id, submission_file, status)
            VALUES (?, ?, ?, 'Done')";
        $stmt = $conn->prepare($insert_submission_query);
        $stmt->bind_param("iis", $assignment_id, $mahasiswa_id, $submission_file);
        

            if ($stmt->execute()) {

                // Log aktivitas submission ke activity_log
                $activity_detail = "Submission berhasil diunggah: Assignment ID: $assignment_id, Mahasiswa ID: $mahasiswa_id";
                $log_submission_query = "INSERT INTO activity_log (activity_type, activity_detail) VALUES ('submit_assignment', ?)";
                $stmt_log = $conn->prepare($log_submission_query);
                $stmt_log->bind_param("s", $activity_detail);
                $stmt_log->execute();

                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Submission Berhasil Diunggah!',
                        text: 'Mohon menunggu nilai dari mentor.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim Data Submission!',
                        text: 'Mohon mengulangi beberapa saat lagi.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            }
        } else {
            $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Upload File Submission!',
                        text: 'Mohon mengulangi beberapa saat lagi.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
        }
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Anda Sudah Mengirim Submission!',
                text: 'Mohon melakukan pengecekan lagi.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

if (isset($_POST['submit_feedback'])) {
    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    $mahasiswa_id = mysqli_real_escape_string($conn, $_POST['mahasiswa_id']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    // Insert feedback ke dalam tabel grades
    $insert_feedback_query = "INSERT INTO grades (assignment_id, mahasiswa_id, feedback)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE feedback = VALUES(feedback)";
    $stmt = $conn->prepare($insert_feedback_query);
    $stmt->bind_param("iis", $assignment_id, $mahasiswa_id, $feedback);

    if ($stmt->execute()) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Feedback Berhasil Disimpan!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        echo "Gagal menyimpan feedback.";
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
                    <!-- DataTales Example -->
                    <?php $class_id = $_GET['class_id']; ?>
                    <?php $class = query("SELECT * FROM class WHERE class_id = $class_id")[0]; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Data Assignment Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Assignment</th>
                                            <th>Upload Submission</th>
                                            <th>Status Task</th>
                                            <th>Nilai</th>
                                            <th>Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['class_id']); // Ambil ID kelas dari parameter URL
                                        $mahasiswa_id = $mahasiswa_id; // Menggunakan ID mahasiswa yang sudah tersedia
                                        $stmt = $conn->prepare("SELECT * FROM assignments WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $assignments = $stmt->get_result();


                                   

                                        ?>
                                        <?php foreach ($assignments as $assignment) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($assignment['assignment_title']); ?></td>
                                                <?php if ($assignment['is_link'] == 1) : ?>
                                                    <td><a target="_blank" href="<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php else : ?>
                                                    <td><a target="_blank" download href="../documents/<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php endif; ?>

                                                <?php
                                                // Cek apakah mahasiswa sudah mengirimkan submission untuk assignment ini
                                                $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND mahasiswa_id = ?");
                                                $stmt->bind_param("ii", $assignment['assignment_id'], $mahasiswa_id);
                                                $stmt->execute();
                                                $submission = $stmt->get_result()->fetch_assoc();

                                                if (!$submission) :
                                                    // Jika submission sudah ada, tampilkan tombol "Upload Submission"
                                                ?>
                                                    <td>
                                                        <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                                            <input type="hidden" name="mahasiswa_id" value="<?= $mahasiswa_id; ?>">
                                                            <input type="file" class="form-control" id="submission_file" name="submission_file" accept=".pdf, image/jpeg, image/jpg, image/png, image/gif">
                                                            <button type="submit" name="upload_submission" class="btn btn-primary mt-2">Upload Submission</button>
                                                        </form>
                                                    </td>
                                                <?php else :
                                                    // Jika submission belum ada, tampilkan pesan
                                                    $submission_id = $submission['submission_id'];
                                                ?>
                                                    <td>
                                                        <p>Anda telah mengirimkan submission untuk tugas ini.</p>
                                                    </td>
                                                <?php endif; ?>

                                                <td>
                                                    <!-- status task mahasiswa -->
                                                <?php
                                                 $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND mahasiswa_id = ?");
                                                 $stmt->bind_param("ii", $assignment['assignment_id'], $mahasiswa_id);
                                                 $stmt->execute();
                                                 $sts = $stmt->get_result()->fetch_assoc();
                                               if ($sts) {
                                                echo htmlspecialchars($sts['status']);
                                                } else {
                                                    echo "Doing";
                                                }
                                                ?>
                                                </td>

                                                <td>
                                                    <!-- Tampilkan nilai sesuai submission_id dan mahasiswa_id -->
                                                    <?php
                                                    $stmt = $conn->prepare("SELECT grade,kriteria FROM grades WHERE submission_id = ? AND mahasiswa_id = ?");
                                                    $stmt->bind_param("ii", $submission_id, $mahasiswa_id);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $grade = $result->fetch_assoc();
                                                    // var_dump($grade);die;
                                                    if ($grade) {
                                                        echo htmlspecialchars($grade['grade']) .' | '.htmlspecialchars($grade['kriteria']) ;
                                                    } else {
                                                        echo "Belum dinilai";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $mahasiswa_id = $mahasiswa_id;

                                                    // Periksa apakah sudah ada feedback untuk tugas ini
                                                    $stmt = $conn->prepare("SELECT feedback FROM grades WHERE submission_id = ? AND mahasiswa_id = ?");
                                                    $stmt->bind_param("ii", $submission_id, $mahasiswa_id);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $existing_feedback = $result->fetch_assoc();
                                                    

                                                    if ($existing_feedback !== null && isset($existing_feedback['feedback'])) {
                                                        // Feedback is available, display it
                                                        echo htmlspecialchars($existing_feedback['feedback']);
                                                    } else {
                                                        // No feedback available, display a message or a form
                                                        echo '<p>No feedback available.</p>';
                                                        // Uncomment the following lines if you want to display a form
                                                        /*
                                                        ?>
                                                        <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="submission_id" value="<?= $submission_id; ?>">
                                                            <input type="hidden" name="mahasiswa_id" value="<?= $mahasiswa_id; ?>">
                                                            <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                                                            <button type="submit" name="submit_feedback" class="btn btn-primary mt-2">Submit Feedback</button>
                                                        </form>
                                                        <?php
                                                        */
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include "footer.php"; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "plugin.php"; ?>

    <script>
        $(document).ready(function() {
            $('#dataX').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sLast": "Terakhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sSearch": "Cari:",
                    "sEmptyTable": "Tidak ada data yang tersedia dalam tabel",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ada data yang cocok dengan pencarian Anda"
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