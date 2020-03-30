<?php

$destino='sales@sentimar.com';
$name=$_POST['name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$asunto="Paneles Solares";
$mensaje="Nombre: ".$name."\nEmail: ".$email."\nTelefono: ".$phone;
mail($destino,$asunto,$mensaje);
header("Location:../energia-solar.html")
?>