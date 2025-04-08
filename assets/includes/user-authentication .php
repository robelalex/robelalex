<?php

    // Establish connection
    include("./db-con.php");

    // Ensure that the only way to access this php is through the form
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // response for the notification 
        $response = ['success' => false, 'type' =>'', "message" => '', "title" => '']; 
               
        // Retrieve username and password from the form
        $entered_username = $_POST['username'];
        $entered_password = $_POST['password'];

        // Create SQL query
        $sql = "SELECT password_hash FROM users WHERE username = '$entered_username'";

        // Execute query
        $result = mysqli_query($conn, $sql);

        // Check if user exists
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row['password_hash'];

            // Verify password
            if (password_verify($entered_password, $hashed_password)) {
                echo "Login successful!";
                $response['success'] = true;
                $response['type'] = 'signin';
                $reponse['message'] = 'Your account has been successfully created.';
                $response['title'] = 'Welcome!';
            } else {
                echo "Username or password might be wrong.";
                $response['success'] = false;
                $response['type'] ='error';
                $response['message'] = 'Invalid username or password.';
                $response['title'] = 'Error!';
            }
        } else {
            echo "User not registered.";
            $response['success'] = false;
            $response['type'] ='error';
            $response['message'] = 'User not registered.';
            $response['title'] = 'Error!';
        }

        mysqli_close($conn);

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

    }