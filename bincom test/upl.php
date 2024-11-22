<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store New Polling Unit Results</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>Enter Results for a New Polling Unit</h1>
    <form id="resultForm" method="POST" action="store.php">
        <label for="state">State:</label>
        <select id="state" name="state">
            <option value="">Select State</option>
            <?php
            // Fetch States
            $conn = new mysqli("localhost", "root", "", "bincoma");
            $state_query = "SELECT state_id, state_name FROM states";
            $result = $conn->query($state_query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['state_id']}'>{$row['state_name']}</option>";
            }
            ?>
        </select><br><br>

        <label for="lga">LGA:</label>
        <select id="lga" name="lga">
            <option value="">Select LGA</option>
        </select><br><br>

        <label for="polling_unit">Polling Unit:</label>
        <select id="polling_unit" name="polling_unit">
            <option value="">Select Polling Unit</option>
        </select><br><br>

        <h2>Enter Results for All Parties</h2>
        <div id="partyResults">
            <?php
            // Fetch Parties
            $party_query = "SELECT DISTINCT party_abbreviation FROM announced_pu_results";
            $result = $conn->query($party_query);
            while ($row = $result->fetch_assoc()) {
                echo "<label for='party_{$row['party_abbreviation']}'>{$row['party_abbreviation']}:</label>";
                echo "<input type='number' name='party[{$row['party_abbreviation']}]' id='party_{$row['party_abbreviation']}' required><br>";
            }
            ?>
        </div><br>

        <button type="submit">Save Results</button>
    </form>
       
    <script>
        // Chained Dropdown Logic
        $(document).ready(function () {
            // Fetch LGAs based on State
            $('#state').change(function () {
                var state_id = $(this).val();
                if (state_id !== '') {
                    $.ajax({
                        url: 'local.php',
                        method: 'POST',
                        data: {state_id: state_id},
                        success: function (data) {
                            $('#lga').html(data);
                        }
                    });
                }
            });

            // Fetch Polling Units based on LGA
            $('#lga').change(function () {
                var lga_id = $(this).val();
                if (lga_id !== '') {
                    $.ajax({
                        url: 'fetchh.php',
                        method: 'POST',
                        data: {lga_id: lga_id},
                        success: function (data) {
                            $('#polling_unit').html(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>