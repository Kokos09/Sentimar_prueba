<?php
$para      = 'ventas@sentimar.com.mx';
$titulo    = 'Paneles Solares';
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$comentarios=$_POST['comentarios'];
$mensaje   = 'Nombre: ' .$name."\nTelefono: ".$phone."\nEmail: ".$email."\nComentarios: ".$comentarios;
$cabeceras = 'From: ventas@sentimar.com.mx' . "\r\n" .
    'Reply-To: ventas@sentimar.com.mx' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($para, $titulo, $mensaje, $cabeceras);
header("Location:../gracias.html")
?>