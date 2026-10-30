<?php
session_start();
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Política de Privacidad - Audiolibro English</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 850px; margin: 40px auto; background: #fff; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a365d; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; font-size: 24px; }
        h2 { color: #2b6cb0; margin-top: 30px; font-size: 18px; }
        p, li { line-height: 1.6; font-size: 14px; }
        ul { padding-left: 20px; }
        .back-link { display: inline-block; margin-top: 25px; color: #319795; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Términos, Condiciones y Política de Privacidad</h1>
        <p><em>Última actualización: Octubre 2026</em></p>

        <!-- SECCIÓN DE PRIVACIDAD -->
        <h2>1. Política de Privacidad y Protección de Datos</h2>
        <p>En <strong>Audiolibro English</strong> valoramos la privacidad de nuestros usuarios. Recopilamos información personal básica estrictamente necesaria para el funcionamiento de la plataforma, tales como:</p>
        <ul>
            <li>Nombre y apellido.</li>
            <li>Correo electrónico de acceso.</li>
            <li>Registros de pagos y transacciones para la habilitación de módulos.</li>
        </ul>

        <h2>2. Seguridad de las Contraseñas (Cifrado Hash)</h2>
        <p>La seguridad de tu cuenta es primordial. Las contraseñas que registras en nuestra plataforma son procesadas y almacenadas mediante algoritmos avanzados de cifrado en formato <strong>hash</strong>. Esto garantiza que <strong>ni siquiera los administradores del sistema pueden visualizar tu contraseña real</strong> en texto plano, protegiendo tus datos ante cualquier vulnerabilidad.</p>

        <h2>3. Uso de la Información</h2>
        <p>Los datos recolectados se emplean exclusivamente para:</p>
        <ul>
            <li>Gestionar el acceso a tu cuenta de usuario.</li>
            <li>Verificar los pagos reportados y autorizar el desbloqueo de los módulos de audiolibros correspondientes.</li>
            <li>Enviar notificaciones operativas o de soporte técnico sobre el servicio.</li>
        </ul>

        <h2>4. Uso de Cookies</h2>
        <p>Este sitio web utiliza cookies técnicas esenciales (como las sesiones de PHP) destinadas únicamente a mantener tu sesión activa mientras navegas por los contenidos, asegurando una experiencia fluida y sin rastreo publicitario de terceros.</p>

        <!-- SECCIÓN DE TÉRMINOS Y CONDICIONES -->
        <h2>5. Términos y Condiciones de Uso</h2>
        <p>Al registrarte y utilizar <strong>Audiolibro English</strong>, aceptas los siguientes lineamientos de uso:</p>
        <ul>
            <li><strong>Uso Personal e Intransferible:</strong> Los contenidos educativos y audiolibros desbloqueados son para uso estrictamente personal del usuario registrado. Queda prohibida la reproducción masiva, reventa, préstamo de credenciales o redistribución no autorizada del material.</li>
            <li><strong>Validación de Pagos:</strong> El acceso a los módulos de pago está sujeto a la verificación y aprobación manual o automatizada de los comprobantes de transacción enviados por el usuario.</li>
            <li><strong>Disponibilidad del Servicio:</strong> La plataforma se proporciona tal cual para entornos locales y web, procurando mantener la estabilidad y el resguardo de los registros en la base de datos.</li>
        </ul>

        <a href="index.php" class="back-link">&larr; Volver al inicio</a>
    </div>
</body>
</html>