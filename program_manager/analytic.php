<!DOCTYPE html>
<html lang="en">
<!-- DataTables Buttons CSS and JS -->

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
                 
                    <!-- Content Row -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Data Analytic </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <div>
        <!-- Add your export buttons here -->
        <button id="export-all" class="btn btn-primary">Export All Data</button>
    </div>

                            <table class="table table-bordered" id="analis_tbl" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Kelompok</th>
                                            <th>Nilai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $stmt = $conn->prepare("SELECT * FROM mahasiswa ");

                                        $stmt->execute();
                                        $analisa = $stmt->get_result();
                                        ?>
                                        <?php foreach ($analisa as $analis) : ?>
                                            <?php
                                               $sql = "SELECT group_members.*, `groups`.group_name 
                                               FROM group_members
                                               JOIN `groups` ON group_members.group_id = `groups`.group_id
                                               WHERE group_members.mahasiswa_id = ?";
                                       
                                       
                                            $stmt = $conn->prepare($sql);
                                            
                                            // Bind the parameter
                                            $stmt->bind_param("i", $analis['id']);
                                            
                                            // Execute the query
                                            $stmt->execute();
                                            
                                            // Get the result
                                            $result = $stmt->get_result();

                                            $sql2 = "SELECT * FROM grades WHERE  mahasiswa_id = ?";
                                            $stmt2 = $conn->prepare($sql2);

                                            // Assuming $colab['submission_id'] and $colab['mahasiswa_id'] are the values you want to use
                                            $stmt2->bind_param("i", $analis['id']);

                                            // Execute the query
                                            $stmt2->execute();

                                            // Get the result
                                            $resultaall = $stmt2->get_result();
                                                ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($analis['nama']); ?></td>
                                                <td><?= htmlspecialchars($analis['nim']); ?></td>
                                                <td>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<li>{$row['group_name']}</li>";
                                                    }
                                                } else {
                                                    echo "No collaboration details found.";
                                                }
                                                ?>

                                                </td>
                                                <td><?php
                                                if ($resultaall->num_rows > 0) {
                                                    while ($row = $resultaall->fetch_assoc()) {
                                                        echo "<li>{$row['grade']} - {$row['kriteria']}</li>";
                                                    }
                                                } else {
                                                    echo "Tidak ada data nilai.";
                                                }
                                                ?></td>
<td>
    <button class="btn btn-sm btn-success export-row" >Export Nilai</button>
</td>
     
                                            </tr>


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
 $(document).ready(function () {
        var table = $('#analis_tbl').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ]
        });

        // Add custom buttons and their click event handlers
        $('#export-all').on('click', function () {
            table.button('0').trigger();
        });

        $('#export-selected').on('click', function () {
            var selectedRows = table.rows('.selected').data().toArray();
            // Implement your logic to export selectedRows data
            console.log('Exporting selected rows:', selectedRows);
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