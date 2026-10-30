<?php
include 'conexion.php';

$mensaje = "";
$tipo_alerta = ""; // 'exito' o 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Verificar si el correo existe en la base de datos
    $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Generar una contraseña temporal aleatoria segura
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$';
        $temp_password = '';
        for ($i = 0; $i < 8; $i++) {
            $temp_password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        // Encriptar la nueva contraseña temporal
        $password_hash = password_hash($temp_password, PASSWORD_BCRYPT);

        // Actualizar en la base de datos
        $stmt_update = $conexion->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
        $stmt_update->bind_param("ss", $password_hash, $email);

        if ($stmt_update->execute()) {
            $mensaje = "¡Contraseña restablecida con éxito! Tu nueva contraseña temporal es: <strong>" . htmlspecialchars($temp_password) . "</strong><br><br>Cópiala, inicia sesión y cámbiala por seguridad.";
            $tipo_alerta = "exito";
        } else {
            $mensaje = "Ocurrió un error al intentar actualizar la contraseña. Inténtalo de nuevo.";
            $tipo_alerta = "error";
        }
        $stmt_update->close();
    } else {
        $mensaje = "El correo electrónico ingresado no se encuentra registrado en el sistema.";
        $tipo_alerta = "error";
    }
    $stmt->close();
} 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Audiolibro English</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/fondo.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }
        h2 {
            color: #1a365d;
            text-align: center;
            margin-bottom: 20px;
        }
        .mensaje {
            padding: 12px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .mensaje.error {
            background-color: #fed7d7;
            color: #e53e3e;
            font-weight: bold;
        }
        .mensaje.exito {
            background-color: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #2d3748;
            font-size: 14px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button[type="submit"] {
            background-color: #319795;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            margin-top: 20px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        button[type="submit"]:hover {
            background-color: #2c7a7b;
        }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <header class="header-marca" style="width: 100%; background: transparent !important; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; position: relative; z-index: 10; padding: 15px 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="index.php" style="display: flex; align-items: center; gap: 15px; text-decoration: none;">
                <img src="img/logo.jpeg" alt="Nerco Logo" style="height: 75px; width: auto; border-radius: 8px; object-fit: contain;">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-size: 20px; font-weight: bold; color: #ffffff; text-shadow: 0 2px 6px rgba(0,0,0,0.9); line-height: 1.2;">Audiolibro English</span>
                    <span style="font-size: 13px; color: #cbd5e0; text-shadow: 0 1px 4px rgba(0,0,0,0.9); margin-top: 3px;">Ingeniera Civil e Informática Nerbelis Valdeblanques</span>
                </div>
            </a>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="login.php" style="background: transparent; color: #ffffff; border: 1px solid rgba(255,255,255,0.6); padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">Iniciar Sesión</a>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL CENTRADO -->
    <main style="display: flex; justify-content: center; align-items: center; flex: 1; width: 100%; padding: 40px 20px; box-sizing: border-box;">
        <div class="card">
            <h2>Recuperar Contraseña</h2>

            <?php if (!empty($mensaje)) { echo "<div class='mensaje {$tipo_alerta}'>$mensaje</div>"; } ?>

            <form action="recuperar.php" method="POST">
                <label>Ingresa tu Correo Electrónico:</label>
                <input type="email" name="email" required placeholder="tucorreo@dominio.com">

                <button type="submit">Generar Nueva Contraseña</button>
            </form>

            <div style="margin-top: 15px; text-align: center;">
                <a href="login.php" style="display: inline-block; background: #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #cbd5e0; width: 100%; box-sizing: border-box;">← Volver al login</a>
            </div>
        </div>
    </main>

    <!-- FOOTER PROFESIONAL -->
    <footer style="width: 100%; background: #1a202c; color: #a0aec0; padding: 40px 20px; border-top: 1px solid #2d3748; text-align: center; box-sizing: border-box;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <p style="color: #ffffff; font-weight: bold; margin-bottom: 15px; font-size: 16px;">Audiolibro English</p>
            <p style="font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
                &copy; 2026 Audiolibro English. Todos los derechos reservados.<br>
                Plataforma desarrollada por la <strong>Ing. Civil e Informática Nerbelis Valdeblanques</strong>.
            </p>
            <p style="font-size: 11px; line-height: 1.6; margin: 0; color: #cbd5e0;">
                <strong>Nota legal / Exoneración de responsabilidad:</strong> Los certificados emitidos por esta plataforma tienen un carácter estrictamente formativo y de constancia interna de avance. No constituyen títulos académicos oficiales ni avales de acreditación educativa formal institucional, quedando los creadores exonerados de cualquier responsabilidad legal al respecto.
            </p>
        </div>
    </footer>

</body>
</html>