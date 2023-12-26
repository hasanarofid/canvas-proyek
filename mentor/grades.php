<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>


<?php

if (isset($_POST['submit_grade'])) {
    // var_dump($_POST['submission_id']);die;
    $submission_id = mysqli_real_escape_string($conn, $_POST['submission_id']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $kriteria = mysqli_real_escape_string($conn, $_POST['kriteria']);

    // Remove leading/trailing whitespaces and control characters
    $kriteria = trim($kriteria);
    
    // Extract the last letter
    $lastLetter = substr($kriteria, -1);
    

    // Perbarui data ke dalam tabel grades
    $insert_query = "INSERT INTO grades (submission_id, mahasiswa_id, grade,kriteria) 
                     VALUES (?, ?, ?,?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iiii", $submission_id, $mahasiswa_id, $grade,$lastLetter);

    // Mendapatkan assignment_id dan mahasiswa_id dari submission_id
    $get_ids_query = "SELECT assignment_id, mahasiswa_id FROM assignment_submissions WHERE submission_id = ?";
    $stmt_get_ids = $conn->prepare($get_ids_query);
    $stmt_get_ids->bind_param("i", $submission_id);
    $stmt_get_ids->execute();
    $result = $stmt_get_ids->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignment_id = $row['assignment_id'];
        $mahasiswa_id = $row['mahasiswa_id'];

        if ($stmt->execute()) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Grade Berhasil Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menambahkan Grade!',
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
                title: 'Submission Tidak Ditemukan!',
                text: 'Mohon periksa submission ID.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

if (isset($_POST['submit_feedback'])) {
    // var_dump($_POST);die;
    // $assignment_id = mysqli_real_escape_string($conn, $_GET['assignment_id']);
    $submission_id = mysqli_real_escape_string($conn, $_POST['submission_id']);
    $mahasiswa_id = mysqli_real_escape_string($conn, $_POST['mahasiswa_id']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $check_query = "SELECT * FROM grades WHERE submission_id = ? AND mahasiswa_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $submission_id, $mahasiswa_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    $status_update = 0;
    if ($result->num_rows > 0) {
        // Update existing record
        $update_query = "UPDATE grades SET feedback = ? WHERE submission_id = ? AND mahasiswa_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sii", $feedback, $submission_id, $mahasiswa_id);
        $update_stmt->execute();
        $status_update = 1;
    } else {
        // Insert new record
        $insert_query = "INSERT INTO grades (submission_id, mahasiswa_id, feedback) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iis", $submission_id, $mahasiswa_id, $feedback);
        $insert_stmt->execute();
        $status_update = 1;
    }


    if ($status_update) {
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

// ajax check validasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gradeValue'])) {
    $gradeValue = $_POST['gradeValue'];

    // Fetch grade criteria from grade_nilai table
    $query = "SELECT nilai, grade FROM grade_nilai WHERE grade_range_start <= ? AND grade_range_end >= ?";
    $gradenya = $conn->prepare($query);
    $gradenya->bind_param('ii', $gradeValue, $gradeValue);
    $gradenya->execute();
    $gradenya->bind_result($nilai, $grade);

    if ($gradenya->fetch()) {
        echo $grade;
        exit;
    } else {
        echo "No criteria found for the given grade.";
        exit;
    }

    $gradenya->close();
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
                    <?php
                    $assignment_id = $_GET['assignment_id'];
                    $assignment = query("SELECT * FROM assignments WHERE assignment_id = $assignment_id")[0];
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Grades Untuk Assignment : <?= $assignment['assignment_title']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Submission</th>
                                            <th>Grade</th>
                                            <th>Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $assignment_id = mysqli_real_escape_string($conn, $_GET['assignment_id']);
                                        $stmt = $conn->prepare("SELECT s.*, m.nama, a.assignment_title, g.grade, g.feedback,g.kriteria FROM assignment_submissions s
                        JOIN mahasiswa m ON s.mahasiswa_id = m.id
                        JOIN assignments a ON s.assignment_id = a.assignment_id
                        LEFT JOIN grades g ON s.submission_id = g.submission_id
                        WHERE s.assignment_id = ?");
                                        $stmt->bind_param("i", $assignment_id);

                                        $stmt->execute();
                                        $submissions = $stmt->get_result();
                                        ?>
                                        <?php foreach ($submissions as $submission) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($submission['nama']); ?></td>
                                                <td><?= htmlspecialchars($submission['assignment_title']); ?></td>
                                                <td>
                                                        <form action="" method="POST">
                                                            <input type="hidden" name="submission_id" value="<?= $submission['submission_id']; ?>">
                                                            <input type="hidden" name="kriteria" id="kriteria_name">
                                                            <input type="number" value="<?= !empty($submission['grade']) ? $submission['grade'] : '' ?>" class="form-control" id="grade" name="grade" required>
                                                            <p>Kriteria : <span id="kriteria"><?= !empty($submission['kriteria']) ? $submission['kriteria'] : '' ?></span> </p>
                                                            <button type="submit" name="submit_grade" class="btn btn-primary mt-2">Simpan</button>
                                                        </form>
                                                </td>
                                                <td>
                                                    <?php

                                                     // Periksa apakah sudah ada feedback untuk tugas ini
                                                     $stmt = $conn->prepare("SELECT feedback FROM grades WHERE submission_id = ? AND mahasiswa_id = ?");
                                                     $stmt->bind_param("ii", $submission['submission_id'], $submission['mahasiswa_id']);
                                                     $stmt->execute();
                                                     $result = $stmt->get_result();
                                                     $existing_feedback = $result->fetch_assoc();
                                                     
                                                     
                                                    ?>
                                                    <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="submission_id" value="<?= $submission['submission_id']; ?>">
                                                            <input type="hidden" name="mahasiswa_id" value="<?= $submission['mahasiswa_id']; ?>">
                                                            <textarea class="form-control" id="feedback" name="feedback" rows="3">
                                                                <?php echo isset($existing_feedback['feedback']) ? $existing_feedback['feedback'] : ''; ?>
                                                            </textarea>
                                                            <button type="submit" name="submit_feedback" class="btn btn-primary mt-2">Submit Feedback</button>
                                                        </form>
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


            // hitung grade nilai
            $('#grade').on('input', function() {
                    var gradeValue = $(this).val();
                    $('#kriteria').html('');

                    // Validate input range
                    if (gradeValue < 0 || gradeValue > 100) {
                        $('#grade').addClass('is-invalid');
                        return;
                    } else {
                        $('#grade').removeClass('is-invalid');
                    }

                    // Fetch and display grade criteria using AJAX
                    $.ajax({
                        url: 'grades.php', // Replace with the actual PHP file
                        method: 'POST',
                        data: { gradeValue: gradeValue },
                        success: function(response) {
                            $('#kriteria').html(response);
    var kriteriaText = $('#kriteria').text().trim();
    console.log('Content of #kriteria:', kriteriaText);
    $('#kriteria_name').val(kriteriaText);
                        },
                        error: function() {
                            console.log('Error fetching grade criteria.');
                        }
                    });
                });

                // Additional client-side validation if needed
                $('#gradeForm').submit(function() {
                    var gradeValue = $('#grade').val();

                    // Validate input range
                    if (gradeValue < 0 || gradeValue > 100) {
                        $('#grade').addClass('is-invalid');
                        return false;
                    } else {
                        $('#grade').removeClass('is-invalid');
                    }

                    return true;
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