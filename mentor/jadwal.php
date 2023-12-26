<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $topik = mysqli_real_escape_string($conn, $_POST['topik']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $link_zoom = mysqli_real_escape_string($conn, $_POST['link_zoom']);
    $class_id = $_GET['id'];
    $module_document = '';
    $module_link = '';

    // Tentukan tipe modul dan simpan data sesuai tipe
    if (empty($topik) || empty($jam) || empty($tanggal) || empty($link_zoom) ) {
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
        $query = "INSERT INTO jadwal (topik, jam, tanggal,link_zoom,class_id) 
                  VALUES ('$topik', '$jam', '$tanggal', '$link_zoom', '$class_id')";
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

if (isset($_POST['edit_module'])) {
    // Ambil data dari form
    $topik = mysqli_real_escape_string($conn, $_POST['topik']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $link_zoom = mysqli_real_escape_string($conn, $_POST['link_zoom']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $class_id = $_GET['id'];

    // Tentukan lokasi penyimpanan dokumen atau tautan
    if (empty($topik) || empty($jam) || empty($tanggal) || empty($link_zoom) ) {
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
        $query = "UPDATE jadwal SET topik = '$topik', jam = '$jam', tanggal = '$tanggal', link_zoom = '$link_zoom'
                  WHERE id = '$id' AND class_id = '$class_id'";
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

if (isset($_POST['hapus_module'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $query = "DELETE FROM jadwal WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Jadwal Berhasil Dihapus!',
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
                                <i class="fas fa-plus-square"></i> Kelola Jadwal
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="module_title">Topik:</label>
                                        <input type="text" class="form-control" id="topik" name="topik" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="module_title">Link Zoom:</label>
                                        <input type="text" class="form-control" id="link_zoom" name="link_zoom" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="module_title">Tanggal:</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="module_title">Jam:</label>
                                        <input type="text" class="form-control" id="jam" name="jam" placeholder="ex : 19:00 - 15:00 WIB" required>
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
                            <h6 class="m-0 font-weight-bold text-dark">Data Jadwal Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Topik</th>
                                            <th>Link</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['id']); // Ambil ID kelas dari parameter URL
                                        $stmt = $conn->prepare("SELECT * FROM jadwal WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $jadwals = $stmt->get_result();
                                        ?>
                                        <?php foreach ($jadwals as $jadwal) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($jadwal['topik']); ?></td>
                                                <td><a target="_blank" href="<?= $jadwal['link_zoom'] ?>"><?= $jadwal['link_zoom'] ?></a></td>
                                                <td><?= htmlspecialchars($jadwal['tanggal']); ?></td>
                                                <td><?= htmlspecialchars($jadwal['jam']); ?></td>

                                                <td>
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $jadwal['id'] ?>">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $jadwal['id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Modul -->
                                            <div class="modal fade" id="editModal<?= $jadwal['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Jadwal</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="id" value="<?= $jadwal['id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="module_title">Topik:</label>
                                                                    <input type="text" class="form-control" id="topik" name="topik" required value="<?= $jadwal['topik']  ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="module_title">Link Zoom:</label>
                                                                    <input type="text" class="form-control" id="link_zoom" name="link_zoom" required value="<?= $jadwal['link_zoom']  ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="module_title">Tanggal:</label>
                                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= $jadwal['tanggal']  ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="module_title">Jam:</label>
                                                                    <input type="text" class="form-control" id="jam" name="jam" placeholder="ex : 19:00 - 15:00 WIB" required value="<?= $jadwal['jam']  ?>">
                                                                </div>

                                                                <button type="submit" name="edit_module" class="btn btn-primary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>





                                            <!-- Modal Hapus Modul -->
                                            <div class="modal fade" id="hapusModal<?= $jadwal['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Jadwal</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus Jadwal dengan Nama: <b><?= htmlspecialchars($jadwal['topik']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id" value="<?= $jadwal['id']; ?>">
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