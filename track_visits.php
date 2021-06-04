<link rel="stylesheet" type="text/css" href="style.css">

<div>
    <a href="http://localhost/hotel-db-project/index.html"><button type="button" id="bb">Homepage</button></a>
    <form method="post">
        <h2>Covid Protocol Customer Tracking</h2>
        Insert customer's NFC ID:
        <input type="text" name="nfc_id" placeholder="nfc_id">
        <input name="set" type="submit">
    </form>
</div>
<br><br>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include 'connection.php';

    $conn = connect();

    $nfc_id = isset($_POST['nfc_id']) ? $_POST['nfc_id'] : false;

    $nfc_id = $nfc_id == "" ? "nfc_id" : $nfc_id;

    $sql = "select nfc_id, venue_id, entrance_tmst, exit_tmst from visit where nfc_id = " . $nfc_id . " order by entrance_tmst;";



    $result = $conn->query($sql);

    if ($nfc_id > 0) {
        echo "<div>";
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
    } else {
        echo "<p>Enter the info above</p>";
    }

    $conn->close();
}
?>