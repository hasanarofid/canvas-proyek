<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>


<?php
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $grade_range_start = mysqli_real_escape_string($conn, $_POST['grade_range_start']);
    $grade_range_end = mysqli_real_escape_string($conn, $_POST['grade_range_end']);

    // Tentukan lokasi penyimpanan gambar


    if (empty($nilai) || empty($grade)) {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Lengkap!',
                text: 'Mohon isi semua kolom.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        // Upload gambar ke direktori
        $query = "INSERT INTO grade_nilai (nilai,grade,grade_range_start,grade_range_end) 
        VALUES ('$nilai','$grade','$grade_range_start','$grade_range_end')";
            if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Data Grade Nilai Berhasil Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
            } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Data Grade Nilai Gagal Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
            }
    }
}

if (isset($_POST['edit'])) {
    // Ambil data dari form
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $grade_range_start = mysqli_real_escape_string($conn, $_POST['grade_range_start']);
    $grade_range_end = mysqli_real_escape_string($conn, $_POST['grade_range_end']);

    $query = "UPDATE grade_nilai 
    SET nilai = '$nilai', grade = '$grade', grade_range_start = '$grade_range_start'
    , grade_range_end = '$grade_range_end'
    WHERE id = '$id'";

    // Cek apakah ada gambar yang diunggah
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Grade Nilai Berhasil Di-Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Grade Nilai Gagal Di-Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}


if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $query = "DELETE FROM grade_nilai WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di-Hapus!',
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
                <div class="mb-3">
                        <p>
                            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-plus-square"></i> Kelola Grade Nilai
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="class_name">Nilai:</label>
                                        <input type="number" class="form-control" id="nilai" name="nilai"  required>
                                    </div>

                                    <div class="form-group">
                                        <label for="class_name">Grade:</label>
                                        <input type="text" class="form-control" id="grade" name="grade"  required>
                                    </div>

                                    <div class="form-group">
                                        <label for="class_name">Range Start:</label>
                                        <input type="number" class="form-control" id="grade_range_start" name="grade_range_start"  required>
                                    </div>

                                    <div class="form-group">
                                        <label for="class_name">Range End:</label>
                                        <input type="number" class="form-control" id="grade_range_end" name="grade_range_end"  required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-secondary w-100">Tambah Grade Nilai</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Grades Nilai</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nilai</th>
                                            <th>Grade</th>
                                            <th>Range Start</th>
                                            <th>Range End</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $stmt = $conn->prepare("SELECT *  FROM grade_nilai");
                                        $stmt->execute();
                                        $submissions = $stmt->get_result();
                                        ?>
                                        <?php foreach ($submissions as $submission) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($submission['nilai']); ?></td>
                                                <td><?= htmlspecialchars($submission['grade']); ?></td>
                                                <td><?= htmlspecialchars($submission['grade_range_start']); ?></td>
                                                <td><?= htmlspecialchars($submission['grade_range_end']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $submission['id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $submission['id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                                    <!-- Modal Edit Kelas -->
                                                    <div class="modal fade" id="editModal<?= $submission['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="id" value="<?= $submission['id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="class_name">Nilai:</label>
                                                                    <input type="number" class="form-control" id="nilai" name="nilai" value="<?= $submission['nilai']; ?>" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="class_name">Grade:</label>
                                                                    <input type="text" class="form-control" id="grade" name="grade" value="<?= $submission['grade']; ?>" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="class_name">Range Start:</label>
                                                                    <input type="number" class="form-control" id="grade_range_start" name="grade_range_start" value="<?= $submission['grade_range_start']; ?>" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="class_name">Range End:</label>
                                                                    <input type="number" class="form-control" id="grade_range_end" name="grade_range_end" value="<?= $submission['grade_range_start']; ?>" required>
                                                                </div>

                                                                <button type="submit" name="edit" class="btn btn-secondary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Kelas -->
                                            <div class="modal fade" id="hapusModal<?= $submission['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Grade Nilai</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus Grade Nilai dengan Nilai: <b><?= htmlspecialchars($submission['nilai']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id" value="<?= $submission['id']; ?>">
                                                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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