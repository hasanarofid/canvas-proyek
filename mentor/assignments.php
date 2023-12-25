<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $assignment_title = mysqli_real_escape_string($conn, $_POST['assignment_title']);
    $assignment_type = mysqli_real_escape_string($conn, $_POST['assignment_type']);
    $class_id = $_GET['id'];
    $assignment_document = '';
    $assignment_link = '';

    // Tentukan tipe Assignment dan simpan data sesuai tipe
    if ($assignment_type === 'document' && isset($_FILES['assignment_document'])) {
        $assignment_document = mysqli_real_escape_string($conn, $_FILES['assignment_document']['name']);
        $assignment_document_tmp = $_FILES['assignment_document']['tmp_name'];

        // Tentukan lokasi penyimpanan dokumen
        $document_path = "../documents/" . $assignment_document;

        if (!empty($assignment_title) && move_uploaded_file($assignment_document_tmp, $document_path)) {
            $insertQuery = "INSERT INTO assignments (assignment_title, assignment_document, is_link, class_id) 
                            VALUES ('$assignment_title', '$assignment_document', 0, '$class_id')";

            if (mysqli_query($conn, $insertQuery)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Assignment Berhasil Ditambahkan!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menambahkan Assignment!',
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
    } elseif ($assignment_type === 'link') {
        // Jika tipe Assignment adalah tautan
        $assignment_link = mysqli_real_escape_string($conn, $_POST['assignment_link']);

        if (!empty($assignment_title) && !empty($assignment_link)) {
            $insertQuery = "INSERT INTO assignments (assignment_title, assignment_document, is_link, class_id) 
                            VALUES ('$assignment_title', '$assignment_link', 1, '$class_id')";

            if (mysqli_query($conn, $insertQuery)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Assignment Berhasil Ditambahkan!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menambahkan Assignment!',
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

if (isset($_POST['edit_assignment'])) {
    // Ambil data dari form
    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    $assignment_title = mysqli_real_escape_string($conn, $_POST['assignment_title']);
    $assignment_type = mysqli_real_escape_string($conn, $_POST['assignment_type']);

    // Tentukan lokasi penyimpanan dokumen atau tautan
    if ($assignment_type === 'file') {
        // Jika tipe Assignment adalah "Dokumen," cek dan proses unggahan dokumen
        if (!empty($_FILES['assignment_document']['name'])) {
            $assignment_document = $_FILES['assignment_document']['name']; // Nama dokumen yang diunggah
            $assignment_document_tmp = $_FILES['assignment_document']['tmp_name']; // Lokasi sementara dokumen yang diunggah

            // Tentukan lokasi penyimpanan dokumen
            $document_path = "../documents/" . $assignment_document;

            // Upload dokumen ke direktori
            if (move_uploaded_file($assignment_document_tmp, $document_path)) {
                // Dokumen berhasil diunggah, perbarui data ke database termasuk dokumen
                $query = "UPDATE assignments 
                          SET assignment_title = '$assignment_title',
                              assignment_document = '$assignment_document', is_link = 0
                          WHERE assignment_id = '$assignment_id'";
                if (mysqli_query($conn, $query)) {
                    $script = "
                        Swal.fire({
                            icon: 'success',
                            title: 'Assignment Berhasil di Edit!',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    ";
                } else {
                    $script = "
                        Swal.fire({
                            icon: 'error',
                            title: 'Assignment Gagal Di-Edit!',
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
            $query = "UPDATE assignments 
                      SET assignment_title = '$assignment_title', is_link = 0
                      WHERE assignment_id = '$assignment_id'";
            if (mysqli_query($conn, $query)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Assignment Berhasil di Edit!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Assignment Gagal Di-Edit!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            }
        }
    } else {
        // Jika tipe Assignment adalah "Tautan," ambil tautan dari input teks
        $assignment_document = mysqli_real_escape_string($conn, $_POST['assignment_document']);

        // Perbarui data ke database untuk tautan
        $query = "UPDATE assignments 
                  SET assignment_title = '$assignment_title', assignment_document = '$assignment_document', is_link = 1
                  WHERE assignment_id = '$assignment_id'";
        if (mysqli_query($conn, $query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Assignment Berhasil di Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Assignment Gagal Di-Edit!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}

if (isset($_POST['hapus_assignment'])) {
    $assignment_id = mysqli_real_escape_string($conn, $_POST['assignment_id']);

    $query = "DELETE FROM assignments WHERE assignment_id = '$assignment_id'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Assignment Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Assignment Gagal Di-Hapus!',
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
                                <i class="fas fa-plus-square"></i> Kelola Assignment
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="assignment_title">Judul Assignment:</label>
                                        <input type="text" class="form-control" id="assignment_title" name="assignment_title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="assignment_type">Tipe Assignment:</label>
                                        <select class="form-control" id="assignment_type" name="assignment_type" required>
                                            <option value="document">Dokumen</option>
                                            <option value="link">Tautan</option>
                                        </select>
                                    </div>

                                    <!-- Jika Tipe Assignment adalah Dokumen -->
                                    <div id="document_upload" class="form-group">
                                        <label for="assignment_document">Unggah Dokumen:</label>
                                        <input type="file" class="form-control" id="assignment_document" name="assignment_document" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    </div>

                                    <!-- Jika Tipe Assignment adalah Tautan -->
                                    <div id="link_input" class="form-group" style="display: none;">
                                        <label for="assignment_link">Tautan Assignment:</label>
                                        <input type="text" class="form-control" id="assignment_link" name="assignment_link">
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-secondary w-100">Tambah Assignment</button>
                                </form>

                                <script>
                                    // Tampilkan/masukkan bidang yang sesuai berdasarkan tipe Assignment yang dipilih
                                    document.getElementById("assignment_type").addEventListener("change", function() {
                                        var assignmentType = this.value;
                                        var documentUpload = document.getElementById("document_upload");
                                        var linkInput = document.getElementById("link_input");

                                        if (assignmentType === "document") {
                                            documentUpload.style.display = "block";
                                            linkInput.style.display = "none";
                                        } else if (assignmentType === "link") {
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
                            <h6 class="m-0 font-weight-bold text-dark">Data Assignment Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Assignment</th>
                                            <th>Tipe Assignment</th>
                                            <th>Assignment</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['id']); // Ambil ID kelas dari parameter URL
                                        $stmt = $conn->prepare("SELECT * FROM assignments WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $assignments = $stmt->get_result();
                                        ?>
                                        <?php foreach ($assignments as $assignment) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($assignment['assignment_title']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($assignment['is_link'] == 1) {
                                                        echo "Link";
                                                    } else {
                                                        echo "Dokumen";
                                                    }
                                                    ?>
                                                </td>
                                                <?php if ($assignment['is_link'] == 1) : ?>
                                                    <td><a target="_blank" href="<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php else : ?>
                                                    <td><a target="_blank" download href="../documents/<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php endif; ?>
                                                <td>
                                                    <a href="grades.php?assignment_id=<?= $assignment['assignment_id'] ?>" class="btn btn-sm btn-success">Kelola Nilai Mahasiswa</a>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $assignment['assignment_id'] ?>">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $assignment['assignment_id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Assignment -->
                                            <div class="modal fade" id="editModal<?= $assignment['assignment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Assignment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="assignment_title">Nama Assignment:</label>
                                                                    <input type="text" class="form-control" id="assignment_title" name="assignment_title" value="<?= htmlspecialchars($assignment['assignment_title']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="assignment_type">Tipe Assignment:</label>
                                                                    <select class="form-control" id="assignment_type" name="assignment_type" disabled required>
                                                                        <option value="file" <?= $assignment['is_link'] == 0 ? 'selected' : ''; ?>>Dokumen</option>
                                                                        <option value="link" <?= $assignment['is_link'] == 1 ? 'selected' : ''; ?>>Tautan</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="assignment_document">

                                                                    </label>
                                                                    <?php if ($assignment['is_link'] == 0) : ?>
                                                                        <input type="file" class="form-control" id="assignment_document" name="assignment_document">
                                                                    <?php else : ?>
                                                                        <input type="text" class="form-control" id="assignment_document" name="assignment_document" value="<?= htmlspecialchars($assignment['assignment_document']); ?>" required>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <button type="submit" name="edit_assignment" class="btn btn-primary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>





                                            <!-- Modal Hapus Assignment -->
                                            <div class="modal fade" id="hapusModal<?= $assignment['assignment_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Assignment</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus Assignment dengan Nama: <b><?= htmlspecialchars($assignment['assignment_title']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                                                <button type="submit" name="hapus_assignment" class="btn btn-danger">Hapus</button>
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