<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["Name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
        $phone = trim($_POST["Phone"]);
        $message = trim($_POST["Message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "patternblue1441@gmail.com";

        // Set the email subject.
        $subject = "New contact from $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Phone: $phone\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        // $email_headers = "From: $name <$email>";
		// $email_headers = "From: $name <$email>" . "\r\n" ;
		$email_headers = "From: Simon patternblue1441@gmail.com" . "\r\n" ;
		// $email_headers .="Reply-To: ". $recipient . "\r\n" ;
		$email_headers .="Reply-To: ".$name." <".$email.">\r\n" ;
		$email_headers .="X-Mailer: PHP/" . phpversion();
		$email_headers .= "MIME-Version: 1.0\r\n";
		$email_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";  

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
		// if (mail($recipient, $subject, $email_content)) {      	
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>