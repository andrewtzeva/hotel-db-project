<link rel="stylesheet" type="text/css" href="style.css">
<div>
    <form method="post">
        <h2>Visits</h2>
        Select service:
        <select id="service_id" name="service_id">
            <option value="" selected="selected">(none)</option>
            <option value="1">Use of gym equipment</option>
            <option value="2">Food-drink serving (restaurant)</option>
            <option value="3">Drink serving (bar)</option>
            <option value="4">Use of sauna</option>
            <option value="5">Hair salon services</option>
            <option value="6">Meeting room reservation</option>
            <option value="7">Room reservation</option>
        </select>
        Select date:
        <input type="date" id="visit_date" name="visit_date" value="2021-05-28" min="2018-01-01" max="2030-12-31">
        <br><br>
        Select cost:
        <input type="text" name="service_cost" placeholder="Cost">
        <br><br>
        (WIP) || Select cost range:
        <input type="text" name="service_cost_min" placeholder="Minimum cost">
        <input type="text" name="service_cost_min" placeholder="Maximum cost">
        <input name="set" type="submit">
    </form>
</div>
<br><br>
<div>
    <form method="">
        <h2>Sales</h2>
        Select view:
        <select id="view_option" name="view_option">
            <option value="" selected="selected">(none)</option>
            <option value="0">per Service</option>
            <option value="0">per Customer</option>
        </select>
    </form>
</div>
<br><br>
<div>
    <form method="">
        <h2>Covid Protocol Customer Tracking</h2>
        Insert customer's NFC ID:
        <input type="text" name="nfc_id" placeholder="nfc_id">
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include 'connection.php';

    $conn = connect();

    $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : false;
    $visit_date = isset($_POST['visit_date']) ? $_POST['visit_date'] : false;
    $service_cost = isset($_POST['service_cost']) ? $_POST['service_cost'] : false;

    $service_id = $service_id == "" ? "pt.service_id" : $service_id;
    $service_cost = $service_cost == "" ? "sc.cost" : $service_cost;

    if ($visit_date) {
        $sql = "select v.nfc_id, v.venue_id, v.entrance_tmst, v.exit_tmst from visit as v inner join venue as ve on v.venue_id = ve.venue_id
                inner join provided_to as pt on ve.venue_id = pt.venue_id inner join customer as c on v.nfc_id = c.nfc_id inner join
                get_services as gs on c.nfc_id = gs.nfc_id inner join service_cost as sc on gs.charge_tmst = sc.charge_tmst
                where pt.service_id =" . $service_id . "
                and v.entrance_tmst between '" . $visit_date . "' and '" . $visit_date . " 23:59:59' and sc.cost=" . $service_cost . ";";
    } else {
        $sql = "select v.nfc_id, v.venue_id, v.entrance_tmst, v.exit_tmst from visit as v inner join venue as ve on v.venue_id = ve.venue_id
                inner join provided_to as pt on ve.venue_id = pt.venue_id inner join customer as c on v.nfc_id = c.nfc_id inner join
                get_services as gs on c.nfc_id = gs.nfc_id inner join service_cost as sc on gs.charge_tmst = sc.charge_tmst
                where pt.service_id =" . $service_id . "
                and sc.cost=" . $service_cost . ";";
    }



    $result = $conn->query($sql);

    if ($service_id > 0 or $service_cost > 0 or $visit_date) {
        echo "<table>";
        echo "<tr>";
        echo "<th> NFC ID </th>";
        echo "<th> venue ID </th>";
        echo "<th> Entrance Datetime </th>";
        echo "<th> Exit Datetime </th>";
        echo "</tr>";

        while ($row = $result->fetch_array()) {
            echo "<tr>";
            echo "<td>" . $row["nfc_id"] . "</td>";
            echo "<td>" . $row["venue_id"] . "</td>";
            echo "<td>" . $row["entrance_tmst"] . "</td>";
            echo "<td>" . $row["exit_tmst"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Enter the info above</p>";
    }

    $conn->close();
}
?>