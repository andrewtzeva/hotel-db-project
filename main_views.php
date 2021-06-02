<form method="post">
<input type="text" name="choice_id" placeholder="choice_id">
<input name="set" type="submit">
</form>


<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        include 'connection.php';

        $conn = connect();

        $choice_id = isset($_POST['choice_id']) ? $_POST['choice_id'] : false;

        $choice_id = $choice_id == "" ? false : $choice_id;

        if ($choice_id == 0) {
            $sql = "select gs.service_id, gs.nfc_id, sc.cost, gs.charge_tmst from get_services as gs inner join service_cost as sc on gs.charge_tmst = sc.charge_tmst
            order by gs.service_id;";
        } else if ($choice_id == 1) {
            $sql = "select * from customer;";
        }

        $result = $conn->query($sql);

        if ($choice_id == 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th> Service ID </th>";
            echo "<th> NFC ID </th>";
            echo "<th> Cost </th>";
            echo "<th> Charge Datetime </th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";
        
            while($row = $result->fetch_array()) {
                echo "<tr>";
                echo "<td>".$row["service_id"]."</td>";
                echo "<td>".$row["nfc_id"]."</td>";
                echo "<td>".$row["cost"]."</td>";
                echo "<td>".$row["charge_tmst"]."</td>";
                echo "</tr>";
            }

            echo "</tbody>";

            echo "</table>";
            
        } else if ($choice_id == 1) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th> NFC ID </th>";
            echo "<th> Name </th>";
            echo "<th> Surname </th>";
            echo "<th> DoB </th>";
            echo "<th> ID Number </th>";
            echo "<th> ID Type </th>";
            echo "<th> ID Auth </th>";
            echo "</tr>";
            echo "</thead>";
            
            echo "<tbody>";
            while($row = $result->fetch_array()) {
                echo "<tr>";
                echo "<td>".$row["nfc_id"]."</td>";
                echo "<td>".$row["first_name"]."</td>";
                echo "<td>".$row["last_name"]."</td>";
                echo "<td>".$row["date_of_birth"]."</td>";
                echo "<td>".$row["id_number"]."</td>";
                echo "<td>".$row["id_type"]."</td>";
                echo "<td>".$row["id_auth"]."</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Enter the info above</p>";
        }

        $conn->close();
    }
?>