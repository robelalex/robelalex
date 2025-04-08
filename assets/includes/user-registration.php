<?php

// Establish connection
include("./db-con.php");

// Ensure that the only way to access this php is through the form
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $response = ['success' => false, 'type' =>'', "message" => '', "title" => '']; 


    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $email = $_POST['email'];
    $passwordHash;


// Check if the passwords match
if ($password !== $confirmPassword) {
    echo "Passwords do not match.";
    $reponse['success'] = false;
    $response['type'] = 'error';
    $response['message'] = 'Passwords do not match';
    $response['title'] = 'Error';
} else {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
}

    //  server side validation
    if(empty($username)) {
        echo "please enter a username";
    }else if (empty($password)) {
        echo "please enter a password";
    }else {

        $sql = "INSERT INTO users (username, password_hash, email,created_at, updated_at) 
                VALUES ('$username', '$passwordHash', '$email', NOW(), NOW())";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        }

    }

    mysqli_close($conn);

}

