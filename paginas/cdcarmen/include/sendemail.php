<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$toemails = array();

$toemails[] = array(
	'email' => 'sales@sentimar.com', // Your Email Address
	'name' => 'Sentimar' // Your Name
);

// Form Processing Messages
$message_success = 'Hemos recibido su Mensaje y le responderemos lo antes posible.';

// Add this only if you use reCaptcha with your Contact Forms
$recaptcha_secret = '6LfshJwUAAAAAM7J1lus_xMJpAMyyxeSA3s5ecgX'; // Your reCaptcha Secret

$mail = new PHPMailer();

// If you intend you use SMTP, add your SMTP Code after this Line


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_POST['template-contactform-email'] != '' ) {

		$name = isset( $_POST['template-contactform-name'] ) ? $_POST['template-contactform-name'] : '';
		$email = isset( $_POST['template-contactform-email'] ) ? $_POST['template-contactform-email'] : '';
		$phone = isset( $_POST['template-contactform-phone'] ) ? $_POST['template-contactform-phone'] : '';
		$subject = isset( $_POST['template-contactform-subject'] ) ? $_POST['template-contactform-subject'] : '';
		$message = isset( $_POST['template-contactform-message'] ) ? $_POST['template-contactform-message'] : '';

		$subject = $subject ? $subject : 'CD. DEL CARMEN - Nuevo Mensaje desde el formulario de Contacto';

		$botcheck = $_POST['template-contactform-botcheck'];

		if( $botcheck == '' ) {

			$mail->SetFrom( $email , $name );
			$mail->AddReplyTo( $email , $name );
			foreach( $toemails as $toemail ) {
				$mail->AddAddress( $toemail['email'] , $toemail['name'] );
			}
			$mail->Subject = $subject;

			$name = isset($name) ? "Nombre: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Teléfono: $phone<br><br>" : '';
			$message = isset($message) ? "Mensaje: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Este formulario fue enviado desde: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $service $message $referrer";

			// // Runs only when File Field is present in the Contact Form
			// if ( isset( $_FILES['template-contactform-file'] ) && $_FILES['template-contactform-file']['error'] == UPLOAD_ERR_OK ) {
			// 	$mail->IsHTML(true);
			// 	$mail->AddAttachment( $_FILES['template-contactform-file']['tmp_name'], $_FILES['template-contactform-file']['name'] );
			// }

			// Runs only when reCaptcha is present in the Contact Form
			if( isset( $_POST['g-recaptcha-response'] ) ) {

				$recaptcha_data = array(
					'secret' => $recaptcha_secret,
					'response' => $_POST['g-recaptcha-response']
				);

				$recap_verify = curl_init();
				curl_setopt( $recap_verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify" );
				curl_setopt( $recap_verify, CURLOPT_POST, true );
				curl_setopt( $recap_verify, CURLOPT_POSTFIELDS, http_build_query( $recaptcha_data ) );
				curl_setopt( $recap_verify, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $recap_verify, CURLOPT_RETURNTRANSFER, true );
				$recap_response = curl_exec( $recap_verify );

				$g_response = json_decode( $recap_response );

				if ( $g_response->success !== true ) {
					echo '{ "alert": "error", "message": "Captcha no validado! Por favor, inténtalo de nuevo." }';
					die;
				}
			}

			// Uncomment the following Lines of Code if you want to Force reCaptcha Validation

			// if( !isset( $_POST['g-recaptcha-response'] ) ) {
			// 	echo '{ "alert": "error", "message": "Captcha not Submitted! Please Try Again." }';
			// 	die;
			// }

			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

			if( $sendEmail == true ):
				echo '{ "alert": "success", "message": "' . $message_success . '" }';
			else:
				echo '{ "alert": "error", "message": "El correo electrónico <strong> no pudo </strong> enviarse debido a un error inesperado. Por favor, inténtelo de nuevo más tarde.<br /><br /><strong>Error:</strong><br />' . $mail->ErrorInfo . '" }';
			endif;
		} else {
			echo '{ "alert": "error", "message": "Bot <strong>Detected</strong>.! Clean yourself Botster.!" }';
		}
	} else {
		echo '{ "alert": "error", "message": "Por favor <strong>llene</strong> todos los campos e intente nuevamente." }';
	}
} else {
	echo '{ "alert": "error", "message": "Se produjo un <strong> error inesperado </strong>. Por favor, inténtelo de nuevo más tarde." }';
}

?>