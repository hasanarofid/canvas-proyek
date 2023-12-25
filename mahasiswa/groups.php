<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);

    // Pastikan data yang diterima adalah valid
    if (empty($group_name)) {
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
        // Periksa apakah mahasiswa sudah menjadi anggota grup
        $query = "SELECT * FROM group_members WHERE mahasiswa_id = '$mahasiswa_id' AND group_id IN (SELECT group_id FROM groups WHERE group_name = '$group_name')";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Jika mahasiswa sudah menjadi anggota grup sebelumnya
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Anda Sudah Menjadi Anggota Grup Ini!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            // Jika mahasiswa belum menjadi anggota grup, tambahkan data ke tabel group_members
            $query = "INSERT INTO group_members (group_id, mahasiswa_id) 
                      VALUES ((SELECT group_id FROM groups WHERE group_name = '$group_name'), '$mahasiswa_id')";
            if (mysqli_query($conn, $query)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Gabung Grup Berhasil!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gabung Grup Gagal!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            }
        }
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
                    // Periksa apakah mahasiswa sudah menjadi anggota grup atau belum
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM group_members WHERE mahasiswa_id = ?");
                    $stmt->bind_param("i", $mahasiswa_id);
                    $mahasiswa_id = $mahasiswa_id;
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $memberCount = $row['COUNT(*)'];

                    if ($memberCount == 0) {
                        // Jika mahasiswa belum menjadi anggota grup
                    ?>
                        <div class="mb-3">
                            <p>
                                <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fas fa-plus-square"></i> Gabung Groups
                                </a>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="group_name">Pilih Grup:</label>
                                            <select class="form-control" id="group_name" name="group_name" required>
                                                <option value="" disabled selected>Pilih Grup</option>
                                                <?php
                                                $stmt = $conn->prepare("SELECT group_id, group_name FROM groups");
                                                $stmt->execute();
                                                $groups = $stmt->get_result();
                                                foreach ($groups as $group) {
                                                    echo '<option value="' . htmlspecialchars($group['group_name']) . '">' . htmlspecialchars($group['group_name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-secondary w-100">Gabung Group</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                        // Jika mahasiswa sudah menjadi anggota grup, tampilkan daftar anggota pada grupnya
                        $gid = query("SELECT group_id FROM group_members WHERE mahasiswa_id = $mahasiswa_id")[0];
                        $grid = $gid["group_id"];
                        $kel = query("SELECT group_name FROM groups WHERE group_id = $grid")[0];
                    ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-secondary"><?= $kel["group_name"]; ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama Anggota</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $stmt = $conn->prepare("SELECT mahasiswa_id FROM group_members WHERE group_id = $grid");
                                            $stmt->execute();
                                            $members = $stmt->get_result();
                                            foreach ($members as $member) {
                                                // Anda perlu mengganti query ini dengan yang sesuai untuk mendapatkan nama anggota
                                                $stmt = $conn->prepare("SELECT nama FROM mahasiswa WHERE id = ?");
                                                $stmt->bind_param("i", $member['mahasiswa_id']);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $row = $result->fetch_assoc();

                                                echo '<tr>
                                    <td>' . $i . '</td>
                                    <td>' . htmlspecialchars($row['nama']) . '</td>
                                </tr>';
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
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