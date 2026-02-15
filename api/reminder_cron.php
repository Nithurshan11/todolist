<?php
require '../vendor/autoload.php';
require_once "../config/database.php";

use PHPMailer\PHPMailer\PHPMailer;

$db = (new Database())->connect();

$result = $db->query("
SELECT tasks.*, users.email, users.username
FROM tasks
JOIN users ON tasks.user_id = users.id
WHERE due_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
AND reminder_sent = 0
");

while($row = $result->fetch_assoc()) {

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "yourgmail@gmail.com";
    $mail->Password = "your_app_password";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("yourgmail@gmail.com","SmartTask");
    $mail->addAddress($row['email']);
    $mail->Subject = "Reminder: Task Due Tomorrow";
    $mail->Body = "Hello ".$row['username'].
                  ", your task '".$row['task_name'].
                  "' is due tomorrow.";

    $mail->send();

    $db->query("UPDATE tasks SET reminder_sent=1 WHERE id=".$row['id']);
}
?>