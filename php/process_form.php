<?php
session_start(); // Iniciar sesión para almacenar el mensaje de éxito

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


/* ******************************************* */
// 2. Incluir PHPMailer
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/* ******************************************* */



if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Datos de conexión a la base de datos
$host = "PMYSQL181.dns-servicio.com:3306";
$dbname = "10718674_prelanzamiento";
$username = "Javier";
$password = "u70q0Z2p@";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['name']) || !isset($_POST['email'])) {
            die("Error: No llegaron los datos.");
        }

        $name = trim($_POST['name']);
        $correo = trim($_POST['email']);

        if (empty($name) || empty($correo)) {
            die("Error: Todos los campos son obligatorios.");
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            die("Error: Correo electrónico no válido.");
        }

        // Verificar si el email ya está registrado
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            die("Error: Este correo ya está registrado.");
        }

        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo) VALUES (?, ?)");
        $stmt->execute([$name, $correo]);

        // Guardar mensaje de éxito en sesión
        $_SESSION['mensaje_exito'] = "Todo correcto, gracias por registrarte.";

        // Redirigir de vuelta a index.html
        header("Location: https://www.estudianteprogramador.com/?success=1");
        exit;
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}



/* Proceso de enviar Correo */


$mail = new PHPMailer(true);


// Configuración del servidor SMTP
$mail->isSMTP();
$mail->Host = 'smtp.ionos.es'; // Servidor SMTP de IONOS
$mail->SMTPAuth = true; // Habilitar autenticación SMTP
$mail->Username = 'noreply@estudianteprogramador.com'; // Tu correo
$mail->Password = 'diGqyn-dagges-tyfgo5'; // Tu contraseña
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cifrado TLS
$mail->Port = 587; // Puerto SMTP para TLS

// Deshabilitar la verificación del certificado SSL
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];

// Habilitar el modo de depuración (opcional, para ver errores detallados)
//$mail->SMTPDebug = 2; // Nivel de depuración (2 = mensajes detallados)

// Configuración del correo
$mail->CharSet = 'UTF-8'; // Configurar la codificación de caracteres
$mail->setFrom('noreply@estudianteprogramador.com', 'Estudiante Programador'); // Remitente
$mail->addAddress($correo); // Destinatario
$mail->isHTML(true); // Formato HTML

// Agregar imagen incrustada
$mail->addEmbeddedImage('../imagenes/LogoEstudianteAzul.png', 'logo', 'LogoEstudianteProgramador.png');

// Cuerpo del correo en HTML
$mail->Subject = 'Registro completado Estudiante Programador';
$mail->Body = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Desarrolla tus habilidades al siguiente nivel</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                padding: 20px 0;
            }
            .header img {
                max-width: 100px;
                height: auto;
            }
            .content {
                padding: 20px;
            }
            .content h1 {
                font-size: 24px;
                color: #090643;
            }
            .content p {
                font-size: 16px;
                line-height: 1.6;
            }
            .content a {
                display: inline-block;
                margin: 20px 0;
                padding: 10px 20px;
                background-color: #090643;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
            }
            .footer {
                text-align: center;
                padding: 20px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='cid:logo' alt='Logo Estudiante Programador'>
            </div>
            <div class='content'>
                <h1>Registro completado Estudiante Programador</h1>
                <p>¡Hola $name!</p>
                <br>
                <p>Gracias por registrarse al pre lanzamiento de <strong>Estudiante Programador</strong>, donde aprender a programar es sencillo, práctico y, sobre todo, ¡sin tecnicismos innecesarios!</p>
                <br>
                <p>Nuestra misión es clara: enseñarte de manera directa, sin profesores que lean diapositivas aburridas ni conceptos complicados que no vas a usar. En nuestra plataforma encontrarás:</p>
                
                <p>✅ <strong>Cursos prácticos</strong> en Desarrollo Web, Ciberseguridad e Inteligencia Artificial.</p>
                <p>✅ <strong>Ejercicios prácticos</strong> para que aprendas haciendo.</p>
                <p>✅ <strong>Recursos útiles</strong> que podrás aplicar en el mundo real.</p>
                <p>✅ <strong>Certificados</strong> que validan tus conocimientos y esfuerzo.</p>

                <br>
                <p>Estamos trabajando para lanzarla al mundo lo antes posible.</p>
                <br>
                <p>¡Prepárate para sumergirte en el mundo de la programación, alcanzar tus metas y obtener certificados que respalden tu aprendizaje!</p>
                <br>
                <p>Muy Pronto</p>
                <p>El equipo de <strong>Estudiante Programador</strong></p>
            </div>
            <div class='footer'>
                <p>&copy; 2025 Estudiante Programador. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ";

// Enviar el correo
$mail->send();






/* *********************** */
?>
