<?php
$conn = new mysqli("localhost", "root", "", "bincoma");
if (isset($_POST['state_id'])) {
    $state_id = intval($_POST['state_id']);
    $query = "SELECT lga_id, lga_name FROM lga WHERE state_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Select LGA</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['lga_id']}'>{$row['lga_name']}</option>";
    }
}
?>