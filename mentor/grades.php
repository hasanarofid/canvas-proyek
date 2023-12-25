<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>


<?php

if (isset($_POST['submit_grade'])) {
    $submission_id = mysqli_real_escape_string($conn, $_POST['submission_id']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);

    // Perbarui data ke dalam tabel grades
    $insert_query = "INSERT INTO grades (submission_id, mahasiswa_id, grade) 
                     VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iii", $assignment_id, $mahasiswa_id, $grade);

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
                                        $stmt = $conn->prepare("SELECT s.*, m.nama, a.assignment_title, g.grade, g.feedback FROM assignment_submissions s
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
                                                    <?php if ($submission['grade'] === null) : ?>
                                                        <form action="" method="POST">
                                                            <input type="hidden" name="submission_id" value="<?= $submission['submission_id']; ?>">
                                                            <input type="number" class="form-control" id="grade" name="grade" required>
                                                            <button type="submit" name="submit_grade" class="btn btn-primary mt-2">Simpan</button>
                                                        </form>
                                                    <?php else : ?>
                                                        <?= htmlspecialchars($submission['grade']); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <textarea class="form-control" id="feedback" name="feedback" rows="3" readonly><?= htmlspecialchars($submission['feedback']); ?></textarea>
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