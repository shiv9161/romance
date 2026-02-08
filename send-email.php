<?php
/**
 * SMTP Email Handler
 * Update these credentials with your real SMTP settings
 */
$smtp_config = [
    'host'       => getenv('SMTP_HOST') ?: 'smtp.example.com',
    'port'       => (int)(getenv('SMTP_PORT') ?: 587),
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
    'username'   => getenv('SMTP_USER') ?: 'your-email@example.com',
    'password'   => getenv('SMTP_PASS') ?: 'your-password',
    'from_email' => getenv('SMTP_FROM') ?: 'noreply@example.com',
    'from_name'  => getenv('SMTP_FROM_NAME') ?: 'Valentine Surprise',
    'to_email'   => getenv('SMTP_TO') ?: 'your-email@example.com',
];

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    require __DIR__ . '/vendor/autoload.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $smtp_config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtp_config['username'];
    $mail->Password   = $smtp_config['password'];
    $mail->SMTPSecure = $smtp_config['encryption'];
    $mail->Port       = $smtp_config['port'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
    $mail->addAddress($smtp_config['to_email']);
    $mail->Subject = '💖 Someone said YES to being your Valentine!';
    $mail->Body    = "Congratulations! 🎉\n\nSomeone clicked YES on your Valentine's page!\n\n"
        . "This is the beginning of something beautiful. "
        . "Get ready for chocolates, flowers, and lots of love! 🍫🌹❤️\n\n"
        . "Sent at " . date('Y-m-d H:i:s');
    $mail->AltBody = strip_tags($mail->Body);

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Email sent! Check your inbox.']);
} catch (Exception $e) {
    $errMsg = isset($mail) ? $mail->ErrorInfo : $e->getMessage();
    echo json_encode(['success' => false, 'message' => 'Failed to send: ' . $errMsg]);
}
