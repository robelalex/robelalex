<?php

// Establish connection
include("./db-con.php");

// Ensure that the only way to access this php is through the form
if($_SERVER["REQUEST_METHOD"] == "POST"){

    
    $serviceType = $_POST['serviceType']; 
    // response for the notification the user will recieve
    $response = ['success' => false, 'message' => '']; 

    switch ($serviceType) {
        case 'citizen':
            $fullName = $_POST['citizenName'];
            $email = $_POST['citizenEmail'];
            $categoryName = $_POST['citizenService']; 
    
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Citizen Services'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];

            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];

            $comments = $_POST['citizenComments'];

            $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                    VALUES ('$serviceId', '$categoryId', '$fullName', '$email', '$comments')";
            if (mysqli_query($conn, $sql)) {
                $response['success'] = true;
                $response['message'] = "Citizen service request submitted successfully!";
            } else {
                $response['message'] = "Error: submitting the citizen request!";
            }
            break;

        case 'complain':
            $fullName = $_POST['complaintName'];
            $email = $_POST['complaintEmail'];
            $categoryName = $_POST['complaintType']; 
    
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Complaint Management'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];
    
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];
    
            $details = $_POST['complaintDetails'];
    
            $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                    VALUES ('$serviceId', '$categoryId', '$fullName', '$email', '$details')";
            if (mysqli_query($conn, $sql)) {
                $response['success'] = true;
                $response['message'] = "Complaint filed successfully. We'll keep you updated.";
            } else {
                $response['message'] = " Error: submitting complaint. Please try again.";
            }
            break;
        case 'document':
            $fullName = $_POST['documentName']; 
            $email = $_POST['documentEmail'];   
            $categoryName = $_POST['documentType']; 
        
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Document Management'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id']; 
        
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id']; 
        
            // Handle file upload
            $targetDir = "../uploads/"; 
            $targetFile = $targetDir . basename($_FILES["documentFile"]["name"]); 
            $uploadOk = 1; 
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); 
        
            // Basic file validation
            // Check if the file already exists
            if (file_exists($targetFile)) {
                $response['message'] = "Sorry, file already exists.";
                $uploadOk = 0;
            }
        
            // Check file size (limit to 500KB)
            if ($_FILES["documentFile"]["size"] > 500000) {
                $response['message'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
        
            // Allow only specific file types
            $allowedTypes = ["jpg", "png", "jpeg", "gif"];
            if (!in_array($fileType, $allowedTypes)) {
                $response['message'] = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
                $uploadOk = 0;
            }
        
            // Upload the file if everything is OK
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["documentFile"]["tmp_name"], $targetFile)) {
                    // File uploaded successfully
                    $filePath = $targetFile; 
        
                    // Insert application data into the `applications` table
                    $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                            VALUES ('$serviceId', '$categoryId', '$fullName', '$email', 'Document Uploaded')"; 
        
                    if (mysqli_query($conn, $sql)) {
                        // Get the `application_id` of the newly inserted application
                        $applicationId = mysqli_insert_id($conn);
        
                        $documentSql = "INSERT INTO applicationdocuments (application_id, document_path) 
                                        VALUES ('$applicationId', '$filePath')";
        
                        if (mysqli_query($conn, $documentSql)) {
                            $response['success'] = true;
                            $response['message'] = "Document uploaded and request submitted successfully!";
                        } else {
                            $response['message'] = "Error: Could not save document details. ";
                        }
                    } else {
                        echo "Error: Could not submit the application request. ";
                    }
                } else {
                    echo  "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, your file was not uploaded.";
            }
            break;
        case 'certificate':
            $fullName = $_POST['certificateName'];
            $email = $_POST['certificateEmail'];
            $categoryName = $_POST['certificateType']; 
    
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Certificate Applications'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];
    
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];
    
            $dateRequired = $_POST['certificateDate'];
    
            $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                    VALUES ('$serviceId', '$categoryId', '$fullName', '$email', '$dateRequired')";

            if (mysqli_query($conn, $sql)) {
                $response['success'] = true;
                $response['message'] = "Certificate application submitted successfully!";
            } else {
                $response['message'] = "Error: submitting the certificate request!";
            }
            break;
        case 'permits':
            $fullName = $_POST['permitsName'];
            $email = $_POST['permitsEmail'];
            $categoryName = $_POST['permitsType']; 
        
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Permits and Licenses'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];
        
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];
        
            $applicationDate = $_POST['permitsDate'];
        
            $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                    VALUES ('$serviceId', '$categoryId', '$fullName', '$email', '$applicationDate')";
            if (mysqli_query($conn, $sql)) {
                echo "Permit/License application submitted successfully!";
            } else {
                echo "Error: submitting the permits request!" . mysqli_error($conn);
            }

            if (mysqli_query($conn, $sql)) {
                $response['success'] = true;
                $response['message'] = "Permit/License application submitted successfully!";
            } else {
                $response['message'] = "Error: submitting the permits request!";
            }
            break;
        case 'tax':
            $fullName = $_POST['taxName'];
            $email = $_POST['taxEmail'];
            $categoryName = $_POST['taxType']; 
    
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Tax and Bill Payments'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];
    
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];
    
            $amount = $_POST['taxAmount'];
    
            $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                    VALUES ('$serviceId', '$categoryId', '$fullName', '$email', '$amount')";

            if (mysqli_query($conn, $sql)) {
                $response['success'] = true;
                $response['message'] = "Tax payment request submitted successfully!";
            } else {
                $response['message'] = "Error: submitting the tax request!";
            }
            break;
        
        case 'scholarship':
            $fullName = $_POST['scholarshipName'];
            $email = $_POST['scholarshipEmail'];
            $categoryName = $_POST['scholarshipLevel']; 
        
            // Get service_id from Services table
            $serviceSql = "SELECT service_id FROM services WHERE service_name = 'Scholarship Applications'";
            $serviceResult = mysqli_query($conn, $serviceSql);
            $serviceRow = mysqli_fetch_assoc($serviceResult);
            $serviceId = $serviceRow['service_id'];
        
            // Get category_id from ServiceCategories table
            $categorySql = "SELECT category_id FROM servicecategories WHERE service_id = '$serviceId' AND category_name = '$categoryName'";
            $categoryResult = mysqli_query($conn, $categorySql);
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['category_id'];
        
            $grade = $_POST['scholarshipGrade'];
        
            // Handle file upload
            $targetDir = "../uploads/"; 
            $targetFile = $targetDir . basename($_FILES["scholarshipFile"]["name"]);
            $uploadOk = 1; 
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); 

            // Basic file validation
            // Check if the file already exists
            if (file_exists($targetFile)) {
                $response['message'] = "Sorry, file already exists.";
                $uploadOk = 0;
            }
        
            // Check file size (limit to 5000KB)
            if ($_FILES["scholarshipFile"]["size"] > 5000000) {
                $response['message'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }
        
            // Allow only specific file types
            $allowedTypes = ["pdf", "docx", "doc"];
            if (!in_array($fileType, $allowedTypes)) {
                $response['message'] = "Sorry, only PDF, DOCX, and DOC files are allowed.";
                $uploadOk = 0;
            }
        
            // Upload the file if everything is OK
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["scholarshipFile"]["tmp_name"], $targetFile)) {
                    // File uploaded successfully
                    $filePath = $targetFile; 
        
                    // Insert application data into the `applications` table
                    $sql = "INSERT INTO applications (service_id, category_id, full_name, email, comments) 
                            VALUES ('$serviceId', '$categoryId', '$fullName', '$email', 'Grade: $grade : Scholarship Information')"; 
        
                    if (mysqli_query($conn, $sql)) {
                        // Get the `application_id` of the newly inserted application
                        $applicationId = mysqli_insert_id($conn);
        
                        $documentSql = "INSERT INTO applicationdocuments (application_id, document_path) 
                                        VALUES ('$applicationId', '$filePath')";
        
                        if (mysqli_query($conn, $documentSql)) {
                            $response['success'] = true;
                            $response['message'] = "Scholarship application submitted successfully!";
                        } else {
                            $response['message'] = "Error: Could not save document details.";
                        }
                    } else {
                        echo "Error: Could not submit the scholarship request.";
                    }
                } else {
                    $response['message'] = "Sorry, there was an error uploading your file.";
                }
            } else {
                echo  "Sorry, your file was not uploaded.";
            }
            break;
            
        default:
        $response['message'] = "Invalid service type!";
        }

        mysqli_close($conn);

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();

}