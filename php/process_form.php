<?php
// Archivo: process_form.php

// Lista de dominios de correos temporales a bloquear
$disposable_domains = ['mailinator.com', 'tempmail.com', '10minutemail.com'];

// Verificar si se ha enviado el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $honeypot = isset($_POST['honeypot']) ? $_POST['honeypot'] : ''; // Campo oculto para evitar bots

    // Validaciones básicas
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $name)) {
        echo json_encode(['success' => false, 'message' => 'El nombre solo debe contener letras y espacios.']);
        exit;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
        exit;
    }

    // Verificar si el correo es de un dominio temporal
    $email_domain = substr(strrchr($email, "@"), 1);
    if (in_array($email_domain, $disposable_domains)) {
        echo json_encode(['success' => false, 'message' => 'No se permiten correos temporales.']);
        exit;
    }

    // Verificar si el honeypot está vacío (si no, es un bot)
    if (!empty($honeypot)) {
        echo json_encode(['success' => false, 'message' => 'Acción no permitida.']);
        exit;
    }

    try {
        // Configuración de la conexión a la base de datos
        $dsn = 'mysql:host=PMYSQL181.dns-servicio.com:3306;dbname=10718674_prelanzamiento;charset=utf8mb4';
        $username = 'Javier';
        $password = 'u70q0Z2p@';

        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Preparar la consulta SQL
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo) VALUES (:name, :email)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
        header("Location: https://patreon.com/EstudianteProgramador");
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}
?>
