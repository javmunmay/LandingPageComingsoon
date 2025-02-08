<?php
// process_form.php

// Habilitar CORS si es necesario (ajustar según tu entorno)
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
}
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, X-Requested-With, Content-Type, Accept, Authorization");

// Verificar si se ha enviado el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// Obtener datos del formulario
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validaciones básicas
if (empty($name) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $name)) {
    echo json_encode(['success' => false, 'message' => 'El nombre solo debe contener letras y espacios.']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
    exit;
}

try {
    // Configuración de la conexión a la base de datos
    $dsn = 'mysql:host=PMYSQL181.dns-servicio.com;port=3306;dbname=10718674_prelanzamiento;charset=utf8mb4';
    $username = 'Javier';
    $password = 'u70q0Z2p@';

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Preparar la consulta SQL
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo) VALUES (:name, :email)");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar los datos.']);
        exit;
    }
} catch (PDOException $e) {
    // Registrar el error en el servidor para depuración
    error_log("Error de base de datos: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos. Por favor, inténtalo de nuevo.']);
    exit;
}