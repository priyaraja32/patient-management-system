<?php

include("../config/db.php");

$id = $_GET['id'] ?? '';

if(!empty($id)){

    $stmt = $conn->prepare(
    "DELETE FROM patients WHERE id=?");

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

header("Location:list.php");
exit;

?>