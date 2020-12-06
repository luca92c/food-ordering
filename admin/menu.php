<?php
include ("../functions.php");
include ("addmenu.php");
include ("additem.php");
include ("deletemenu.php");

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

    <title>Menu - Admin</title>

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
                <li class="breadcrumb-item active">Menu</li>
            </ol>

            <!-- Page Content -->
            <h1>Gestione Menu</h1>
            <hr>
            <p>Gestisci il menu aggiungendo, modificando o eliminando i vari piatti o categorie</p>

            <div class="card mb-3 border-primary">
                <div class="card-header">
                    <i class="fas fa-chart-area"></i>
                    Listino menu
                    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addMenuModal">Aggiungi categoria</button>

                </div>
                <div class="card-body">

                    <?php
                    $menuQuery = "SELECT * FROM tbl_menu";

                    if ($menuResult = $sqlconnection->query($menuQuery))
                    {
                        if ($menuResult->num_rows == 0)
                        {
                            echo "<span><label>No category right now.</label></span>";
                        }

                        while ($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC))
                        { ?>
                            <div class="card mb-3 border-primary">
                                <div class="card-header">
                                    <i class="fas fa-chart-area"></i>
                                    <?php echo $menuRow["menuName"]; ?>
                                    <button class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#deleteModal" data-category="<?php echo $menuRow["menuName"]; ?>" data-menuid="<?php echo $menuRow["menuID"]; ?>">Elimina</button>
                                    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addItemModal" data-category="<?php echo $menuRow["menuName"]; ?>" data-menuid="<?php echo $menuRow["menuID"]; ?>">Aggiungi</button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <tr>
                                            <td>#</td>
                                            <td>Nome Piatto</td>
                                            <td>Prezzo (€)</td>
                                            <td>Opzioni</td>
                                        </tr>
                                        <?php
                                        $menuItemQuery = "SELECT * FROM tbl_menuitem WHERE menuID = " . $menuRow["menuID"];
                                        if ($menuItemResult = $sqlconnection->query($menuItemQuery))
                                        {
                                            if ($menuItemResult->num_rows == 0)
                                            {
                                                echo "<td colspan='4' class='text-center'>Nessun piatto per questa categoria</td>";
                                            }

                                            $itemno = 1;
                                            // Recupero e mostro a frontend i piatti in base alla categoria
                                            while ($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC))
                                            { ?>
                                                <tr>
                                                    <td><?php echo $itemno++; ?></td>
                                                    <td><?php echo $menuItemRow["menuItemName"] ?></td>
                                                    <td><?php echo $menuItemRow["price"] ?></td>
                                                    <td>
                                                        <a href="#editItemModal" data-toggle="modal" data-itemname="<?php echo $menuItemRow["menuItemName"] ?>" data-itemprice="<?php echo $menuItemRow["price"] ?>" data-menuid="<?php echo $menuRow["menuID"] ?>" data-itemid="<?php echo $menuItemRow["itemID"] ?>">Modifica</a>
                                                        <a href="deleteitem.php?itemID=<?php echo $menuItemRow["itemID"] ?>&menuID=<?php echo $menuRow["menuID"] ?>"> Elimina</a></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>
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

<!-- Modale Logout -->
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

<!-- aggiunta categoria -->
<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Aggiungi categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addmenuform" method="POST">
                    <div class="form-group">
                        <label class="col-form-label">Categoria:</label>
                        <input type="text" required="required" class="form-control" name="menuname" placeholder="Primi piatti, secondi piatti, dessert, ecc..." >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancella</button>
                <button type="submit" form="addmenuform" class="btn btn-success" name="addmenu">Aggiungi</button>
            </div>
        </div>
    </div>
</div>

<!-- aggiunta piatto -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Aggiungi menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="additemform" method="POST">
                    <div class="form-group">
                        <label class="col-form-label">Nome:</label>
                        <input type="text" required="required" class="form-control" name="itemName" placeholder="Pasta al ragù, spaghetti allo scoglio, frittura di pesce, ecc..." >
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Prezzo (€):</label>
                        <input type="text" required="required" class="form-control" name="itemPrice" placeholder="10.00" >
                        <input type="hidden" name="menuID" id="menuid">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancella</button>
                <button type="submit" form="additemform" class="btn btn-success" name="addItem">Aggiungi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modifica piatto -->
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Modifica Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edititemform" action="edititem.php" method="POST">
                    <div class="form-group">
                        <label class="col-form-label">Nome:</label>
                        <input type="text" required="required" id="itemname" class="form-control" name="itemName" placeholder="Pasta al ragù, spaghetti allo scoglio, frittura di pesce, ecc..." >
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Prezzo (€):</label>
                        <input type="text" required="required" id="itemprice" class="form-control" name="itemPrice" placeholder="10.00" >
                        <input type="hidden" name="menuID" id="menuid">
                        <input type="hidden" name="itemID" id="itemid">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancella</button>
                <button type="submit" form="edititemform" class="btn btn-primary" name="btnedit">Modifica</button>
            </div>
        </div>
    </div>
</div>

<!-- Eliminazione categoria -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Sei sicuro di voler eliminare la categoria?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Selezionando "Elimina" verranno eliminati tutti i piatti appartenenti alla categoria</div>
            <div class="modal-footer">
                <form id="deletemenuform" method="POST">
                    <input type="hidden" name="menuID" id="menuid">
                </form>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancella</button>
                <button type="submit" form="deletemenuform" class="btn btn-danger" name="deletemenu">Elimina</button>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin.min.js"></script>

<script>
    $('#addItemModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('menuid');
        var category = button.data('category');

        var modal = $(this);
        modal.find('.modal-title').text('Add new menu (' + category +')');
        modal.find('.modal-body #menuid').val(id);
    });

    $('#editItemModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var menuid = button.data('menuid');
        var itemid = button.data('itemid');
        var itemname = button.data('itemname');
        var itemprice = button.data('itemprice');

        var modal = $(this);
        modal.find('.modal-body #menuid').val(menuid);
        modal.find('.modal-body #itemid').val(itemid);
        modal.find('.modal-body #itemname').val(itemname);
        modal.find('.modal-body #itemprice').val(itemprice);
    });


    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('menuid');
        var category = button.data('category');

        var modal = $(this);
        modal.find('.modal-body').html('Selezionando "Elimina" verranno cancellati anche tutti i piatti della categoria <strong>'+ category +' </strong>');
        modal.find('.modal-footer #menuid').val(id);
    });
</script>

<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).hide();
        });
    }, 1000);
</script>
</body>
</html>
