<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Admin</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body id="page-top">
<nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="index.php">Gestione ordini</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle fa-fw"></i>
            </a>
        </li>
    </ul>

</nav>

<div id="wrapper">
    <!------------------ Sidebar ------------------->
    <ul class="sidebar navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="menu.php">
                <i class="fas fa-fw fa-utensils"></i>
                <span>Menu</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="sales.php">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Ordini</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="staff.php">
                <i class="fas fa-fw fa-user-circle"></i>
                <span>Staff</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-fw fa-power-off"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

    <div id="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Overview</li>
            </ol>

            <h1>Pannello di amministrazione</h1>
            <hr>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-utensils"></i>
                            Lista ordini</div>
                        <div class="card-body">
                            <table id="tblCurrentOrder" table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                <th>Ordine #</th>
                                <th>Menu</th>
                                <th>Piatto</th>
                                <th class='text-center'>Quantità</th>
                                <th class='text-center'>Stato</th>
                                </thead>

                                <tbody id="tblBodyCurrentOrder">
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer small text-muted"><i>La dashboard viene aggiornata ogni 5 secondi</i></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-chart-bar""></i>
                            Staff</div>
                        <div class="card-body">
                            <table table class="table table-bordered text-center" width="100%" cellspacing="0">
                                <tr>
                                    <td><b>Nome</b></td>
                                    <td><b>Ruolo</b></td>
                                    <td><b>Stato</b></td>
                                </tr>

                                <?php
                                $displayStaffQuery = "SELECT username, role, status FROM tbl_staff";
                                if ($result = $sqlconnection->query($displayStaffQuery))
                                {
                                    while ($staff = $result->fetch_array(MYSQLI_ASSOC))
                                    {
                                        echo "<tr>";
                                        echo "<td>{$staff['username']}</td>";
                                        echo "<td>{$staff['role']}</td>";

                                        if ($staff['status'] == "Online")
                                        {
                                            echo "<td><span class=\"badge badge-success\">Online</span></td>";
                                        }

                                        if ($staff['status'] == "Offline")
                                        {
                                            echo "<td><span class=\"badge badge-secondary\">Offline</span></td>";
                                        }

                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span></span>
                </div>
            </div>
        </footer>

    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di volerti disconnettere?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleziona "Logout" se ci si vuole disconnettere</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancella</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>
<script type="text/javascript">

    $( document ).ready(function() {
        refreshTableOrder();
    });

    function refreshTableOrder() {
        $( "#tblBodyCurrentOrder" ).load( "displayorder.php?cmd=display" );
    }

    //refresh order current list every 3 secs
    setInterval(function(){ refreshTableOrder(); }, 3000);

</script>
</body>
</html>
