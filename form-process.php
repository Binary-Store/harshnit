<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

// require 'vendor/autoload.php';

include('smtp/PHPMailerAutoload.php');

$data = json_decode(file_get_contents("php://input"), true);

// Validate and sanitize data (add more validation as needed)
$fname = isset($data['fname']) ? htmlspecialchars(trim($data['fname'])) : '';
$lname = isset($data['lname']) ? htmlspecialchars(trim($data['lname'])) : '';
$number = isset($data['phone']) ? htmlspecialchars(trim($data['phone'])) : '';
$email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : '';
$message = isset($data['message']) ? htmlspecialchars(trim($data['message'])) : '';
$name = $fname . " " . $lname;

$html = "Name: <b>" . $name . "</b><br>" . "Mobile NO: " . $number . "<br>" . "Email: " . $email . "<br> Message: " . $message;
echo smtp_mailer('decentindustries2000@gmail.com', $name, $html);
//echo smtp_mailer('xyzabc6639@gmail.com', $name, $html);

function smtp_mailer($to, $subject, $msg)
{
  $mail = new PHPMailer();
  // $mail->SMTPDebug  = 3;
  $mail->IsSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Host = "smtp.gmail.com";
  $mail->Port = 587;
  $mail->IsHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Username = "harshil9915vasoya@gmail.com";
  $mail->Password = "fiummkhswgxudiso";
  $mail->SetFrom("harshil9915vasoya@gmail.com");
  $mail->Subject = $subject;
  $mail->Body = $msg;
  $mail->AddAddress($to);
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => false
    )
  );

  if (!$mail->Send()) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Error sending email']);

  } else {
    http_response_code(200); // OK
    echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
  }
}
?>