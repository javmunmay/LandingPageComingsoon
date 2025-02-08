<?php
// Datos de conexión a la base de datos
$host = "PMYSQL181.dns-servicio.com:3306";  // Cambia esto si tu DB está en otro servidor
$dbname = "10718674_prelanzamiento";
$username = "Javier";
$password = "u70q0Z2p@";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar si los datos fueron enviados
        if (!isset($_POST['name']) || !isset($_POST['email'])) {
            die("Error: No llegaron los datos.");
        }

        // Obtener y limpiar los valores
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);


        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        exit;



        // Validar que no estén vacíos
        if (empty($name) || empty($email)) {
            die("Error: Todos los campos son obligatorios.");
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Error: Correo electrónico no válido.");
        }

        // Verificar si el email ya está registrado
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            die("Error: Este correo ya está registrado.");
        }

        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);

        echo "Registro exitoso. ¡Gracias por registrarte!";
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
