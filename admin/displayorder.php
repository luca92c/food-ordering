<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "admin") header("Location: login.php");

if (empty($_GET['cmd'])) die();
if ($_GET['cmd'] != 'display') die();

$displayOrderQuery = "
			SELECT O.orderID, M.menuName, OD.itemID,MI.menuItemName,OD.quantity,O.status
			FROM tbl_order O
			LEFT JOIN tbl_orderdetail OD
			ON O.orderID = OD.orderID
			LEFT JOIN tbl_menuitem MI
			ON OD.itemID = MI.itemID
			LEFT JOIN tbl_menu M
			ON MI.menuID = M.menuID
			WHERE O.status 
			IN ( 'waiting','preparing','ready','finish')
		";

if ($orderResult = $sqlconnection->query($displayOrderQuery))
{
    $currentspan = 0;

    // se non ci sono ordini
    if ($orderResult->num_rows == 0)
    {
        echo "<tr><td class='text-center' colspan='7' >No order for now :) </td></tr>";
    }

    else
    {
        while ($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC))
        {

            // evito di mostrare lo stesso ID ordine per ogni piatto appartenente alla stessa ordinazione
            $rowspan = getCountID($orderRow["orderID"], "orderID", "tbl_orderdetail");

            if ($currentspan == 0) $currentspan = $rowspan;

            echo "<tr>";

            if ($currentspan == $rowspan)
            {
                echo "<td class='text-center' rowspan=" . $rowspan . "># " . $orderRow['orderID'] . "</td>";
            }

            echo "
					<td>" . $orderRow['menuName'] . "</td>
					<td>" . $orderRow['menuItemName'] . "</td>
					<td class='text-center'>" . $orderRow['quantity'] . "</td>
				";

            if ($currentspan == $rowspan)
            {

                $color = "badge badge-warning";
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

                    case 'finish':
                        $color = "badge badge-success";
                        break;
                }

                echo "<td class='text-center' rowspan=" . $rowspan . "><span class='{$color}'>" . $orderRow['status'] . "</span></td>";
                echo "</td>";
            }

            echo "</tr>";
            $currentspan--;
        }
    }
}