<?php
include ("../functions.php");

if ((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level']))) header("Location: login.php");

if ($_SESSION['user_level'] != "staff") header("Location: login.php");

// non mostro nulla se richiamo direttamente la pagina all'url /displayorder.php
if (empty($_GET['cmd'])) die();

// se presente la querystring cmd allora mostro la lista di ordini
if ($_GET['cmd'] == 'currentorder')
{
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
					IN ( 'waiting','preparing','ready')
				";

    if ($orderResult = $sqlconnection->query($displayOrderQuery))
    {
        $currentspan = 0;

        if ($orderResult->num_rows == 0)
        {
            echo "<tr><td class='text-center' colspan='7' >Nessun ordine presente</td></tr>";
        }

        else
        {
            while ($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC))
            {
                $rowspan = getCountID($orderRow["orderID"], "orderID", "tbl_orderdetail");

                if ($currentspan == 0) $currentspan = $rowspan;

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
                    }

                    echo "<td class='text-center' rowspan=" . $rowspan . "><span class='{$color}'>" . $orderRow['status'] . "</span></td>";
                    echo "<td class='text-center' rowspan=" . $rowspan . ">";

                    switch ($orderRow['status'])
                    {
                        case 'waiting':
                            echo "<button onclick='editStatus(this," . $orderRow['orderID'] . ")' class='btn btn-outline-primary' value = 'preparing'>In preparazione</button>";
                            echo "<button onclick='editStatus(this," . $orderRow['orderID'] . ")' class='btn btn-outline-success' value = 'ready'>Pronto</button>";
                            break;
                        case 'preparing':
                            echo "<button onclick='editStatus(this," . $orderRow['orderID'] . ")' class='btn btn-outline-success' value = 'ready'>Pronto</button>";
                            break;
                        case 'ready':
                            echo "<button onclick='editStatus(this," . $orderRow['orderID'] . ")' class='btn btn-outline-warning' value = 'finish'>Rimuovi</button>";
                            break;
                    }

                    echo "<button onclick='editStatus(this," . $orderRow['orderID'] . ")' class='btn btn-outline-danger' value = 'cancelled'>Cancella</button></td>";
                    echo "</td>";
                }

                echo "</tr>";
                $currentspan--;
            }
        }
    }
}

// mostro lista ordini che sono pronti (stato 'ready')
if ($_GET['cmd'] == 'currentready')
{
    $latestReadyQuery = "SELECT orderID FROM tbl_order WHERE status IN ( 'finish','ready') ";

    if ($result = $sqlconnection->query($latestReadyQuery))
    {
        if ($result->num_rows == 0)
        {
            echo "<tr><td class='text-center'>Nessun ordine pronto per essere servito</td></tr>";
        }

        while ($latestOrder = $result->fetch_array(MYSQLI_ASSOC))
        {
            echo "<tr><td><i class='fas fa-bullhorn' style='color:green;'></i><b> Ordine #" . $latestOrder['orderID'] . "</b> pronto per essere servito<a href='editstatus.php?orderID=" . $latestOrder['orderID'] . "'><i class='fas fa-check float-right'></i></a></td></tr>";
        }
    }
}