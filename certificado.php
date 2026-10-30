<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener los datos del usuario y su fecha de registro (fecha de inicio del curso)
$stmt = $conexion->prepare("SELECT nombre, email, fecha_registro FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();


// --- PEGA LA VALIDACIÓN AQUÍ MISMO ---
$stmt_mod = $conexion->prepare("SELECT COUNT(*) as total FROM accesos_modulos WHERE usuario_id = ? AND estado = 'aprobado'");
$stmt_mod->bind_param("i", $usuario_id);
$stmt_mod->execute();
$res_mod = $stmt_mod->get_result()->fetch_assoc();
$modulos_aprobados = $res_mod['total'];
$stmt_mod->close();

// Calcular el porcentaje de avance (10 módulos = 100%)
$porcentaje = ($modulos_aprobados / 10) * 100;

// Si no ha completado los 10 módulos, mostrar la vista con el porcentaje
if ($modulos_aprobados < 10) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Progreso del Certificado - Audiolibro English</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #1a202c; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .progreso-card { background: #2d3748; padding: 40px; border-radius: 12px; text-align: center; max-width: 500px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
            h2 { color: #f6ad55; }
            .btn-volver { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3182ce; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
            .btn-volver:hover { background: #2b6cb0; }
        </style>
    </head>
    <body>
        <div class="progreso-card">
            <h2>Certificado Bloqueado</h2>
            <p>Aún no cumples con el 100% de los módulos requeridos para emitir tu certificado.</p>
            <p><strong>Módulos aprobados:</strong> <?php echo $modulos_aprobados; ?> de 10</p>
            <p><strong>Tu progreso actual es del:</strong> <?php echo $porcentaje; ?>%</p>
            <a href="dashboard.php" class="btn-volver">Volver al Panel</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
// -------------------------------------

// Aplicar formato para que el nombre y apellido comiencen con mayúscula
$nombre_usuario_raw = $usuario['nombre'] ?? 'Estudiante';
$nombre_usuario = ucwords(strtolower(trim($nombre_usuario_raw)));

// Formatear la fecha de inicio y de emisión
$fecha_inicio_raw = $usuario['fecha_registro'] ?? date('Y-m-d');
$fecha_inicio = date("d/m/Y", strtotime($fecha_inicio_raw));
$fecha_emision = date("d/m/Y");

$curso_nombre = "Inglés desde Cero";
$total_modulos = "10";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Culminación - Audiolibro English</title>
    <!-- Fuente tipográfica elegante estilo Palmer / Cursiva -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
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
        .certificado-card {
            background: linear-gradient(135deg, #fdfbf7 0%, #f4f0ea 100%); /* Fondo crema/marfil suave y estético */
            padding: 40px;
            border-radius: 12px;
            max-width: 820px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            box-sizing: border-box;
            border: 8px solid #1a365d;
            outline: 3px solid #cbd5e0;
            outline-offset: -14px;
            text-align: center;
            position: relative;
        }
        .certificado-card h2 {
            color: #1a365d;
            font-size: 25px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitulo-cert {
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .nombre-estudiante {
            font-family: 'Great Vibes', cursive;
            font-size: 48px;
            font-weight: normal;
            color: #2c5282;
            margin: 10px 0;
            border-bottom: 2px solid #cbd5e0;
            display: inline-block;
            padding: 0 30px 5px 30px;
            text-transform: none !important; 
        }
        .texto-certificado {
            font-size: 14px;
            color: #2d3748;
            line-height: 1.5;
            margin-bottom: 15px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        /* Estilo para el esquema de módulos */
        .esquema-modulos {
            background: rgba(26, 54, 93, 0.04);
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            padding: 12px 15px;
            margin: 20px auto;
            max-width: 720px;
            text-align: left;
        }
        .esquema-modulos h4 {
            color: #1a365d;
            font-size: 13px;
            margin: 0 0 8px 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .grid-modulos {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px 15px;
            font-size: 12px;
            color: #4a5568;
        }
        .info-fechas {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            padding: 0 10px;
            font-size: 12px;
            color: #4a5568;
            align-items: flex-end;
        }
        .bloque-firma {
            text-align: center;
        }
        .linea-firma {
            border-top: 1px solid #4a5568;
            width: 160px;
            margin: 0 auto 3px auto;
        }
        .btn-accion {
            background-color: #319795;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
            margin-top: 20px;
        }
        .btn-accion:hover {
            background-color: #2c7a7b;
        }
        @media print {
            body {
                background: none !important;
            }
            header, footer, .no-print {
                display: none !important;
            }
            .certificado-card {
                background: #fdfbf7 !important;
                box-shadow: none !important;
                border: 6px solid #1a365d;
                outline: 2px solid #cbd5e0;
                outline-offset: -10px;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <header class="header-marca no-print" style="width: 100%; background: transparent !important; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; position: relative; z-index: 10; padding: 15px 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="dashboard.php" style="display: flex; align-items: center; gap: 15px; text-decoration: none;">
                <img src="img/logo.jpeg" alt="Nerco Logo" style="height: 75px; width: auto; border-radius: 8px; object-fit: contain;">
                <div style="display: flex; flex-direction: column;">
                    <span style="font-size: 20px; font-weight: bold; color: #ffffff; text-shadow: 0 2px 6px rgba(0,0,0,0.9); line-height: 1.2;">Audiolibro English</span>
                    <span style="font-size: 13px; color: #cbd5e0; text-shadow: 0 1px 4px rgba(0,0,0,0.9); margin-top: 3px;">Ingeniera Civil e Informática Nerbelis Valdeblanques</span>
                </div>
            </a>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="dashboard.php" style="background: transparent; color: #ffffff; border: 1px solid rgba(255,255,255,0.6); padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">Volver al Dashboard</a>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL CENTRADO -->
    <main style="display: flex; justify-content: center; align-items: center; flex: 1; width: 100%; padding: 40px 20px; box-sizing: border-box;">
        <div class="certificado-card">
            <h2>Constancia de Culminación</h2>
            <div class="subtitulo-cert">Nivel Básico - Guía Interactiva de Autoaprendizaje</div>

            <div class="texto-certificado" style="font-size: 13px; color: #718096; margin-bottom: 5px;">
                El presente reconocimiento se otorga con orgullo a:
            </div>

            <div class="nombre-estudiante">
                <?php echo htmlspecialchars($nombre_usuario); ?>
            </div>

            <div class="texto-certificado" style="margin-top: 10px; margin-bottom: 15px;">
                Por haber completado satisfactoriamente los <strong><?php echo $total_modulos; ?> módulos</strong> del programa interactivo <strong><?php echo htmlspecialchars($curso_nombre); ?></strong>, demostrando constancia, dedicación y compromiso en su proceso de formación autónoma en el idioma.
            </div>

            <!-- ESQUEMA DE LOS 10 MÓDULOS -->
            <div class="esquema-modulos">
                <h4>Esquema de Contenido Superado (10 Módulos)</h4>
                <div class="grid-modulos">
                    <div>• Módulo 1: Fundamentos y Saludos</div>
                    <div>• Módulo 6: Rutinas Diarias</div>
                    <div>• Módulo 2: Vocabulario Esencial</div>
                    <div>• Módulo 7: Pasado Simple</div>
                    <div>• Módulo 3: Estructura de Oraciones</div>
                    <div>• Módulo 8: Expresiones de Tiempo</div>
                    <div>• Módulo 4: Preguntas Frecuentes</div>
                    <div>• Módulo 9: Conversación Fluida</div>
                    <div>• Módulo 5: Verbos y Acciones</div>
                    <div>• Módulo 10: Evaluación y Cierre</div>
                </div>
            </div>

            <!-- BLOQUE DE FECHAS Y FIRMA -->
            <div class="info-fechas">
                <div style="text-align: left;">
                    <strong>Fecha de Inicio:</strong> <?php echo $fecha_inicio; ?><br>
                    <strong>Fecha de Emisión:</strong> <?php echo $fecha_emision; ?>
                </div>
                
            </div>

            <!-- BOTONES DE ACCIÓN (OCULTOS AL IMPRIMIR) -->
            <div class="no-print" style="margin-top: 20px;">
                <button onclick="window.print()" class="btn-accion">🖨️ Imprimir / Guardar como PDF</button>
                <div style="margin-top: 10px;">
                    <a href="dashboard.php" style="color: #319795; text-decoration: none; font-weight: bold; font-size: 14px;">← Volver al panel principal</a>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER PROFESIONAL -->
    <footer class="no-print" style="width: 100%; background: #1a202c; color: #a0aec0; padding: 25px 20px; border-top: 1px solid #2d3748; text-align: center; box-sizing: border-box;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <p style="color: #ffffff; font-weight: bold; margin-bottom: 8px; font-size: 14px;">Audiolibro English</p>
            <p style="font-size: 12px; line-height: 1.5; margin-bottom: 12px;">
                &copy; 2026 Audiolibro English. Todos los derechos reservados.<br>
                Plataforma desarrollada por la <strong>Ing. Civil e Informática Nerbelis Valdeblanques</strong>.
            </p>
            <p style="font-size: 11px; line-height: 1.5; margin: 0; color: #cbd5e0;">
                <strong>Nota legal / Exoneración de responsabilidad:</strong> Los certificados emitidos por esta plataforma tienen un carácter estrictamente formativo y de constancia interna de avance. No constituyen títulos académicos oficiales ni avales de acreditación educativa formal institucional, quedando los creadores exonerados de cualquier responsabilidad legal al respecto.
            </p>
        </div>
    </footer>

</body>
</html>