<link rel="stylesheet" type="text/css" href="style.css">

<body>
    <div class="container">
        <a href="http://localhost/hotel-db-project/index.html"><button type="button" id="bb">Homepage</button></a>
        <form method="post">
            <h2>Statistics</h2>
            Select stat category:
            <select id="stat_cat" name="stat_cat">
                <option value="" selected="selected">(none)</option>
                <option value=1>Most used venues</option>
                <option value=2>Most frequently used services</option>
                <option value=3>Sort services by number of users</option>
            </select>
            Select date range:
            <select id="date_range" name="date_range">
                <option value="" selected="selected">(none)</option>
                <option value=0>Last month</option>
                <option value=1>Last year</option>
            </select>
            <br><br>
            Select age range:
            <select id="age_range" name="age_range">
                <option value="" selected="selected">(none)</option>
                <option value=1>20-40</option>
                <option value=2>41-60</option>
                <option value=3>61+</option>
            </select>
            <input name="set" type="submit">
        </form>
    </div>
    <br>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            include 'connection.php';

            $conn = connect();

            $stat_cat = isset($_POST['stat_cat']) ? $_POST['stat_cat'] : false;
            $date_range = isset($_POST['date_range']) ? $_POST['date_range'] : false;
            $age_range = isset($_POST['age_range']) ? $_POST['age_range'] : false;


            switch ($age_range) {
                case 1:
                    $min_age = 20;
                    $max_age = 40;
                    break;
                case 2:
                    $min_age = 41;
                    $max_age = 60;
                    break;
                case 3:
                    $min_age = 61;
                    $max_age = 100;
                    break;
                default:
                    $min_age = 0;
                    $max_age = 100;
            }

            if($stat_cat == 1){
                $interval = $date_range == 0 ? 'MONTH' : 'YEAR';

                $sql = "select venue_id, COUNT(venue_id) as visit_freq
                FROM customer as c 
                inner join visit as v on c.nfc_id=v.nfc_id
                WHERE timestampdiff(YEAR,c.date_of_birth,NOW()) BETWEEN ".$min_age." and ".$max_age." and entrance_tmst BETWEEN DATE_SUB(NOW(), INTERVAL 1 ".$interval.") AND NOW()
                GROUP BY venue_id
                ORDER BY visit_freq DESC
                LIMIT 5";
            } else if ($stat_cat == 2) {
                $interval = $date_range == 0 ? 'MONTH' : 'YEAR';

                $sql = "select service_id, COUNT(service_id) as service_freq 
                FROM customer as c 
                inner join visit as v on c.nfc_id=v.nfc_id 
                inner join provided_to as pt on pt.venue_id=v.venue_id 
                WHERE timestampdiff(YEAR,c.date_of_birth,NOW()) BETWEEN ".$min_age." and ".$max_age." and service_id!=7 and entrance_tmst BETWEEN DATE_SUB(NOW(), INTERVAL 1 ".$interval.") AND NOW()
                GROUP BY service_id
                ORDER BY service_freq DESC
                LIMIT 5";
            } else if ($stat_cat == 3) {
                $interval = $date_range == 0 ? 'MONTH' : 'YEAR';

                $sql = "select service_id, COUNT(distinct c.nfc_id) as service_pop
                FROM customer as c
                inner join visit as v on c.nfc_id = v.nfc_id
                inner join provided_to as pt on pt.venue_id = v.venue_id
                WHERE timestampdiff(YEAR,c.date_of_birth,NOW()) BETWEEN ".$min_age." and ".$max_age." and service_id!=7 and entrance_tmst BETWEEN DATE_SUB(NOW(), INTERVAL 1 ".$interval.") AND NOW()
                GROUP BY service_id
                ORDER BY service_pop DESC
                LIMIT 5";
            }


            $result = $conn->query($sql);

            if ($stat_cat != "" and $date_range != "" and $age_range != "") {

                switch ($stat_cat) {
                    case 1:
                        $column1_title = 'venue ID';
                        $column2_title = '#Visits';

                        $column1_name = 'venue_id';
                        $column2_name = 'visit_freq';
                        break;
                    case 2:
                        $column1_title = 'service ID';
                        $column2_title = '#Visits';

                        $column1_name = 'service_id';
                        $column2_name = 'service_freq';
                        break;
                    case 3:
                        $column1_title = 'service ID';
                        $column2_title = '#Uses';

                        $column1_name = 'service_id';
                        $column2_name = 'service_pop';
                        break;
                }

                echo "<div class='container'>";
                echo "<table>";
                echo "<tr>";
                echo "<th> ".$column1_title." </th>";
                echo "<th> ".$column2_title." </th>";
                echo "</tr>";

                while ($row = $result->fetch_array()) {
                    echo "<tr>";
                    echo "<td>" . $row[$column1_name] . "</td>";
                    echo "<td>" . $row[$column2_name] . "</td>";
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

</body>