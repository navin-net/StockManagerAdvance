<?php

$data = json_decode(file_get_contents("php://input"), true);

$name    = $data['name'] ?? '';
$email   = $data['email'] ?? '';
$subject = $data['subject'] ?? '';
$message = $data['message'] ?? '';

$content = "Contact Submission\n\n";
$content .= "Name: $name\n";
$content .= "Email: $email\n";
$content .= "Subject: $subject\n\n";
$content .= "Message:\n$message\n";

if (!is_dir("submissions")) {
    mkdir("submissions", 0777, true);
}

$filename = "submissions/" . time() . ".txt";

file_put_contents($filename, $content);

echo "File saved successfully!";
