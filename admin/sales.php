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

    <title>Ordini - Admin</title>

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
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Ordini</li>
            </ol>

            <!-- Page Content -->
            <h1>Gestione ordini</h1>
            <hr>
            <p>Tutti gli ordini</p>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-chart-area"></i>
                    Statistiche ordini
                </div>
                <div class="card-body">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <tbody>
                        <tr>
                            <td>Oggi</td>
                            <td>€ <?php echo getSalesGrandTotal("DAY"); ?></td>
                        </tr>
                        <tr>
                            <td>Questa settimana</td>
                            <td>€ <?php echo getSalesGrandTotal("WEEK"); ?></td>
                        </tr>
                        <tr>
                            <td>Questo mese</td>
                            <td>€ <?php echo getSalesGrandTotal("MONTH"); ?></td>
                        </tr>
                        <tr class="table-info">
                            <td><b>Di sempre</b></td>
                            <td><b>€ <?php echo getSalesGrandTotal("ALLTIME"); ?></b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-chart-area"></i>
                    Lista ordini</div>
                <div class="card-body">
                    <table id="tblCurrentOrder" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <th>Ordine #</th>
                        <th>Menu</th>
                        <th>Nome</th>
                        <th class='text-center'>Quantità</th>
                        <th class='text-center'>Stato</th>
                        <th class='text-center'>Totale (€)</th>
                        <th class='text-center'>Data</th>
                        </thead>

                        <tbody id="tblBodyCurrentOrder">
                        <?php
                        $displayOrderQuery = "
                        SELECT O.orderID, M.menuName, OD.itemID,MI.menuItemName,OD.quantity,O.status, MI.price ,O.order_date
                        FROM tbl_order O
                        LEFT JOIN tbl_orderdetail OD
                        ON O.orderID = OD.orderID
                        LEFT JOIN tbl_menuitem MI
                        ON OD.itemID = MI.itemID
                        LEFT JOIN tbl_menu M
                        ON MI.menuID = M.menuID
                        ";

                        if ($orderResult = $sqlconnection->query($displayOrderQuery))
                        {
                            $currentspan = 0;
                            $total = 0;

                            if ($orderResult->num_rows == 0)
                            {
                                echo "<tr><td class='text-center' colspan='7' >Attualmente non ci sono ordini</td></tr>";
                            }
                            else
                            {
                                while ($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC))
                                {

                                    // Evito di mostrare lo stesso ID ordine per i piatti appartenenti allo stesso ordine
                                    $rowspan = getCountID($orderRow["orderID"], "orderID", "tbl_orderdetail");

                                    if ($currentspan == 0)
                                    {
                                        $currentspan = $rowspan;
                                        $total = 0;
                                    }

                                    // per ogni ordine calcolo il totale
                                    $total += ($orderRow['price'] * $orderRow['quantity']);

                                    echo "<tr>";

                                    if ($currentspan == $rowspan)
                                    {
                                        echo "<td rowspan=" . $rowspan . "># " . $orderRow['orderID'] . "</td>";
                                    }

                                    echo "
                              <td>" . $orderRow['menuName'] . "</td>
                              <td>" . $orderRow['menuItemName'] . "</td>
                              <td class='text-center'>" . $orderRow['quantity'] . "</td>
                            ";

                                    if ($currentspan == $rowspan)
                                    {

                                        $color = "badge";

                                        switch ($orderRow['status'])
                                        {
                                            case 'waiting':
                                                $color = "badge badge-warning";
                                                break;

                                            case 'preparing':
                                                $color = "badge badge-primary";
                                                break;

                                            case 'ready':
                                                $color = "badge badge-success";
                                                break;

                                            case 'cancelled':
                                                $color = "badge badge-danger";
                                                break;

                                            case 'finish':
                                                $color = "badge badge-success";
                                                break;

                                            case 'Completed':
                                                $color = "badge badge-success";
                                                break;
                                        }

                                        echo "<td class='text-center' rowspan=" . $rowspan . "><span class='{$color}'>" . $orderRow['status'] . "</span></td>";
                                        echo "<td rowspan=" . $rowspan . " class='text-center'>" . getSalesTotal($orderRow['orderID']) . "</td>";
                                        echo "<td rowspan=" . $rowspan . " class='text-center'>" . $orderRow['order_date'] . "</td>";
                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                    $currentspan--;
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
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

<!-- Modale di logout -->
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
</body>
</html>
