<?php
function log_report($conn, $report_type, $content, $patient_id = null)
{
    $query = "INSERT INTO Reports (ReportType, Content, PatientID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $report_type, $content, $patient_id);
    $stmt->execute();
    $stmt->close();
}
?>