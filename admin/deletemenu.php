<?php
// cancello menu
if (isset($_POST['deletemenu'])){
    if (isset($_POST['menuID'])){
        $menuID = $sqlconnection->real_escape_string($_POST['menuID']);

        // prima di cancellare il menu elimino tutti i piatti appartenenti al menu
        $deleteMenuItemQuery = "DELETE FROM tbl_menuitem WHERE menuID = {$menuID}";

        if ($sqlconnection->query($deleteMenuItemQuery) === true){
            // eliminazione del menu
            $deleteMenuQuery = "DELETE FROM tbl_menu WHERE menuID = {$menuID}";

            if ($sqlconnection->query($deleteMenuQuery) === true){
                header("Location: menu.php");
                exit();
            }
        }
    }
}