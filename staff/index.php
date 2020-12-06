<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

function getStatus()
{
    global $sqlconnection;
    $checkOnlineQuery = "SELECT status FROM tbl_staff WHERE staffID = {$_SESSION['uid']}";

    if ($result = $sqlconnection->query($checkOnlineQuery))
    {
        if ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            return $row['status'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - Staff</title>

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


        <?php
        if ($_SESSION['user_role'] == "cameriere")
        {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="order.php">
                <i class="fas fa-fw fa-book"></i>
                <span>Ordini</span></a>
            </li>
          ';
        }

        if ($_SESSION['user_role'] == "chef")
        {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="kitchen.php">
                <i class="fas fa-fw fa-utensils"></i>
                <span>Cucina</span></a>
            </li>
            ';
        }

        ?>

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

            <!-- Page Content -->
            <h1>Pannello amministrazione - Staff</h1>
            <hr>

            <div class="row">
                <div class="col-lg-9">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-utensils"></i>
                            Ultimi ordini pronti</div>
                        <div class="card-body">
                            <table id="orderTable" class="table table-striped table-bordered width="100%" cellspacing="0">
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card mb-3 text-center">
                        <div class="card-header">
                            <i class="fas fa-chart-bar""></i>
                            Stato</div>
                        <div class="card-body">
                            Ciao, <b><?php echo $_SESSION['username'] ?></b><hr>
                            Ruolo : <b><?php echo ucfirst($_SESSION['user_role']) ?></b><hr>
                            <form action="statuschange.php" method="POST">
                                <input type="hidden" id="staffid" name="staffid" value=" <?php echo $_SESSION['uid']; ?>" />
                                <?php if (getStatus() == 'Online') echo "<input type='submit' class='btn btn-success myBtn' name='btnStatus' value='Online'>";
                                else echo "<input type='submit' class='btn btn-danger myBtn' name='btnStatus' value='Offline'>" ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span></span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Modal Logout -->
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

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">

    $( document ).ready(function() {
        refreshTableOrder();
    });

    function refreshTableOrder() {
        $( "#orderTable" ).load( "displayorder.php?cmd=currentready" );
    }

    setInterval(function(){ refreshTableOrder(); }, 3000);
</script>
</body>
</html>
