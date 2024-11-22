<?php
$conn = new mysqli("localhost", "root", "", "bincoma");
if (isset($_POST['lga_id'])) {
    $lga_id = intval($_POST['lga_id']);
    $query = "SELECT uniqueid, polling_unit_name FROM polling_unit WHERE lga_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $lga_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Select Polling Unit</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['uniqueid']}'>{$row['polling_unit_name']}</option>";
    }
}
?>