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

                <div class="row mt-4">
                        <!-- Tasks To Do -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    To Do
                                </div>
                                <div class="card-body">
                                    <?php
                                    $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'To Do' ");
                                    
                                    // Execute the statement
                                    $result = $sql->execute();
                                    // $mahasiswa_id = $mahasiswa_id;
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
                                 $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'Doing' ");
                                 
                                 // Execute the statement
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
                                $sql = $conn->prepare("SELECT * FROM tasks WHERE task_status = 'Done' ");
                                
                                // Execute the statement
                                $result = $sql->execute();
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