<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bincoma";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected LGA ID
$lga_id = isset($_GET['lga_id']) ? intval($_GET['lga_id']) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Summed Results for LGA</title>
</head>
<body>
    <h1>Select an LGA</h1>
    <form method="GET">
        <label for="lga">Choose an LGA:</label>
        <select name="lga_id" id="lga">
            <option value="">-- Select LGA --</option>
            <?php
            // Fetch all LGAs
            $lga_query = "SELECT lga_id, lga_name FROM lga";
            $result = $conn->query($lga_query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['lga_id'] == $lga_id) ? "selected" : "";
                    echo "<option value='{$row['lga_id']}' $selected>{$row['lga_name']}</option>";
                }
            } else {
                echo "<option value=''>No LGAs found</option>";
            }
            ?>
        </select>
        <button type="submit">Get Results</button>
    </form>

    <?php if ($lga_id > 0): ?>
        <h2>Results for Selected LGA</h2>
        <p>Selected LGA ID: <?php echo $lga_id; ?></p> <!-- Debugging Output -->
        <table border="1">
            <tr>
                <th>Party</th>
                <th>Total Score</th>
            </tr>
            <?php
            // Fetch summed results for the selected LGA
            $sql = "SELECT apr.party_abbreviation, SUM(apr.party_score) AS total_score
            FROM announced_pu_results apr
            WHERE apr.polling_unit_uniqueid IN (
                SELECT pu.uniqueid 
                FROM polling_unit pu 
                WHERE pu.lga_id = ?
            )
            GROUP BY apr.party_abbreviation";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lga_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display results
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Party</th>
                    <th>Total Score</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['party_abbreviation']}</td>
                    <td>{$row['total_score']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found for the selected LGA.";
    }

##else {
  ##  echo "Please select an LGA.";
  # }
            ?>
        </table>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
