<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php

if (isset($_POST['change_status'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);
    $new_status = ''; // Inisialisasi variabel untuk status baru

    // Cek status tugas saat ini
    $checkStatusQuery = "SELECT task_status FROM tasks WHERE task_id = '$task_id'";
    $statusResult = mysqli_query($conn, $checkStatusQuery);

    if ($statusResult && mysqli_num_rows($statusResult) > 0) {
        $row = mysqli_fetch_assoc($statusResult);
        $current_status = $row['task_status'];

        // Tentukan status baru berdasarkan status saat ini
        if ($current_status === 'To Do') {
            $new_status = 'Doing';
        } elseif ($current_status === 'Doing') {
            $new_status = 'Done';
        }

        // Update status tugas ke status baru
        if (!empty($new_status)) {
            $updateStatusQuery = "UPDATE tasks SET task_status = '$new_status' WHERE task_id = '$task_id'";
            if (mysqli_query($conn, $updateStatusQuery)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Tugas Berhasil Diubah!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengubah Status Tugas!',
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
                    title: 'Tugas Sudah Selesai!',
                    text: 'Tugas ini sudah dalam status Done.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}

if (isset($_POST['submitDone'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);
    $new_status = 'Done'; // Inisialisasi variabel untuk status baru


    // Update status tugas ke status baru
    if (!empty($new_status)) {
        $updateStatusQuery = "UPDATE tasks SET task_status = '$new_status' WHERE task_id = '$task_id'";
        if (mysqli_query($conn, $updateStatusQuery)) {
            $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Tugas Berhasil Diubah!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                ";
        } else {
            $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengubah Status Tugas!',
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
                    title: 'Tugas Sudah Selesai!',
                    text: 'Tugas ini sudah dalam status Done.',
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <div class="row mt-4">
                        <!-- Tasks To Do -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    To Do
                                </div>
                                <div class="card-body">
                                    <?php
                                    $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'To Do' AND mahasiswa_id = ?");
                                    $sql->bind_param("i", $mahasiswa_id); // Bind the parameter
                                    
                                    // Execute the statement
                                    $result = $sql->execute();
                                    $mahasiswa_id = $mahasiswa_id;
                                    $result = $sql->execute();

                                    if ($result) {
                                        // Fetch the results
                                        $tasksToDo = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                                    
                                        // Do something with $tasksToDo
                                    } else {
                                        // Handle the error, for example:
                                        echo "Error: " . $sql->error;
                                    }
                                    
                                    foreach ($tasksToDo as $task) {
                                        echo '<div class="card mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">' . $task['task_name'] . '</h5>
                                                    <p class="card-text">' . $task['task_description'] . '</p>
                                                    <p class="card-text"><small class="text-muted">Due Date: ' . $task['task_due_date'] . '</small></p>
                                                </div>
                                            </div>';
                                    }
                                    // Check if $tasksToDo is empty
                                    if (empty($tasksToDo)) {
                                        echo '<p>No tasks available.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Doing -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    Doing
                                </div>
                                <div class="card-body">
                                <?php
                                 $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'Doing' AND mahasiswa_id = ?");
                                 $sql->bind_param("i", $mahasiswa_id); // Bind the parameter
                                 
                                 // Execute the statement
                                 $result = $sql->execute();
                                 $mahasiswa_id = $mahasiswa_id;
                                 $result = $sql->execute();

                                 if ($result) {
                                     // Fetch the results
                                     $tasksDoing = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                                 
                                     // Do something with $tasksToDo
                                 } else {
                                     // Handle the error, for example:
                                     echo "Error: " . $sql->error;
                                 }
                                 

                                  
                                 
                                    foreach ($tasksDoing as $task) {
                                        echo '<div class="card mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">' . $task['task_name'] . '</h5>
                                                    <p class="card-text">' . $task['task_description'] . '</p>
                                                    <p class="card-text"><small class="text-muted">Due Date: ' . $task['task_due_date'] . '</small></p>
                                                </div>
                                            </div>';
                                    }
                                     // Check if $tasksDone is empty
                                     if (empty($tasksDoing)) {
                                        echo '<p>No tasks available.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Done -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    Done
                                </div>
                                <div class="card-body">
                                <?php
                                $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'Done' AND mahasiswa_id = ?");
                                $sql->bind_param("i", $mahasiswa_id); // Bind the parameter
                                
                                // Execute the statement
                                $result = $sql->execute();
                                $mahasiswa_id = $mahasiswa_id;
                                $result = $sql->execute();

                                if ($result) {
                                    // Fetch the results
                                    $tasksDone = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                                
                                    // Do something with $tasksToDo
                                } else {
                                    // Handle the error, for example:
                                    echo "Error: " . $sql->error;
                                }
                                    foreach ($tasksDone as $task) {
                                        echo '<div class="card mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">' . $task['task_name'] . '</h5>
                                                    <p class="card-text">' . $task['task_description'] . '</p>
                                                    <p class="card-text"><small class="text-muted">Due Date: ' . $task['task_due_date'] . '</small></p>
                                                </div>
                                            </div>';
                                    }
                                    // Check if $tasksDone is empty
                                    if (empty($tasksDone)) {
                                        echo '<p>No tasks available.</p>';
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
<br>
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

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Tasks</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                // Query atau proses untuk mengambil total tugas dari tabel task
                                                $totalTasksQuery = "SELECT COUNT(*) AS total_tasks FROM tasks";
                                                $totalTasksResult = mysqli_query($conn, $totalTasksQuery);
                                                $totalTasksRow = mysqli_fetch_assoc($totalTasksResult);
                                                echo $totalTasksRow['total_tasks'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Content Row -->


                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-9 mb-4">
                            <div class="row">
                                <?php $classes = mysqli_query($conn, "SELECT * FROM class"); ?>
                                <?php foreach ($classes as $class) : ?>
                                    <div class="col-lg-4 col-md-12 col-sm-12">

                                        <div class="card mb-5" style="width: 18rem;">
                                            <img src="../img/<?= $class['class_image']; ?>" class="card-img-top" alt="<?= $class['class_name']; ?>">
                                            <div class="card-body">
                                                <h5 class="text-dark card-title"><?= $class['class_name']; ?></h5>
                                                <p class="card-text"><?= $class['class_description']; ?></p>
                                                <a href="modules.php?class_id=<?= $class['class_id']; ?>" class="btn btn-primary w-100">Lihat Module <i class="fas fa-arrow-circle-right"></i></a>
                                                <br><br>
                                                <a href="assignments.php?class_id=<?= $class['class_id']; ?>" class="btn btn-success w-100">Lihat Assignments <i class="fas fa-arrow-circle-right"></i></a>
                                                <br><br>
                                                <a href="syllabus.php?class_id=<?= $class['class_id']; ?>" class="btn btn-danger w-100">Lihat Syllabus <i class="fas fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                        </div>

                        <div class="col-lg-3 mb-4">

                            <!-- Tasks -->

                            <?php
                            $stmt = $conn->prepare("SELECT * FROM tasks WHERE mahasiswa_id = ?");
                            $stmt->bind_param("i", $mahasiswa_id);
                            $mahasiswa_id = $mahasiswa_id; // ID mahasiswa yang telah diambil sebelumnya

                            $stmt->execute();
                            $tasks = $stmt->get_result();
                            ?>

                            <?php foreach ($tasks as $task) : ?>
                                <div class="card shadow mb-4" style="display: none;">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($task['task_name']); ?> (<b><?= htmlspecialchars($task['task_status']); ?></b>)</h6>
                                    </div>
                                    <div class="card-body">
                                        <b><small><u><?= htmlspecialchars($task['task_due_date']); ?></u></small></b>
                                        <p><?= htmlspecialchars($task['task_description']); ?></p>

                                        <?php if ($task['task_status'] === 'To Do') : ?>
                                            <form action="" method="POST" class="d-inline">
                                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                                <button type="submit" name="change_status" class="btn btn-success">Doing</button>
                                            </form>
                                            <form action="" method="POST" class="d-inline">
                                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                                <button type="submit" name="submitDone" class="btn btn-secondary">Done</button>
                                            </form>
                                        <?php elseif ($task['task_status'] === 'Doing') : ?>
                                            <form action="" method="POST" class="d-inline">
                                                <input type="hidden" name="task_id" value="<?= $task['task_id']; ?>">
                                                <button type="submit" name="change_status" class="btn btn-secondary">Done</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        </div>
                    </div>

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