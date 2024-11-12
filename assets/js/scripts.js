function viewPatient(patient_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../views/patient_details.php?patient_id=" + patient_id, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById("patient-info").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
