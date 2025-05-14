<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require '../vendor/autoload.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration
$max_file_size = 5 * 1024 * 1024; // 5MB
$allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];

  $mail = new PHPMailer(true);

  try {
      // Server settings
      $mail->isSMTP();
      $mail->Host       = 'smtp.mailtrap.io';
      $mail->SMTPAuth   = true;
      $mail->Username   = '4cb0910cf033b5';
      $mail->Password   = '0d993e7ba37ae2';
      $mail->Port       = 2525;

      // Recipients
      $mail->setFrom('contact-form@excelpssllc.com', 'Contact Form'); //This is just a madeup email account, does not exist.
      $mail->addAddress('miyukipg@hotmail.com');
      $mail->addReplyTo($_POST['email'], $_POST['name']); // For replies

      // Handle file attachment if uploaded
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['attachment'];

        // Verify file was actually uploaded
        if (!is_uploaded_file($_FILES['attachment']['tmp_name'])) {
            throw new Exception('Possible file upload attack!');
        }
        
        // Validate file size
        if ($file['size'] > $max_file_size) {
            throw new Exception('File is too large. Maximum size is 5MB.');
        }
        
        // Validate file type
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types)) {
            throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowed_types));
        }
        
        // Attach the file
        $mail->addAttachment(
            $file['tmp_name'],
            $file['name'],
            'base64',
            $file['type']
        );
    }
      
      // Content
      $mail->isHTML(true);
      $mail->Subject = 'New Contact Form Submission: ' . $_POST['subject'];
      $mail->Body    = "
      <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> {$_POST['name']}</p>
        <p><strong>Email:</strong> {$_POST['email']}</p>
        <p><strong>Message:</strong></p>
        <p>{$_POST['message']}</p>"
         . (!empty($_FILES['attachment']['name']) ? 
            "<p><strong>Attachment:</strong> {$_FILES['attachment']['name']}</p>" : '');
      
      $mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
?>
