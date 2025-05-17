<?php
include 'db_connect.php'; // Database Connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $employees = $_POST['employees']; // Get employee names

    if ($id) {
        // UPDATE Evaluator Data
        $sql = "UPDATE evaluator_list SET firstname='$firstname', middlename='$middlename', lastname='$lastname', email='$email', gender='$gender', contact='$contact', address='$address', employees='$employees' WHERE id='$id'";
    } else {
        // INSERT New Evaluator
        $sql = "INSERT INTO evaluator_list (firstname, middlename, lastname, email, gender, contact, address, employees) VALUES ('$firstname', '$middlename', '$lastname', '$email', '$gender', '$contact', '$address', '$employees')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Data successfully saved!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
