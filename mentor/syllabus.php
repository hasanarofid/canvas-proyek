<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $class_id = $_GET['id'];

    $syllabus_document = $_FILES['syllabus_document']['name'];

    // Tentukan lokasi penyimpanan dokumen
    $document_path = "../documents/" . $syllabus_document;

    if (empty($syllabus_document)) {
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
        // Upload dokumen ke direktori
        if (move_uploaded_file($_FILES['syllabus_document']['tmp_name'], $document_path)) {
            // Dokumen berhasil diunggah, tambahkan data ke database
            $query = "INSERT INTO syllabus (syllabus_document, class_id) 
                      VALUES ('$syllabus_document', '$class_id')";
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
                    title: 'Gagal Mengunggah Dokumen!',
                    text: 'Mohon periksa jenis dan ukuran dokumen.',
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
    $syllabus_id = mysqli_real_escape_string($conn, $_POST['syllabus_id']);
    $syllabus_document = $_FILES['syllabus_document']['name'];
    $class_id = $_POST['class_id']; // Perhatikan ini, sesuaikan dengan input yang sesuai pada form edit

    // Tentukan lokasi penyimpanan dokumen
    $document_path = "../documents/" . $syllabus_document;

    if (empty($syllabus_document)) {
        // Jika tidak ada dokumen yang diunggah, perbarui data tanpa mengubah dokumen
        $query = "UPDATE syllabus 
                  SET class_id = '$class_id'
                  WHERE syllabus_id = '$syllabus_id'";
    } else {
        // Upload dokumen baru ke direktori
        if (move_uploaded_file($_FILES['syllabus_document']['tmp_name'], $document_path)) {
            // Dokumen berhasil diunggah, perbarui data termasuk dokumen
            $query = "UPDATE syllabus 
                      SET class_id = '$class_id',
                          syllabus_document = '$syllabus_document'
                      WHERE syllabus_id = '$syllabus_id'";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengunggah Dokumen!',
                    text: 'Mohon periksa jenis dan ukuran dokumen.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }

    // Eksekusi query untuk mengupdate data
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

if (isset($_POST['hapus'])) {
    $syllabus_id = mysqli_real_escape_string($conn, $_POST['syllabus_id']);

    // Hapus dokumen dari direktori
    $query = "SELECT syllabus_document FROM syllabus WHERE syllabus_id = '$syllabus_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $document_path = "../documents/" . $row['syllabus_document'];

    if (unlink($document_path)) {
        // Dokumen berhasil dihapus dari direktori, hapus data dari database
        $query = "DELETE FROM syllabus WHERE syllabus_id = '$syllabus_id'";
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
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menghapus Dokumen!',
                text: 'Mohon coba lagi.',
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
                                <i class="fas fa-plus-square"></i> Kelola Syllabus
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="syllabus_document">Dokumen Syllabus:</label>
                                        <input type="file" class="form-control" id="syllabus_document" name="syllabus_document" required>
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
                            <h6 class="m-0 font-weight-bold text-secondary">Data Syllabus</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Syllabus Document</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $stmt = $conn->prepare("SELECT * FROM syllabus WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);
                                        $class_id = $_GET['id'];

                                        $stmt->execute();
                                        $syllabusData = $stmt->get_result();
                                        ?>
                                        <?php foreach ($syllabusData as $syllabus) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><a href="../documents/<?= htmlspecialchars($syllabus['syllabus_document']); ?>"><?= htmlspecialchars($syllabus['syllabus_document']); ?></a></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $syllabus['syllabus_id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $syllabus['syllabus_id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Syllabus -->
                                            <div class="modal fade" id="editModal<?= $syllabus['syllabus_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Syllabus</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="syllabus_id" value="<?= $syllabus['syllabus_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="syllabus_document">Syllabus Document:</label>
                                                                    <input type="file" class="form-control" id="syllabus_document" name="syllabus_document" accept="application/pdf"> <!-- Sesuaikan dengan jenis dokumen yang diizinkan -->
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-secondary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Syllabus -->
                                            <div class="modal fade" id="hapusModal<?= $syllabus['syllabus_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Syllabus</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus syllabus ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="syllabus_id" value="<?= $syllabus['syllabus_id']; ?>">
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