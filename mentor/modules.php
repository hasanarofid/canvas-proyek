<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $module_title = mysqli_real_escape_string($conn, $_POST['module_title']);
    $module_type = mysqli_real_escape_string($conn, $_POST['module_type']);
    $class_id = $_GET['id'];
    $module_document = '';
    $module_link = '';

    // Tentukan tipe modul dan simpan data sesuai tipe
    if ($module_type === 'document' && isset($_FILES['module_document'])) {
        $module_document = mysqli_real_escape_string($conn, $_FILES['module_document']['name']);
        $module_document_tmp = $_FILES['module_document']['tmp_name'];

        // Tentukan lokasi penyimpanan dokumen
        $document_path = "../documents/" . $module_document;

        if (!empty($module_title) && move_uploaded_file($module_document_tmp, $document_path)) {
            $insertQuery = "INSERT INTO modules (module_title, module_document, is_link, class_id) 
                            VALUES ('$module_title', '$module_document', 0, '$class_id')";

            if (mysqli_query($conn, $insertQuery)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Modul Berhasil Ditambahkan!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menambahkan Modul!',
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
    } elseif ($module_type === 'link') {
        // Jika tipe modul adalah tautan
        $module_link = mysqli_real_escape_string($conn, $_POST['module_link']);

        if (!empty($module_title) && !empty($module_link)) {
            $insertQuery = "INSERT INTO modules (module_title, module_document, is_link, class_id) 
                            VALUES ('$module_title', '$module_link', 1, '$class_id')";

            if (mysqli_query($conn, $insertQuery)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Modul Berhasil Ditambahkan!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menambahkan Modul!',
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
                    title: 'Data Tidak Lengkap!',
                    text: 'Mohon isi semua kolom.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}

if (isset($_POST['edit_module'])) {
    // Ambil data dari form
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);
    $module_title = mysqli_real_escape_string($conn, $_POST['module_title']);
    $module_type = mysqli_real_escape_string($conn, $_POST['module_type']);

    // Tentukan lokasi penyimpanan dokumen atau tautan
    if ($module_type === 'file') {
        // Jika tipe modul adalah "Dokumen," cek dan proses unggahan dokumen
        if (!empty($_FILES['module_document']['name'])) {
            $module_document = $_FILES['module_document']['name']; // Nama dokumen yang diunggah
            $module_document_tmp = $_FILES['module_document']['tmp_name']; // Lokasi sementara dokumen yang diunggah

            // Tentukan lokasi penyimpanan dokumen
            $document_path = "../documents/" . $module_document;

            // Upload dokumen ke direktori
            if (move_uploaded_file($module_document_tmp, $document_path)) {
                // Dokumen berhasil diunggah, perbarui data ke database termasuk dokumen
                $query = "UPDATE modules 
                          SET module_title = '$module_title',
                              module_document = '$module_document', is_link = 0
                          WHERE module_id = '$module_id'";
                if (mysqli_query($conn, $query)) {
                    $script = "
                        Swal.fire({
                            icon: 'success',
                            title: 'Modul Berhasil di Edit!',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    ";
                } else {
                    $script = "
                        Swal.fire({
                            icon: 'error',
                            title: 'Modul Gagal Di-Edit!',
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
        } else {
            // Tidak ada dokumen yang diunggah, perbarui data ke database tanpa dokumen
            $query = "UPDATE modules 
                      SET module_title = '$module_title', is_link = 0
                      WHERE module_id = '$module_id'";
            if (mysqli_query($conn, $query)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Modul Berhasil di Edit!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Modul Gagal Di-Edit!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            }
        }
    } else {
        // Jika tipe modul adalah "Tautan," ambil tautan dari input teks
        $module_document = mysqli_real_escape_string($conn, $_POST['module_document']);

        // Perbarui data ke database untuk tautan
        $query = "UPDATE modules 
                  SET module_title = '$module_title', module_document = '$module_document', is_link = 1
                  WHERE module_id = '$module_id'";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Modul Berhasil di Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Modul Gagal Di-Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}

if (isset($_POST['hapus_module'])) {
    $module_id = mysqli_real_escape_string($conn, $_POST['module_id']);

    $query = "DELETE FROM modules WHERE module_id = '$module_id'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Modul Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Modul Gagal Di-Hapus!',
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
                                <i class="fas fa-plus-square"></i> Kelola Modul
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="module_title">Judul Modul:</label>
                                        <input type="text" class="form-control" id="module_title" name="module_title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="module_type">Tipe Modul:</label>
                                        <select class="form-control" id="module_type" name="module_type" required>
                                            <option value="document">Dokumen</option>
                                            <option value="link">Tautan</option>
                                        </select>
                                    </div>

                                    <!-- Jika Tipe Modul adalah Dokumen -->
                                    <div id="document_upload" class="form-group">
                                        <label for="module_document">Unggah Dokumen:</label>
                                        <input type="file" class="form-control" id="module_document" name="module_document" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    </div>

                                    <!-- Jika Tipe Modul adalah Tautan -->
                                    <div id="link_input" class="form-group" style="display: none;">
                                        <label for="module_link">Tautan Modul:</label>
                                        <input type="text" class="form-control" id="module_link" name="module_link">
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-secondary w-100">Tambah Modul</button>
                                </form>

                                <script>
                                    // Tampilkan/masukkan bidang yang sesuai berdasarkan tipe modul yang dipilih
                                    document.getElementById("module_type").addEventListener("change", function() {
                                        var moduleType = this.value;
                                        var documentUpload = document.getElementById("document_upload");
                                        var linkInput = document.getElementById("link_input");

                                        if (moduleType === "document") {
                                            documentUpload.style.display = "block";
                                            linkInput.style.display = "none";
                                        } else if (moduleType === "link") {
                                            documentUpload.style.display = "none";
                                            linkInput.style.display = "block";
                                        } else {
                                            documentUpload.style.display = "none";
                                            linkInput.style.display = "none";
                                        }
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <?php $id = $_GET['id']; ?>
                    <?php $class = query("SELECT * FROM class WHERE class_id = $id")[0]; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Data Modul Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Modul</th>
                                            <th>Tipe Modul</th>
                                            <th>Modul</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['id']); // Ambil ID kelas dari parameter URL
                                        $stmt = $conn->prepare("SELECT * FROM modules WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $modules = $stmt->get_result();
                                        ?>
                                        <?php foreach ($modules as $module) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($module['module_title']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($module['is_link'] == 1) {
                                                        echo "Link";
                                                    } else {
                                                        echo "Dokumen";
                                                    }
                                                    ?>
                                                </td>
                                                <?php if ($module['is_link'] == 1) : ?>
                                                    <td><a target="_blank" href="<?= $module['module_document'] ?>"><?= $module['module_document'] ?></a></td>
                                                <?php else : ?>
                                                    <td><a target="_blank" download href="../documents/<?= $module['module_document'] ?>"><?= $module['module_document'] ?></a></td>
                                                <?php endif; ?>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $module['module_id'] ?>">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $module['module_id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Modul -->
                                            <div class="modal fade" id="editModal<?= $module['module_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Modul</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="module_id" value="<?= $module['module_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="module_title">Nama Modul:</label>
                                                                    <input type="text" class="form-control" id="module_title" name="module_title" value="<?= htmlspecialchars($module['module_title']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="module_type">Tipe Modul:</label>
                                                                    <select class="form-control" id="module_type" name="module_type" disabled required>
                                                                        <option value="file" <?= $module['is_link'] == 0 ? 'selected' : ''; ?>>Dokumen</option>
                                                                        <option value="link" <?= $module['is_link'] == 1 ? 'selected' : ''; ?>>Tautan</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="module_document">

                                                                    </label>
                                                                    <?php if ($module['is_link'] == 0) : ?>
                                                                        <input type="file" class="form-control" id="module_document" name="module_document">
                                                                    <?php else : ?>
                                                                        <input type="text" class="form-control" id="module_document" name="module_document" value="<?= htmlspecialchars($module['module_document']); ?>" required>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <button type="submit" name="edit_module" class="btn btn-primary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>





                                            <!-- Modal Hapus Modul -->
                                            <div class="modal fade" id="hapusModal<?= $module['module_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Modul</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus modul dengan Nama: <b><?= htmlspecialchars($module['module_title']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="module_id" value="<?= $module['module_id']; ?>">
                                                                <button type="submit" name="hapus_module" class="btn btn-danger">Hapus</button>
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