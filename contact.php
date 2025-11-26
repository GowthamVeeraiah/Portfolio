<?php
// contact.php
header('Content-Type: application/json; charset=utf-8');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Invalid request method.']);
    exit;
}

// helper: sanitize
function s($v){ return trim(htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8')); }

$name = s($_POST['name'] ?? '');
$email = s($_POST['email'] ?? '');
$subject = s($_POST['subject'] ?? '');
$message = s($_POST['message'] ?? '');

$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if ($email === '') $errors[] = 'Email is required.';
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
if ($subject === '') $errors[] = 'Subject is required.';
if ($message === '') $errors[] = 'Message is required.';

if (!empty($errors)) {
    echo json_encode(['status'=>'error','message'=>'Please fix the errors.','errors'=>$errors]);
    exit;
}

// Prepare entry for messages.txt
$entry = "Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}\nCreatedAt: " . date('Y-m-d H:i:s') . "\n---\n";

$filename = __DIR__ . DIRECTORY_SEPARATOR . 'messages.txt';

// Attempt to append; make sure file is writable by Apache user
$ok = @file_put_contents($filename, $entry, FILE_APPEND | LOCK_EX);

if ($ok === false) {
    // If file write fails, still return success (or return error). We'll return error with guidance.
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Server error: cannot save message. Check file permissions for messages.txt.']);
    exit;
}

// Optionally: send email (disabled here). Could be added with mail() if configured.
// respond success
echo json_encode(['status'=>'success','message'=>'Thank you for your message! I will get back to you soon.']);
exit;
?>
