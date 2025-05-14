<?php
/*
  PHP contact form script
  Version: 1.1
  Copyrights BootstrapMade.com
*/

/***************** Configuration *****************/

  // Replace with your real receiving email address
  // $contact_email_to = "admin@excelpssllc.com";
    $contact_email_to = "miyukipg@hotmail.com";



  // Title prefixes
  $subject_title = "Contact Form Message:";
  $name_title = "Name:";
  $email_title = "Email:";
  $message_title = "Message:";
  $attachment_title = "Attachment:";

  // Error messages
  $contact_error_name = "Name is too short or empty!";
  $contact_error_email = "Please enter a valid email!";
  $contact_error_subject = "Subject is too short or empty!";
  $contact_error_message = "Too short message! Please enter something.";
  $contact_error_attachment = "File upload failed or invalid file type!";
  $contact_error_attachment_size = "File is too large! Maximum size is 5MB.";

  // File upload settings
  $max_file_size = 5 * 1024 * 1024; // 5MB
  $allowed_file_types = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif');


/********** Do not edit from the below line ***********/

  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('Sorry Request must be Ajax POST');
  }

  if(isset($_POST)) {

    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    if(!$contact_email_to || $contact_email_to == 'contact@example.com') {
      die('The contact form receiving email address is not configured!');
    }

    if(strlen($name)<3){
      die($contact_error_name);
    }

    if(!$email){
      die($contact_error_email);
    }

    if(strlen($subject)<3){
      die($contact_error_subject);
    }

    if(strlen($message)<3){
      die($contact_error_message);
    }

    if(!isset($contact_email_from)) {
      $contact_email_from = "contactform@" . @preg_replace('/^www\./','', $_SERVER['SERVER_NAME']);
    }

  // Process file attachment if exists
    $attachment_path = null;
    $attachment_name = null;
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
      $file_tmp_path = $_FILES['attachment']['tmp_name'];
      $file_name = $_FILES['attachment']['name'];
      $file_size = $_FILES['attachment']['size'];
      $file_type = $_FILES['attachment']['type'];
      
      // Get file extension
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      
      // Validate file
      if($file_size > $max_file_size) {
        die($contact_error_attachment_size);
      }
      
      if(!in_array($file_ext, $allowed_file_types)) {
        die($contact_error_attachment);
      }
      
      // Generate unique name for the file
      $attachment_name = uniqid() . '.' . $file_ext;
      $upload_dir = 'uploads/';
      
      // Create directory if it doesn't exist
      if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
      }
      
      $attachment_path = $upload_dir . $attachment_name;
      
      if(!move_uploaded_file($file_tmp_path, $attachment_path)) {
        die($contact_error_attachment);
      }
    }


    $headers = 'From: ' . $name . ' <' . $contact_email_from . '>' . PHP_EOL;
    $headers .= 'Reply-To: ' . $email . PHP_EOL;
    $headers .= 'MIME-Version: 1.0' . PHP_EOL;

    // Boundary for multipart message
    $boundary = md5(time());

    $headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL;
    $headers .= 'X-Mailer: PHP/' . phpversion();

    // Message body
    $message_content = "--" . $boundary . PHP_EOL;
    $message_content .= "Content-Type: text/html; charset=UTF-8" . PHP_EOL;
    $message_content .= "Content-Transfer-Encoding: 7bit" . PHP_EOL . PHP_EOL;
    $message_content = '<strong>' . $name_title . '</strong> ' . $name . '<br>';
    $message_content .= '<strong>' . $email_title . '</strong> ' . $email . '<br>';
    $message_content .= '<strong>' . $message_title . '</strong> ' . nl2br($message) . PHP_EOL . PHP_EOL;

    // Add attachment if exists
    if($attachment_path) {
      $file_content = file_get_contents($attachment_path);
      $file_content = chunk_split(base64_encode($file_content));
      
      $message_content .= "--" . $boundary . PHP_EOL;
      $message_content .= "Content-Type: application/octet-stream; name=\"" . $attachment_name . "\"" . PHP_EOL;
      $message_content .= "Content-Transfer-Encoding: base64" . PHP_EOL;
      $message_content .= "Content-Disposition: attachment; filename=\"" . $attachment_name . "\"" . PHP_EOL . PHP_EOL;
      $message_content .= $file_content . PHP_EOL . PHP_EOL;
    }

    $message_content .= "--" . $boundary . "--";

    $sendemail = mail($contact_email_to, $subject_title . ' ' . $subject, $message_content, $headers);

    // Delete the uploaded file after sending the email
    if($attachment_path && file_exists($attachment_path)) {
      unlink($attachment_path);
    }

    if( $sendemail ) {
      echo 'OK';
    } else {
      echo 'Could not send mail! Please check your PHP mail configuration.';
    }
  }
?>
