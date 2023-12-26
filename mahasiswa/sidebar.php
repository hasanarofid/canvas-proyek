<style>
 .sidebar .sidebar-brand {
    /* padding-top: 0rem; */
    /* padding-bottom: 1rem; */
/* margin-bottom: 90px; */
/* padding: 0;
margin: 0; */
padding-top: 0;
padding-right: 0;
padding-bottom: 7rem;
padding-left: 0;
}

/* Additional styles to make the logo larger */
.sidebar .sidebar-brand img {
    /* max-width: 100%;
    height: auto;
    width: 100%;
    max-height: 200px;  */
    width: 400px;
    height: 400px;
    margin-bottom: 80px;
}

@media (min-width: 768px) {
    .sidebar .sidebar-brand img {
        max-height: 150px; /* Adjust as needed for larger screens */
    }
}
</style>
<ul style="background-color: #97AEC0;" class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <img src="../img/logocanvas.png" alt="Logo" class="img-fluid" >
    </a> -->

    <a class="sidebar-brand" href="index.php">
        <img src="../img/logocanvas.png" alt="Logo" class="img-fluid">
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="task.php">
            <i class="fas fa-tasks"></i>
            <span>Kelola Tugas</span></a>
    </li>

    <li class="nav-item active" style="display: none;">
        <a class="nav-link" href="class.php">
            <i class="fas fa-list-alt"></i>
            <span>List Kelas</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="groups.php">
            <i class="fas fa-users"></i>
            <span>Groups</span></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="helps.php">
            <i class="fas fa-question-circle"></i>
            <span>Helps</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>