<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>


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
                    <!-- DataTales Example -->
                    <?php $class_id = $_GET['class_id']; ?>
                    <?php $class = query("SELECT * FROM class WHERE class_id = $class_id")[0]; ?>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['class_id']); // Ambil ID kelas dari parameter URL
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
                                            </tr>

                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- DataTales Example -->
                    <?php $class_id = $_GET['class_id']; ?>
                    <?php $class = query("SELECT * FROM class WHERE class_id = $class_id")[0]; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Data Modul Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Modul</th>
                                            <th>Materi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['class_id']); // Ambil ID kelas dari parameter URL
                                        $stmt = $conn->prepare("SELECT * FROM modules WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $modules = $stmt->get_result();
                                        ?>
                                        <?php foreach ($modules as $module) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($module['module_title']); ?></td>
                                                <?php if ($module['is_link'] == 1) : ?>
                                                    <td><a class="log-activity" target="_blank" href="<?= $module['module_document'] ?>"><?= $module['module_document'] ?></a></td>
                                                <?php else : ?>
                                                    <td><a class="log-activity" target="_blank" download href="../documents/<?= $module['module_document'] ?>"><?= $module['module_document'] ?></a></td>
                                                <?php endif; ?>
                                            </tr>

                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>


                

                 <!-- Begin Page Content -->
                 




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

            $('#dataX2').DataTable({
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

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.log-activity').forEach(link => {
                link.addEventListener('click', function(e) {
                    logLinkClick(this.getAttribute('href'));
                });
            });
        });

        function logLinkClick(url) {
            console.log("Logging URL: " + url);
            // AJAX call ke server untuk log aktivitas
            var xhr = new XMLHttpRequest();
            xhr.open("POST", 'log_activity.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('activity_type=klik_link&activity_detail=' + encodeURIComponent(url));
        }
    </script>
</body>

</html>