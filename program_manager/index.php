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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Classes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $totalClassesQuery = "SELECT COUNT(*) AS total_classes FROM class";
                                                $totalClassesResult = mysqli_query($conn, $totalClassesQuery);
                                                $totalClassesRow = mysqli_fetch_assoc($totalClassesResult);
                                                echo $totalClassesRow['total_classes'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Students</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                // Query atau proses untuk mengambil total mahasiswa dari tabel mahasiswa
                                                $totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM mahasiswa";
                                                $totalStudentsResult = mysqli_query($conn, $totalStudentsQuery);
                                                $totalStudentsRow = mysqli_fetch_assoc($totalStudentsResult);
                                                echo $totalStudentsRow['total_students'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Modules</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                // Query atau proses untuk mengambil total modul dari tabel modules
                                                $totalModulesQuery = "SELECT COUNT(*) AS total_modules FROM modules";
                                                $totalModulesResult = mysqli_query($conn, $totalModulesQuery);
                                                $totalModulesRow = mysqli_fetch_assoc($totalModulesResult);
                                                echo $totalModulesRow['total_modules'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>


                    <?php
                    // Query untuk Aktivitas Klik Tugas
                    $queryKlikTugas = "SELECT DATE(timestamp) as tanggal, COUNT(*) as jumlah
                        FROM activity_log
                        WHERE activity_type = 'klik_link' AND timestamp >= CURDATE() - INTERVAL 30 DAY
                        -- WHERE activity_type = 'klik_link' AND timestamp >= CURDATE() - INTERVAL 7 DAY
                        GROUP BY DATE(timestamp)
                        ORDER BY tanggal";

                    $resultKlikTugas = $conn->query($queryKlikTugas);

                    $dataKlikTugas = array();

                    while ($row = $resultKlikTugas->fetch_assoc()) {
                        $dataKlikTugas[] = array($row["tanggal"], (int)$row["jumlah"]);
                    }

                    // Query untuk Pengiriman Tugas
                    $queryPengirimanTugas = "SELECT DATE(timestamp) as tanggal, COUNT(*) as jumlah
                        FROM activity_log
                        -- WHERE activity_type = 'submit_assignment' AND timestamp >= CURDATE() - INTERVAL 7 DAY
                        WHERE activity_type = 'submit_assignment' AND timestamp >= CURDATE() - INTERVAL 30 DAY
                        GROUP BY DATE(timestamp)
                        ORDER BY tanggal";

                    $resultPengirimanTugas = $conn->query($queryPengirimanTugas);

                    $dataPengirimanTugas = array();

                    while ($row = $resultPengirimanTugas->fetch_assoc()) {
                        $dataPengirimanTugas[] = array($row["tanggal"], (int)$row["jumlah"]);
                    }

                    ?>

                    <div class="card my-4 p-3" style="border: 1px solid lightgreen;">
                        <div id="grafikKlikTugas" style="width:100%; height:400px;"></div>
                    </div>

                    <div class="card my-4 p-3" style="border: 1px solid skyblue;">
                        <div id="grafikPengirimanTugas" style="width:100%; height:400px;"></div>
                    </div>



                    <script>
                        // Data dari PHP
                        var dataKlikTugas = <?php echo json_encode($dataKlikTugas); ?>;
                        var dataPengirimanTugas = <?php echo json_encode($dataPengirimanTugas); ?>;

                        // Inisialisasi Grafik Aktivitas Klik Tugas
                        Highcharts.chart('grafikKlikTugas', {
                            chart: {
                                type: 'area'
                            },
                            title: {
                                text: 'Aktivitas Mengakses Materi Harian'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: 'Jumlah Akses'
                                }
                            },
                            series: [{
                                name: 'Klik Tugas',
                                data: dataKlikTugas
                            }]
                        });

                        // Inisialisasi Grafik Pengiriman Tugas
                        Highcharts.chart('grafikPengirimanTugas', {
                            chart: {
                                type: 'area'
                            },
                            title: {
                                text: 'Aktivitas Pengiriman Tugas Harian'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: 'Jumlah Pengiriman'
                                }
                            },
                            series: [{
                                name: 'Pengiriman Tugas',
                                data: dataPengirimanTugas
                            }]
                        });
                    </script>


                    <!-- Content Row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Canvas 2023</span>
                    </div>
                </div>
            </footer>
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



</body>

</html>