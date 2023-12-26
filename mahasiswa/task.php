<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $task_description = mysqli_real_escape_string($conn, $_POST['task_description']);
    $task_due_date = mysqli_real_escape_string($conn, $_POST['task_due_date']);

    if (empty($task_name) || empty($task_description) || empty($task_due_date)) {
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
        $query = "INSERT INTO tasks (task_name, task_description, task_due_date, mahasiswa_id) 
                  VALUES ('$task_name', '$task_description', '$task_due_date', '$mahasiswa_id')";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Data Berhasil Ditambahkan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Data Gagal Ditambahkan!',
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
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);
    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $task_description = mysqli_real_escape_string($conn, $_POST['task_description']);
    $task_due_date = mysqli_real_escape_string($conn, $_POST['task_due_date']);

    if (empty($task_name) || empty($task_description) || empty($task_due_date)) {
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
        $query = "UPDATE tasks SET task_name = '$task_name', task_description = '$task_description', task_due_date = '$task_due_date' 
                  WHERE task_id = '$task_id' AND mahasiswa_id = '$mahasiswa_id'";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Data Berhasil di Edit!',
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
    }
}

if (isset($_POST['hapus'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);

    $query = "DELETE FROM tasks WHERE task_id = '$task_id' AND mahasiswa_id = '$mahasiswa_id'";
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
                        <p style="display: none;">
                            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-plus-square"></i> Kelola Tugas
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="task_name">Nama Tugas:</label>
                                        <input type="text" class="form-control" id="task_name" name="task_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="task_description">Deskripsi Tugas:</label>
                                        <textarea class="form-control" id="task_description" name="task_description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="task_due_date">Tanggal Deadline:</label>
                                        <input type="date" class="form-control" id="task_due_date" name="task_due_date" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-secondary w-100">Tambah Tugas</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-secondary">List Tugas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Tugas</th>
                                            <th>Deskripsi Tugas</th>
                                            <th>Tanggal Deadline</th>
                                            <th style="display: none;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT * FROM tasks WHERE mahasiswa_id = ?");
                                        $stmt->bind_param("i", $mahasiswa_id);
                                        $mahasiswa_id = $mahasiswa_id;

                                        $stmt->execute();
                                        $tasks = $stmt->get_result();
                                        ?>

                                        <?php $i = 1; ?>
                                        <?php foreach ($tasks as $task) : ?>
                                            <?php
$stmt = $conn->prepare("SELECT * FROM assignment WHERE class_id = ? ");
$stmt->bind_param("i", $task['class_id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch one row as an associative array
$existing_submission = $result->fetch_assoc();
var_dump($existing_submission);die;
                                                 ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($task['task_name']); ?></td>
                                                <td><?= htmlspecialchars($task['task_description']); ?></td>
                                                <td><?= htmlspecialchars($task['task_due_date']); ?></td>
                                                <td style="display: none;">
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $task['task_id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $task['task_id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Tugas -->
                                            <div class="modal fade" id="editModal<?= $task['task_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Tugas</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="task_name">Nama Tugas:</label>
                                                                    <input type="text" class="form-control" id="task_name" name="task_name" value="<?= htmlspecialchars($task['task_name']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="task_description">Deskripsi Tugas:</label>
                                                                    <textarea class="form-control" id="task_description" name="task_description" required><?= htmlspecialchars($task['task_description']); ?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="task_due_date">Tanggal Deadline:</label>
                                                                    <input type="date" class="form-control" id="task_due_date" name="task_due_date" value="<?= htmlspecialchars($task['task_due_date']); ?>" required>
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-secondary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Tugas -->
                                            <div class="modal fade" id="hapusModal<?= $task['task_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Tugas</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus tugas dengan Nama: <b><?= htmlspecialchars($task['task_name']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
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