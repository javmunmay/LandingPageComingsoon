-- Crear la base de datos
-- CREATE DATABASE IF NOT EXISTS prelanzamiento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos recién creada
-- USE tu_base_de_datos;

-- Crear la tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identificador único para cada usuario
    nombre VARCHAR(255) NOT NULL,               -- Nombre del usuario (hasta 255 caracteres)
    correo VARCHAR(255) NOT NULL UNIQUE,        -- Correo electrónico del usuario (debe ser único)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha y hora de registro automática
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;