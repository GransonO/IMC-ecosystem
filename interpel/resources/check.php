<?php
include 'core/init.php';

$transport = Swift_SmtpTransport::newInstance('root.server-ke21.com ', 465)
->setUsername('services@indulgencemarketing.co.ke')
->setPassword('G#*Hii4M#P');

$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance('This is a Swift mailer test')
->setFrom(array('info@indulgencemarketing.co.ke' => 'Granson is Testing'))
->setTo(array('corumdeveloper@gmail.com' => 'Corum','granson@indulgencemarketing.co.ke' => 'Granson work Mail'))
->setBody('This is a valid test from Swift Mailer Friday at 7:18pm.');

$result = $mailer->send($message);

if ($result) {
      echo "Email sent successfully";
    }
    else
    {
      echo "Email failed to send";
    }
?>