<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audiolibro English - Aprende Inglés Escuchando</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            color: #2d3748;
            line-height: 1.6;
        }
        /* Header / Navbar superior */
        header {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #1a365d;
            text-decoration: none;
        }
        .nav-buttons a {
            margin-left: 15px;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-login {
            color: #319795;
            border: 1px solid #319795;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background-color: #319795;
            color: white;
        }
        .btn-register {
            background-color: #319795;
            color: white;
        }
        .btn-register:hover {
            background-color: #2c7a7b;
        }

        /* Hero Section (Sección Principal) */
        .hero {
            background: linear-gradient(rgba(26, 54, 93, 0.85), rgba(26, 54, 93, 0.85)), url('img/fondo.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 80px 20px;
        }
        .hero h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #ffffff;
        }
        .hero p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px auto;
            color: #e2e8f0;
        }
        .hero-cta {
            background-color: #319795;
            color: white;
            padding: 14px 30px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .hero-cta:hover {
            background-color: #2c7a7b;
        }

        /* Contenido / Secciones de la Landing */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .section-title {
            text-align: center;
            color: #1a365d;
            font-size: 28px;
            margin-bottom: 30px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 4px solid #319795;
        }
        .card.premium {
            border-top-color: #d69e2e;
        }
        .card h3 {
            color: #1a365d;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .card p {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .badge-free {
            background-color: #c6f6d5;
            color: #22543d;
        }
        .badge-premium {
            background-color: #feebc8;
            color: #744210;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 20px;
            background-color: #edf2f7;
            color: #4a5568;
            font-size: 13px;
            line-height: 1.8;
            border-top: 1px solid #e2e8f0;
        }
        footer .disclaimer {
            max-width: 750px;
            margin: 15px auto 0 auto;
            color: #718096;
            font-size: 12px;
            text-align: justify;
        }
    </style>
</head>
<body>

    <!-- Barra Superior -->
    <header class="header-marca" style="width: 100%; background: transparent !important; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; position: relative; z-index: 10; padding: 15px 30px;">
    
    <!-- Logo y Bloque de Texto a la Izquierda -->
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="img/logo.jpeg" alt="Nerco Logo" style="height: 75px; width: auto; border-radius: 8px; object-fit: contain;">
        
        <!-- Contenedor del Título y los Títulos Profesionales en dos líneas -->
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 20px; font-weight: bold; color: #ffffff; text-shadow: 0 2px 6px rgba(0,0,0,0.9); line-height: 1.2;">Audiolibro English</span>
            <span style="font-size: 13px; color: #cbd5e0; text-shadow: 0 1px 4px rgba(0,0,0,0.9); margin-top: 3px;">Ing. Civil e Informática Nerbelis Valdeblanques</span>
        </div>
    </div>

    <!-- Botones de Acción a la Derecha -->
    <div style="display: flex; gap: 12px; align-items: center;">
        <a href="login.php" style="background: transparent; color: #3182ce; border: 1px solid #3182ce; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">Iniciar Sesión</a>
        
        <a href="registro.php" style="background: #3182ce; color: #ffffff; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">Registrarse</a>
    </div>

</header>

    <!-- Sección Hero Principal -->
    <section class="hero">
        <h1>Domina el Inglés Escuchando y sin Esfuerzo</h1>
        <p>Una metodología diseñada para jóvenes, niños y adultos que desean conectar con los libros y potenciar su comprensión auditiva desde cualquier lugar.</p>
        <?php if (!isset($_SESSION['usuario_id'])): ?>
            <a href="login.php" class="hero-cta">¡Empieza Gratis Ahora!</a>
        <?php else: ?>
            <a href="dashboard.php" class="hero-cta">Continuar Aprendiendo</a>
        <?php endif; ?>
    </section>

    <!-- Contenido y Módulos -->
   <!-- SECCIÓN DE MÓDULOS (Solo Módulo 1 y Módulo 2 básicos) -->
    <section class="modulos-container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        <h2 style="text-align: center; color: #2d3748; margin-bottom: 30px;">Nuestros Módulos de Aprendizaje</h2>
        
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <!-- Módulo 1 -->
            <div class="modulo-card" style="background: white; border: 1px solid #cbd5e0; padding: 20px; border-radius: 8px; width: 45%; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <span style="background: #c6f6d5; color: #22543d; padding: 3px 8px; font-size: 11px; border-radius: 4px; font-weight: bold;">GRATIS</span>
                <h3 style="margin: 10px 0; color: #2d3748;">Módulo 1: Fundamentos y Vocabulario</h3>
                <p style="font-size: 13px; color: #4a5568;">Comienza tu viaje con bases sólidas, pronunciación guiada y los audiolibros esenciales.</p>
                <a href="detalle_modulo.php?id=1" style="display: inline-block; margin-top: 15px; color: #3182ce; text-decoration: none; font-weight: bold;">Acceder al Módulo &rarr;</a>
            </div>

            <!-- Módulo 2 -->
            <div class="modulo-card" style="background: white; border: 1px solid #cbd5e0; padding: 20px; border-radius: 8px; width: 45%; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <span style="background: #c6f6d5; color: #22543d; padding: 3px 8px; font-size: 11px; border-radius: 4px; font-weight: bold;">GRATIS</span>
                <h3 style="margin: 10px 0; color: #2d3748;">Módulo 2: Comprensión Auditiva Básica</h3>
                <p style="font-size: 13px; color: #4a5568;">Ejercicios interactivos con historias cortas y narraciones adaptadas para estudiantes.</p>
                <a href="detalle_modulo.php?id=2" style="display: inline-block; margin-top: 15px; color: #3182ce; text-decoration: none; font-weight: bold;">Acceder al Módulo &rarr;</a>
            </div>
        </div>
    </section>

    <!-- FOOTER PROFESIONAL DE ANCHO COMPLETO -->
<!-- FOOTER PROFESIONAL DE ANCHO COMPLETO (UNA SOLA COLUMNA) -->
<footer style="width: 100%; background: #1a202c; color: #a0aec0; padding: 40px 20px; margin-top: 60px; border-top: 1px solid #2d3748; text-align: center; box-sizing: border-box;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        <!-- Título -->
        <p style="color: #ffffff; font-weight: bold; margin-bottom: 15px; font-size: 16px;">
            Audiolibro English
        </p>
        
        <!-- Derechos de Autor y Desarrolladora -->
        <p style="font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
            &copy; 2026 Audiolibro English. Todos los derechos reservados.<br>
            Plataforma desarrollada por la <strong>Ing. Civil e Informática Nerbelis Valdeblanques</strong>.
        </p>

        <!-- Exoneración Legal -->
        <p style="font-size: 11px; line-height: 1.6; margin: 0; color: #cbd5e0;">
            <strong>Nota legal / Exoneración de responsabilidad:</strong> Los certificados emitidos por esta plataforma tienen un carácter estrictamente formativo y de constancia interna de avance. No constituyen títulos académicos oficiales ni avales de acreditación educativa formal institucional, quedando los creadores exonerados de cualquier responsabilidad legal al respecto.
        </p>

    </div>
<<<<<<< HEAD
    <a href="privacidad.php" style="color: #63b3ed; text-decoration: none; font-weight: bold;">Términos y Privacidad</a>
=======
>>>>>>> f03d1d4dd3d53859c742270dc79d05937766b1a3
</footer>
<script>
    // 1. Deshabilitar el clic derecho
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('Acción protegida.'); // Puedes quitar el alert si prefieres que no salga aviso
    });

    // 2. Bloquear combinaciones de teclas para inspeccionar código o guardar
    document.addEventListener('keydown', function(e) {
        // Bloquear F12
        if (e.key === 'F12') {
            e.preventDefault();
            return false;
        }
        // Bloquear Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S
        if (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S')) {
            e.preventDefault();
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.key === 'i' || e.key === 'I' || e.key === 'j' || e.key === 'J' || e.key === 'c' || e.key === 'C')) {
            e.preventDefault();
            return false;
        }
    });
</script>
    

</body>
</html>