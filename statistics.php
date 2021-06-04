<link rel="stylesheet" type="text/css" href="style.css">
<div>
    <a href="http://localhost/hotel-db-project/index.html"><button type="button" id="bb">Homepage</button></a>
    <form method="post">
        <h2>Statistics</h2>
        Select stat category:
        <select id="stat_cat" name="stat_cat">
            <option value="" selected="selected">(none)</option>
            <option value="1">Most used venues</option>
            <option value="2">Most frequently used services</option>
            <option value="3">Sort services by number of users</option>
        </select>
        Select date range:
        <select id="date_range" name="date_range">
            <option value="" selected="selected">(none)</option>
            <option value="0">Last month</option>
            <option value="1">Last year</option>
        </select>
        <br><br>
        Select age range:
        <select id="stat_cat" name="stat_cat">
            <option value="" selected="selected">(none)</option>
            <option value="1">20-40</option>
            <option value="2">41-60</option>
            <option value="3">61+</option>
        </select>
        <input name="set" type="submit">
    </form>
</div>
<br><br>