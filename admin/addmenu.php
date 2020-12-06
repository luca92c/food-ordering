<?php
//Aggiungo nuova categoria (es. primio, secondi, dessert, ecc...)
if (isset($_POST['addmenu'])){
    if (!empty($_POST['menuname'])){
        $menuname = $sqlconnection->real_escape_string($_POST['menuname']);

        $addMenuQuery = "INSERT INTO tbl_menu (menuName) VALUES ('{$menuname}')";

        if ($sqlconnection->query($addMenuQuery) === true){
            header("Location: menu.php");
        }
    }
}