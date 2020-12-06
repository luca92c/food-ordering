<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

if (isset($_POST['sentorder']))
{
    if (isset($_POST['itemID']) && isset($_POST['itemqty']))
    {
        $arrItemID = $_POST['itemID'];
        $arrItemQty = $_POST['itemqty'];

        if (count($arrItemID) == count($arrItemQty))
        {
            $arrlength = count($arrItemID);
            $currentOrderID = getLastID("orderID", "tbl_order") + 1;

            insertOrderQuery($currentOrderID);

            for ($i = 0;$i < $arrlength;$i++)
            {
                insertOrderDetailQuery($currentOrderID, $arrItemID[$i], $arrItemQty[$i]);
            }

            updateTotal($currentOrderID);

            header("Location: index.php");
            exit();
        }
    }
}

function insertOrderDetailQuery($orderID, $itemID, $quantity)
{
    global $sqlconnection;
    $addOrderQuery = "INSERT INTO tbl_orderdetail (orderID ,itemID ,quantity) VALUES ('{$orderID}', '{$itemID}' ,{$quantity})";
    $sqlconnection->query($addOrderQuery);
}

function insertOrderQuery($orderID)
{
    global $sqlconnection;
    $addOrderQuery = "INSERT INTO tbl_order (orderID ,status ,order_date) VALUES ('{$orderID}' ,'waiting' ,CURDATE() )";
    $sqlconnection->query($addOrderQuery);
}