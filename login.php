<?php
session_start();
include 'conexion.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta adaptada a tus campos reales: id, nombre, password, suscripcion_activa
    $stmt = $conexion->prepare("SELECT id, nombre, password, suscripcion_activa, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña cifrada de forma segura
        if (password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['suscripcion'] = $usuario['suscripcion_activa'];

            if (isset($usuario['rol']) && $usuario['rol'] === 'admin') {
              $_SESSION['es_admin'] = true;
          } else {
           $_SESSION['es_admin'] = false;
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Audiolibro English</title>
    <style>
        body {
            margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    /* Aquí colocas la misma imagen de fondo que usas en el index */
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('img/fondo.jpeg') no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            color: #1a365d;
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            background-color: #fed7d7;
            color: #e53e3e;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #2d3748;
            font-size: 14px;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #718096;
            margin-top: 3px;
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
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        button[type="submit"]:hover {
            background-color: #2c7a7b;
        }
        .links-container {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }
        .links-container a {
            color: #319795;
            text-decoration: none;
            font-weight: bold;
        }
        .links-container a:hover {
            text-decoration: underline;
        }
        .separator {
            margin: 0 8px;
            color: #cbd5e0;
        }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4a5568;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-back:hover {
            color: #319795;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Contenedor principal centrado entre el header y el footer -->
<main style="display: flex; justify-content: center; align-items: center; flex: 1; width: 100%; padding: 40px 20px; box-sizing: border-box;">

    <div class="card" style="background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 8px 24px rgba(0,0,0,0.3);">
        <h2>Iniciar Sesión</h2>

        <?php if ($error != "") { echo "<div class='error'>$error</div>"; } ?>

        <form action="login.php" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="email" required>

            <label>Contraseña:</label>
            <div class="password-container">
                <input type="password" name="password" id="passwordField" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
            </div>

            <button type="submit">Entrar al Sistema</button>
        </form>

        <div class="links-container" style="text-align: center; margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
            <div>
                <a href="registro.php">Regístrate aquí</a>
                <span class="separator">|</span>
                <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
            </div>
            
            <!-- Botón de Volver al Inicio con margen superior separado -->
            <div style="margin-top: 15px;">
                <a href="index.php" style="display: inline-block; background: #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #cbd5e0; width: 100%; box-sizing: border-box; text-align: center;">← Volver al Inicio</a>
            </div>
        </div
    </div>
     

</main>

        

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
<<<<<<< HEAD
  <footer style="text-align: center; margin-top: 40px; padding: 20px; font-size: 13px; color: #ffffff; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <p style="margin: 0 0 5px 0; color: #ffffff;">Audiolibro English &copy; 2026 | Todos los derechos reservados.</p>
            <p style="margin: 0 0 10px 0; color: #cbd5e0;">Plataforma desarrollada por la Ingeniera Civil e Informática Nerbelis Valdeblanques.</p>
            <a href="privacidad.php" style="color: #63b3ed; text-decoration: none; font-weight: bold;">Términos y Privacidad</a>
        </footer>
</body>
</html>
=======

</body>
</html>
>>>>>>> f03d1d4dd3d53859c742270dc79d05937766b1a3
