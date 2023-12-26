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
                            <h6 class="m-0 font-weight-bold text-dark">Data Modul Untuk Kelas : <?= $class['class_name']; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
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
                 <div class="container-fluid">
                    <!-- DataTales Example -->
                    <?php $class_id = $_GET['class_id']; ?>
                    <?php $class = query("SELECT * FROM class WHERE class_id = $class_id")[0]; ?>
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
                                            <th>Judul</th>
                                            <th>Assignment</th>
                                            <th>Upload Submission</th>
                                            <th>Status Task</th>
                                            <th>Nilai</th>
                                            <th>Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $class_id = mysqli_real_escape_string($conn, $_GET['class_id']); // Ambil ID kelas dari parameter URL
                                        $mahasiswa_id = $mahasiswa_id; // Menggunakan ID mahasiswa yang sudah tersedia
                                        $stmt = $conn->prepare("SELECT * FROM assignments WHERE class_id = ?");
                                        $stmt->bind_param("i", $class_id);

                                        $stmt->execute();
                                        $assignments = $stmt->get_result();


                                   

                                        ?>
                                        <?php foreach ($assignments as $assignment) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($assignment['assignment_title']); ?></td>
                                                <?php if ($assignment['is_link'] == 1) : ?>
                                                    <td><a target="_blank" href="<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php else : ?>
                                                    <td><a target="_blank" download href="../documents/<?= $assignment['assignment_document'] ?>"><?= $assignment['assignment_document'] ?></a></td>
                                                <?php endif; ?>

                                                <?php
                                                // Cek apakah mahasiswa sudah mengirimkan submission untuk assignment ini
                                                $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND mahasiswa_id = ?");
                                                $stmt->bind_param("ii", $assignment['assignment_id'], $mahasiswa_id);
                                                $stmt->execute();
                                                $submission = $stmt->get_result()->fetch_assoc();

                                                if (!$submission) :
                                                    // Jika submission sudah ada, tampilkan tombol "Upload Submission"
                                                ?>
                                                    <td>
                                                        <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id']; ?>">
                                                            <input type="hidden" name="mahasiswa_id" value="<?= $mahasiswa_id; ?>">
                                                            <input type="file" class="form-control" id="submission_file" name="submission_file" accept=".pdf, image/jpeg, image/jpg, image/png, image/gif">
                                                            <button type="submit" name="upload_submission" class="btn btn-primary mt-2">Upload Submission</button>
                                                        </form>
                                                    </td>
                                                <?php else :
                                                    // Jika submission belum ada, tampilkan pesan
                                                    $submission_id = $submission['submission_id'];
                                                ?>
                                                    <td>
                                                        <p>Anda telah mengirimkan submission untuk tugas ini.</p>
                                                    </td>
                                                <?php endif; ?>

                                                <td>
                                                    <!-- status task mahasiswa -->
                                                <?php
                                                 $stmt = $conn->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND mahasiswa_id = ?");
                                                 $stmt->bind_param("ii", $assignment['assignment_id'], $mahasiswa_id);
                                                 $stmt->execute();
                                                 $sts = $stmt->get_result()->fetch_assoc();
                                               if ($sts) {
                                                echo htmlspecialchars($sts['status']);
                                                } else {
                                                    echo "Doing";
                                                }
                                                ?>
                                                </td>

                                                <td>
                                                    <!-- Tampilkan nilai sesuai submission_id dan mahasiswa_id -->
                                                    <?php
                                                    $stmt = $conn->prepare("SELECT grade,kriteria FROM grades WHERE submission_id = ? AND mahasiswa_id = ?");
                                                    $stmt->bind_param("ii", $submission_id, $mahasiswa_id);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $grade = $result->fetch_assoc();
                                                    // var_dump($grade);die;
                                                    if ($grade) {
                                                        echo htmlspecialchars($grade['grade']) .' | '.htmlspecialchars($grade['kriteria']) ;
                                                    } else {
                                                        echo "Belum dinilai";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $mahasiswa_id = $mahasiswa_id;

                                                    // Periksa apakah sudah ada feedback untuk tugas ini
                                                    $stmt = $conn->prepare("SELECT feedback FROM grades WHERE submission_id = ? AND mahasiswa_id = ?");
                                                    $stmt->bind_param("ii", $submission_id, $mahasiswa_id);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $existing_feedback = $result->fetch_assoc();
                                                    

                                                    if ($existing_feedback !== null && isset($existing_feedback['feedback'])) {
                                                        // Feedback is available, display it
                                                        echo htmlspecialchars($existing_feedback['feedback']);
                                                    } else {
                                                        // No feedback available, display a message or a form
                                                        echo '<p>No feedback available.</p>';
                                                        // Uncomment the following lines if you want to display a form
                                                        /*
                                                        ?>
                                                        <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="submission_id" value="<?= $submission_id; ?>">
                                                            <input type="hidden" name="mahasiswa_id" value="<?= $mahasiswa_id; ?>">
                                                            <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
                                                            <button type="submit" name="submit_feedback" class="btn btn-primary mt-2">Submit Feedback</button>
                                                        </form>
                                                        <?php
                                                        */
                                                    }
                                                    ?>
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