<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $jenis_colaboration = mysqli_real_escape_string($conn, $_POST['jenis_colaboration']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);

    if (empty($jenis_colaboration) || empty($link) ) {
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
        $query = "INSERT INTO collaboration (jenis_colaboration, mahasiswa_id, link) 
                  VALUES ('$jenis_colaboration', '$mahasiswa_id', '$link')";
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
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nohp = mysqli_real_escape_string($conn, $_POST['nohp']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    if (empty($nama) || empty($nim) ) {
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
        $query = "UPDATE mahasiswa SET nama = '$nama', nim = '$nim', nohp = '$nohp'
                  WHERE id = '$id' ";
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
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $query = "DELETE FROM collaboration WHERE id = '$id' AND mahasiswa_id = '$mahasiswa_id'";
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
            
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-secondary">List Mahasiswa</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Nim</th>
                                            <th>No Telp</th>
                                            <th>Nilai</th>
                                            <th >Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ?");
                                        $stmt->bind_param("i", $_GET['assignment_id']);

                                        $stmt->execute();
                                        $collaboration = $stmt->get_result();
                                        ?>

                                        <?php $i = 1; ?>
                                        <?php foreach ($collaboration as $colab) : ?>
                                            <?php
$sql = "SELECT * FROM  mahasiswa WHERE id = ?";
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("i", $colab['mahasiswa_id']);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$mahasiswa = $result->fetch_assoc();
// var_dump($result);die;
$sql2 = "SELECT * FROM grades WHERE  mahasiswa_id = ?";
$stmt2 = $conn->prepare($sql2);

// Assuming $colab['submission_id'] and $colab['mahasiswa_id'] are the values you want to use
$stmt2->bind_param("i", $colab['mahasiswa_id']);

// Execute the query
$stmt2->execute();

// Get the result
$resultaall = $stmt2->get_result();
// var_dump($resultaall);die;




                                                 ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($mahasiswa['nama']); ?></td>
                                                <td><?= htmlspecialchars($mahasiswa['nim']); ?></td>
                                                <td><?= htmlspecialchars($mahasiswa['nohp']); ?></td>
                                                <td>
                                                <?php
                                                if ($resultaall->num_rows > 0) {
                                                    while ($row = $resultaall->fetch_assoc()) {
                                                        echo "<li>{$row['grade']} - {$row['kriteria']}</li>";
                                                    }
                                                } else {
                                                    echo "Tidak ada data nilia.";
                                                }
                                                ?>

                                                </td>

                                                <td >
                                                    <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $colab['submission_id'] ?>">Edit</a>
                                               
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Tugas -->
                                            <div class="modal fade" id="editModal<?= $colab['submission_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Mahasiswa</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="id" value="<?= $colab['mahasiswa_id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="task_description">Nama:</label>
                                                                    <input type="text" name="nama" id="nama" class="form-control" value="<?=  $mahasiswa['nama'] ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="task_description">NIM:</label>
                                                                    <input type="text" name="nim" id="nim" class="form-control" value="<?=  $mahasiswa['nim'] ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="task_description">No Hp:</label>
                                                                    <input type="text" name="nohp" id="nohp" class="form-control" value="<?=  $mahasiswa['nohp'] ?>" required>
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-secondary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus Tugas -->
                                            <div class="modal fade" id="hapusModal<?= $colab['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Tugas</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus Collaboration dengan jenis: <b><?= htmlspecialchars($colab['jenis_colaboration']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id" value="<?= $colab['id']; ?>">
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