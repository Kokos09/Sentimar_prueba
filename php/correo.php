<?php
$para      = 'sales@sentimar.com';
$titulo    = 'Paneles Solares';
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$mensaje   = 'Hola Nombre: ' .$name."\nEmail: ".$email."\nTelefono: ".$phone;
$cabeceras = 'From: sales@sentimar.com' . "\r\n" .
    'Reply-To: sales@sentimar.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($para, $titulo, $mensaje, $cabeceras);
header("Location:../gracias.html")
?>