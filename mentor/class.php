<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $class_description = mysqli_real_escape_string($conn, $_POST['class_description']);
    $class_image = $_FILES['class_image']['name']; // Nama gambar yang diunggah
    $class_image_tmp = $_FILES['class_image']['tmp_name']; // Lokasi sementara gambar yang diunggah

    // Tentukan lokasi penyimpanan gambar
    $image_path = "../img/" . $class_image;

    if (empty($class_name) || empty($class_description)) {
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
        if (move_uploaded_file($class_image_tmp, $image_path)) {
            // Gambar berhasil diunggah, tambahkan data ke database
            $query = "INSERT INTO class (class_name, mentor_id, class_description, class_image) 
                      VALUES ('$class_name', '$mentor_id', '$class_description', '$class_image')";
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
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengunggah Gambar!',
                    text: 'Mohon periksa jenis dan ukuran gambar.',
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
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $class_description = mysqli_real_escape_string($conn, $_POST['class_description']);

    // Cek apakah ada gambar yang diunggah
    if (!empty($_FILES['class_image']['name'])) {
        $class_image = $_FILES['class_image']['name']; // Nama gambar yang diunggah
        $class_image_tmp = $_FILES['class_image']['tmp_name']; // Lokasi sementara gambar yang diunggah

        // Tentukan lokasi penyimpanan gambar
        $image_path = "../img/" . $class_image;

        // Upload gambar ke direktori
        if (move_uploaded_file($class_image_tmp, $image_path)) {
            // Gambar berhasil diunggah, perbarui data ke database termasuk gambar
            $query = "UPDATE class 
                      SET class_name = '$class_name',
                          class_description = '$class_description', class_image = '$class_image' 
                      WHERE class_id = '$class_id'";
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
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengunggah Gambar!',
                    text: 'Mohon periksa jenis dan ukuran gambar.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    } else {
        // Tidak ada gambar yang diunggah, perbarui data ke database tanpa gambar
        $query = "UPDATE class 
                  SET class_name = '$class_name',
                      class_description = '$class_description'
                  WHERE class_id = '$class_id'";
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
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);

    $query = "DELETE FROM class WHERE class_id = '$class_id'";
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
                                <i class="fas fa-plus-square"></i> Kelola Kelas
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="class_name">Nama Kelas:</label>
                                        <input type="text" class="form-control" id="class_name" name="class_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_description">Deskripsi Kelas:</label>
                                        <textarea class="form-control" id="class_description" name="class_description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="class_image">Gambar Kelas:</label>
                                        <input type="file" class="form-control" id="class_image" name="class_image" accept="image/*" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-secondary w-100">Tambah Kelas</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-secondary">Data Kelas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Aksi</th>
                                            <th>Nama Kelas</th>
                                            <th>Mentor</th>
                                            <th>Deskripsi Kelas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $stmt = $conn->prepare("SELECT c.class_id, c.class_name, m.nama, c.class_description, c.mentor_id FROM class c
                                JOIN mentor m ON c.mentor_id = m.id
                                WHERE c.mentor_id = ?");
                                        $stmt->bind_param("i", $mentor_id);
                                        $mentor_id = $mentor_id;

                                        $stmt->execute();
                                        $classes = $stmt->get_result();
                                        ?>
                                        <?php foreach ($classes as $class) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-secondary" href="modules.php?id=<?= $class['class_id']; ?>"> <i class="fas fa-book-open"></i> Kelola Modules</a>
                                                    <br><br>
                                                    <a class="btn btn-sm btn-success" href="assignments.php?id=<?= $class['class_id']; ?>"> <i class="fas fa-book"></i> Kelola Assignments</a>
                                                    <br><br>
                                                    <a class="btn btn-sm btn-success" href="syllabus.php?id=<?= $class['class_id']; ?>"> <i class="fas fa-sync"></i> Kelola Syllabus</a>
                                                    <a class="btn btn-sm btn-info" href="task.php?id=<?= $class['class_id']; ?>"> <i class="fas fa-users"></i> Kelola Task</a>
                                                </td>
                                                <td><?= htmlspecialchars($class['class_name']); ?></td>
                                                <td><?= htmlspecialchars($class['nama']); ?></td>
                                                <td><?= htmlspecialchars($class['class_description']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $class['class_id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $class['class_id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Kelas -->
                                            <div class="modal fade" id="editModal<?= $class['class_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                                                <input type="hidden" name="class_id" value="<?= $class['class_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="class_name">Nama Kelas:</label>
                                                                    <input type="text" class="form-control" id="class_name" name="class_name" value="<?= htmlspecialchars($class['class_name']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="class_description">Deskripsi Kelas:</label>
                                                                    <textarea class="form-control" id="class_description" name="class_description" required><?= htmlspecialchars($class['class_description']); ?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="class_image">Gambar Kelas:</label>
                                                                    <input type="file" class="form-control" id="class_image" name="class_image" accept="image/*">
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-secondary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Kelas -->
                                            <div class="modal fade" id="hapusModal<?= $class['class_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Kelas</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus kelas dengan Nama: <b><?= htmlspecialchars($class['class_name']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="class_id" value="<?= $class['class_id']; ?>">
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