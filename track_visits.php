<link rel="stylesheet" type="text/css" href="style.css">

<body>
    <div class="container">
        <a href="http://localhost/hotel-db-project/index.html"><button type="button" id="bb">Homepage</button></a>
        <form method="post">
            <h2>Covid Protocol Customer Tracking</h2>
            Insert customer's NFC ID:
            <input type="text" name="nfc_id" placeholder="nfc_id"><br /><br />
            Show customers in risk of infection:
            <select id="view_option" name="choice_id">
                <option value=0 selected="selected">Off</option>
                <option value=1>On</option>
            </select>
            <input name="set" type="submit">
        </form>
    </div>
    <br>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        include 'connection.php';

        $conn = connect();
        
        $nfc_id = isset($_POST['nfc_id']) ? $_POST['nfc_id'] : false;
        $nfc_id = $nfc_id == "" ? "nfc_id" : $nfc_id;

        $choice_id = isset($_POST['choice_id']) ? $_POST['choice_id']: false;


        $sql = "select nfc_id, venue_id, entrance_tmst, exit_tmst from visit where nfc_id = " . $nfc_id . " order by entrance_tmst;";

        $sql2 = "select * from customer as c where c.nfc_id in (select nfc_id from visit as v1 where venue_id in (select venue_id from visit as v2 where nfc_id = ".$nfc_id." and (v1.entrance_tmst BETWEEN v2.entrance_tmst and date_add(v2.exit_tmst, INTERVAL 1 hour) or v1.exit_tmst BETWEEN v2.entrance_tmst and date_add(v2.exit_tmst, INTERVAL 1 hour)))) and c.nfc_id != ".$nfc_id.";";



        $result = $conn->query($sql);

        if ($nfc_id > 0) {
            echo "<div class='container'>";
            echo "<h2>Infected Customer's Visits</h2>";
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
            echo "</div>";

            
            if($choice_id == 1){
                $result2 = $conn->query($sql2);

                echo "<div class='container'>";
                echo "<h2>Customers in Risk of Infection</h2>";
                echo "<table>";
                echo "<tr>";
                echo "<th> NFC ID </th>";
                echo "<th> Name </th>";
                echo "<th> Surname </th>";
                echo "<th> DoB </th>";
                echo "<th> ID Number </th>";
                echo "<th> ID Type </th>";
                echo "<th> ID Auth </th>";
                echo "</tr>";

                while ($row = $result2->fetch_array()) {
                    echo "<tr>";
                    echo "<td>" . $row["nfc_id"] . "</td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["date_of_birth"] . "</td>";
                    echo "<td>" . $row["id_number"] . "</td>";
                    echo "<td>" . $row["id_type"] . "</td>";
                    echo "<td>" . $row["id_auth"] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</div>";
            }


        } else {
            echo "<p>Enter the info above</p>";
        }

        $conn->close();
    }
    ?>
</body>