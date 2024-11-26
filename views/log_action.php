<?php
function log_action($conn, $clinician_id, $action, $patient_id = null, $description = null)
{
    $query = "INSERT INTO AuditLog (ClinicianID, Action, PatientID, Description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isis", $clinician_id, $action, $patient_id, $description);
    $stmt->execute();
    $stmt->close();
}
?>