<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if ($_SESSION['user_role'] != "cameriere")
{
    echo ("<script>window.alert('Sezione disponibile solo per i camerieri!'); window.location.href='index.php';</script>");
    exit();
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

    <title>Ordini - Staff</title>
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
                <span>Order</span></a>
            </li>
          ';
        }

        if ($_SESSION['user_role'] == "chef")
        {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="kitchen.php">
                <i class="fas fa-fw fa-utensils"></i>
                <span>Kitchen</span></a>
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
                <li class="breadcrumb-item active">Ordini</li>
            </ol>

            <!-- Page Content -->
            <h1>Gestioni ordini</h1>
            <hr>
            <p>Gestisci nuvoi ordini</p>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-utensils"></i>
                            Prendi ordinazione</div>
                        <div class="card-body">
                            <table class="table table-bordered text-center" width="100%" cellspacing="0">
                                <tr>
                                    <?php
                                    $menuQuery = "SELECT * FROM tbl_menu";

                                    if ($menuResult = $sqlconnection->query($menuQuery))
                                    {
                                        $counter = 0;
                                        while ($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC))
                                        {
                                            if ($counter >= 3)
                                            {
                                                echo "</tr>";
                                                $counter = 0;
                                            }

                                            if ($counter == 0)
                                            {
                                                echo "<tr>";
                                            }
                                            ?>
                                            <td><button style="margin-bottom:4px;white-space: normal;" class="btn btn-primary" onclick="displayItem(<?php echo $menuRow['menuID'] ?>)"><?php echo $menuRow['menuName'] ?></button></td>
                                            <?php
                                            $counter++;
                                        }
                                    }
                                    ?>
                                </tr>
                            </table>
                            <table id="tblItem" class="table table-bordered text-center" width="100%" cellspacing="0"></table>

                            <div id="qtypanel" hidden="">
                                Quantità : <input id="qty" required="required" type="number" min="1" max="50" name="qty" value="1" />
                                <button class="btn btn-info" onclick = "insertItem()">Fatto</button>
                                <br><br>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-chart-bar""></i>
                            Lista ordini</div>
                        <div class="card-body">
                            <form action="insertorder.php" method="POST">
                                <table id="tblOrderList" class="table table-bordered text-center" width="100%" cellspacing="0">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Prezzo</th>
                                        <th>Quantità</th>
                                        <th>Totale (€)</th>
                                    </tr>
                                </table>
                                <input class="btn btn-success" id="send-order-list" type="submit" name="sentorder" value="Conferma">
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

<!-- Logout Modale -->
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

<script>
    var currentItemID = null;
    $("#send-order-list").hide();


    function displayItem (id) {
        $.ajax({
            url : "displayitem.php",
            type : 'POST',
            data : { btnMenuID : id },

            success : function(output) {
                $("#tblItem").html(output);
            }
        });
    }

    function insertItem () {
        var id = currentItemID;
        var quantity = $("#qty").val();
        $.ajax({
            url : "displayitem.php",
            type : 'POST',
            data : {
                btnMenuItemID : id,
                qty : quantity
            },

            success : function(output) {
                $("#tblOrderList").append(output);
                $("#qtypanel").prop('hidden',true);
            }
        });

        $("#qty").val(1);
        $("#send-order-list").show();
    }

    function setQty (id) {
        currentItemID = id;
        $("#qtypanel").prop('hidden',false);
    }

    $(document).on('click','.deleteBtn', function(event){
        event.preventDefault();
        $(this).closest('tr').remove();

        if($("#tblOrderList tr").length == 1) {
            $("#send-order-list").hide();
        }
        return false;
    });

</script>
</body>
</html>