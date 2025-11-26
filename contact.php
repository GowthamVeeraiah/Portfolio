<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required.";
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // In a real implementation, you would:
        // 1. Save to database
        // 2. Send email notification
        // 3. Return success message
        
        // For this example, we'll just return a success message
        $response = [
            'status' => 'success',
            'message' => 'Thank you for your message! I will get back to you soon.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Please fix the following errors:',
            'errors' => $errors
        ];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // If not a POST request, redirect to home page
    header('Location: index.html');
    exit;
}
?>