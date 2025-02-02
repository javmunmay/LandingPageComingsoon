<?php
// Archivo: process_form.php

// Verificar si se ha enviado el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validaciones básicas
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
        exit;
    }

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico es obligatorio.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
        exit;
    }

    // Procesamiento adicional (guardar en una base de datos usando PDO)
    try {
        // Configuración de la conexión a la base de datos
        $dsn = 'mysql:host=PMYSQL181.dns-servicio.com:3306;dbname=10718674_prelanzamiento;charset=utf8mb4';
        $username = 'Javier'; // Reemplaza con tu usuario de MySQL
        $password = 'u70q0Z2p@'; // Reemplaza con tu contraseña de MySQL

        // Crear una instancia de PDO
        $pdo = new PDO($dsn, $username, $password);

        // Preparar la consulta SQL para insertar datos en la tabla `usuarios`
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo) VALUES (:name, :email)");

        // Asociar parámetros con valores
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la inserción fue exitosa, mostrar una página intermedia
            echo json_encode(['success' => true, 'message' => 'Registro exitoso.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar los datos.']);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}
?>