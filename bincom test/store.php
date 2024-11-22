<?php
$conn = new mysqli("localhost", "root", "", "bincoma");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $polling_unit_id = intval($_POST['polling_unit']);
    $party_results = $_POST['party'];

    $query = "INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    foreach ($party_results as $party => $score) {
        $stmt->bind_param("isi", $polling_unit_id, $party, $score);
        $stmt->execute();
    }

    echo "Results successfully stored!";
}
?>