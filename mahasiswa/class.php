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
                    <div class=" mb-4">
                        <h1 class="h3 mb-0 text-gray-800">List Kelas Tersedia</h1>
                        <div style="border-bottom: 2px solid black;"> </div>
                    </div>

                    <div class="row">
                        <div class="col mb-4">
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