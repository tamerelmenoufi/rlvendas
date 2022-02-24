<?php
include "../../lib/includes.php";

if (!isset($_SESSION['usuario'])) {
    //header("Location: ../index.php");
}
?>

<div id="wrapper">

    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <?php include "header.php"; ?>

        <!-- Main Content -->
        <div id="content" style="position: relative">
            <div class="loading">
                <div class="loader"></div>
            </div>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div id="palco">
                    <?php include "content.php"; ?>
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



<script>

</script>