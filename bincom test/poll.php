<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bincoma";
$conn = new mysqli($host, $user, $password, $dbname);
if($conn-> connect_error) {
    die("Connection failed:".$conn-> connect_error);
}
    if( isset($_GET['polling_unit_uniqueid'])) {
        $polling_unit_id = $_GET['polling_unit_uniqueid'];


        $sql ="SELECT party_abbreviation, party_score FROM announced_pu_results WHERE polling_unit_uniqueid =?";
        $stmt = $conn->prepare($sql);
        $stmt-> bind_param("i", $polling_unit_id);
        $stmt->execute();
        $result = $stmt-> get_result();


        // Display results
    echo "<h1>Results for Polling Unit ID: $polling_unit_id</h1>";
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Party</th>
                    <th>Score</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['party_abbreviation'] . "</td>
                    <td>" . $row['party_score'] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found for this Polling Unit ID.</p>";
    }
} else {
    echo "<p>Please provide a Polling Unit ID in the URL (e.g., ?polling_unit_uniqueid=8).</p>";
}

// Close connection
$conn->close();
?>


    


