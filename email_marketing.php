<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Database credentials
$host = 'localhost'; // Adjust with your DB host if needed
$dbname = 'mydrive'; // Your database name
$username = 'root'; // Your MySQL username
$password = 'Rajpatel@2003'; // Your MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch email addresses
$sql = "SELECT email FROM email_marketing";
$result = $conn->query($sql);

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'manavtricks@gmail.com';
$mail->Password = 'hzkcjntkjjcyfudd'; // Your app password
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$counter = 0; // Counter for emails sent
$maxEmails = 400; // Set max limit to 400 emails

if ($result->num_rows > 0) {
    // Loop through all the emails
    while ($row = $result->fetch_assoc()) {
        $email = $row['email'];

        // Prepare the email
        $mail->setFrom('manavtricks@gmail.com', 'Manavtricks Cloud Storage');
        $mail->addAddress($email);
        $mail->isHTML(true); 
        $mail->Subject = "Lifetime 25 GB Storage Free";
        $mail->Body = "
        Dear $email,<br><br>

        I hope this email finds you well.<br><br>

        We are excited to introduce our cutting-edge cloud storage service, designed to provide you with a secure, scalable, and affordable solution for all your data storage needs. Whether you're looking to store personal files or manage large-scale business data, our platform offers a seamless experience with advanced features to ensure your data is always safe and easily accessible.<br><br>

        With your account on manavtricks.in, you have access to secure cloud storage that lets you:<br>

        ✅ Store files with end-to-end encryption - your data is always safe with us.<br>
        ✅ Access your files anytime, anywhere, on any device.<br>
        ✅ Share files easily with colleagues or friends with just a few clicks.<br><br>

        Haven't tried our cloud storage yet? Now's the perfect time! We're offering you a bonus 25 GB of storage free for you to get started.<br><br>

        🔐 Login now to explore all your cloud storage options: <br>
        https://manavtricks.in/cloudstorage/website/login <br><br>

        Need help or have questions? Our support team is here to assist you. Just reply to this email.<br><br>

        Thank you for choosing Manavtricks Cloud Storage. We're excited to help you manage your files securely and efficiently.<br><br>

        Best regards,<br>
        The Manavtricks Cloud Storage Team<br>
        manavtricks.in<br><br>

        P.S. Don't miss out on your extra 25 GB of free storage! Login now and start uploading your files today.<br>
        ";

        // Send the email
        try {
            $mail->send();
            echo "Email sent to: " . $email . "<br><br>";

            // Delete email from the database after successful send
            $deleteSql = "DELETE FROM email_marketing WHERE email = '$email'";
            $conn->query($deleteSql);

            // Increment the counter
            $counter++;

            // Check if counter reached the limit of 400
            if ($counter === $maxEmails) {
                echo "Reached the limit of $maxEmails emails. Stopping script.<br>";
                break;
            }

        } catch (Exception $e) {
            echo "Failed to send email to: " . $email . ". Error: " . $mail->ErrorInfo . "<br>";
        }

        // Clear the recipient list for the next iteration
        $mail->clearAddresses();
    }
} else {
    echo "No email addresses found.";
}

// Close the connection
$conn->close();

?>
