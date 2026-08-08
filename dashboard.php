<?php
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 0;
$es_admin = $_SESSION['es_admin'] ?? false;
include 'conexion.php';

$mensaje_alerta = "";

// 1. Procesar el Registro
if (isset($_POST['registrar'])) {
    $nombre = $_POST['reg_nombre'];
    $email = $_POST['reg_email'];
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
    if ($conexion->query($sql) === TRUE) {
        $mensaje_alerta = "¡Registro exitoso! Por favor, inicia sesión.";
    } else {
        $mensaje_alerta = "Error en el registro o correo duplicado.";
    }
}

// 2. Procesar el Login (Con acceso de Administrador especial)
if (isset($_POST['ingresar'])) {
    $email = $_POST['log_email'];
    $password = $_POST['log_password'];

    // CLAVE DE ADMINISTRADOR MAESTRA
    if ($email === 'admin@sistema.com' && $password === 'admin123') {
        $_SESSION['usuario_id'] = 999999; // ID ficticio de admin
        $_SESSION['nombre'] = 'Administrador Nerco';
        $_SESSION['es_admin'] = true;
        header("Location: dashboard.php");
        exit();
    }

    // Login normal para usuarios comunes
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE email = '$email'");
    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['es_admin'] = false;
            header("Location: dashboard.php");
            exit();
        } else {
            $mensaje_alerta = "Contraseña incorrecta.";
        }
    } else {
        $mensaje_alerta = "El correo no está registrado.";
    }
}

// Verificar sesión activa
$sesion_activa = isset($_SESSION['usuario_id']);
$es_admin = isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true;
$tiene_acceso = $es_admin; // Si es admin, tiene acceso total automático
// Consultar los estados de los módulos del usuario (aprobado, pendiente, etc.)
$estados_modulos = [];
if ($sesion_activa && !$es_admin) {
    $usuario_id = $_SESSION['usuario_id'];
    $stmt_mod = $conexion->prepare("SELECT numero_modulo, estado FROM accesos_modulos WHERE usuario_id = ?");
    $stmt_mod->bind_param("i", $usuario_id);
    $stmt_mod->execute();
    $resultado_mod = $stmt_mod->get_result();
    while ($row = $resultado_mod->fetch_assoc()) {
        // Guardamos el estado usando el número de módulo como índice, por ejemplo: $estados_modulos[3] = 'pendiente'
        $estados_modulos[$row['numero_modulo']] = $row['estado']; 
    }
    if ($sesion_activa && !$es_admin) {
    $sql_pagos_pend = "SELECT numero_modulo FROM pagos WHERE usuario_id = ? AND estado = 'pendiente'";
    $stmt_pp = $conexion->prepare($sql_pagos_pend);
    $stmt_pp->bind_param("i", $usuario_id);
    $stmt_pp->execute();
    $res_pp = $stmt_pp->get_result();
    while ($row_p = $res_pp->fetch_assoc()) {
        // Si hay un pago pendiente, forzamos el estado a 'pendiente' para que el botón cambie
        $estados_modulos[$row_p['numero_modulo']] = 'pendiente';}
    }
    $stmt_mod->close();
}
if ($sesion_activa && !$es_admin) {
    $usuario_id = $_SESSION['usuario_id'];
    $verificar_pago = $conexion->query("SELECT * FROM pagos WHERE usuario_id = '$usuario_id' AND estado = 'aprobado'");
    $tiene_acceso = ($verificar_pago && $verificar_pago->num_rows > 0);
}

// Lista de los 10 módulos
$lista_modulos = [
    1 => ["titulo" => "Módulo 1: Saludos y Despedidas", "desc" => "Expresiones fundamentales para saludar y presentarte.", "gratis" => true],
    2 => ["titulo" => "Módulo 2: Cortesía Básica", "desc" => "Frases de educación elemental para desenvolverte con amabilidad.", "gratis" => true],
    3 => ["titulo" => "Módulo 3: En el Salón de Clases", "desc" => "Vocabulario esencial para nombrar útiles escolares.", "gratis" => false],
    4 => ["titulo" => "Módulo 4: Números y Colores", "desc" => "Aprende a contar elementos y describir tonalidades visuales.", "gratis" => false],
    5 => ["titulo" => "Módulo 5: Miembros de la Familia", "desc" => "Identifica y describe a los integrantes de tu núcleo familiar.", "gratis" => false],
    6 => ["titulo" => "Módulo 6: Partes del Cuerpo", "desc" => "Reconoce de manera visual y verbal las partes del cuerpo humano.", "gratis" => false],
    7 => ["titulo" => "Módulo 7: Días y Meses", "desc" => "Organiza tus rutinas semanales manejando el calendario.", "gratis" => false],
    8 => ["titulo" => "Módulo 8: Emociones", "desc" => "Aprende a comunicar cómo te sientes anímica o físicamente.", "gratis" => false],
    9 => ["titulo" => "Módulo 9: Animales", "desc" => "Vocabulario clásico de animales comunes y de la granja.", "gratis" => false],
    10 => ["titulo" => "Módulo 10: Práctica Rítmica", "desc" => "Suelta la lengua con una tonada rítmica para fijar la pronunciación.", "gratis" => false]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Landscape - Inglés desde Cero</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; margin: 0; padding: 10px; color: #2d3748; }
        
        .container-landscape { max-width: 1300px; margin: 0 auto; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        
        .header-marca { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px; }
        .marca-logo { font-size: 18px; font-weight: bold; color: #1a365d; display: flex; align-items: center; gap: 8px; }
        .marca-logo span { color: #319795; }
        .logout-btn { background: #e53e3e; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; }
        
        .alerta { background: #ebf8ff; border: 1px solid #bee3f8; color: #2b6cb0; padding: 8px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; text-align: center; }

        .main-grid { display: grid; grid-template-columns: 350px 1fr; gap: 20px; }

        .panel-izq { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; height: fit-content; }
        .panel-izq h3 { color: #1a365d; margin-top: 0; font-size: 16px; border-bottom: 2px solid #319795; padding-bottom: 6px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 3px; color: #4a5568; }
        .form-group input { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e0; border-radius: 4px; font-size: 12px; }
        .btn-submit { background-color: #319795; color: white; border: none; padding: 8px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; font-size: 13px; margin-top: 5px; }
        .btn-submit:hover { background-color: #2c7a7b; }

        .panel-der { display: flex; flex-direction: column; gap: 15px; }
        .intro-box { background: #e6fffa; border: 1px solid #b2f5ea; padding: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; }
        .intro-texto h2 { color: #234e52; margin: 0 0 5px 0; font-size: 18px; }
        .intro-texto p { color: #2c7a7b; margin: 0; font-size: 13px; line-height: 1.5; }

        h1 { color: #1a365d; font-size: 16px; margin: 0 0 10px 0; }

        .modulos-grid-landscape { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .modulo-card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #319795; padding: 10px 12px; border-radius: 0 6px 6px 0; display: flex; justify-content: space-between; align-items: center; }
        .modulo-bloqueado { border-left-color: #cbd5e0; background: #edf2f7; }
        .modulo-info h3 { color: #1a365d; margin: 0 0 3px 0; font-size: 13px; }
        .modulo-info p { color: #4a5568; margin: 0; font-size: 11px; }
        
        .btn-entrar { background-color: #319795; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; white-space: nowrap; }
        .btn-entrar:hover { background-color: #2c7a7b; }
        .btn-bloqueado { background-color: #a0aec0; }

        .banner-final { background: #fffaf0; border: 1px solid #feebc8; padding: 12px; border-radius: 6px; text-align: center; }
        .banner-final h3 { color: #744210; margin: 0 0 5px 0; font-size: 15px; }
        .banner-final p { color: #975a16; margin: 0 0 10px 0; font-size: 12px; }
        .btn-pago { display: inline-block; background: #dd6b20; color: white; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 12px; text-decoration: none; }

        .footer-autor { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0; color: #718096; font-size: 11px; line-height: 1.5; }
        .footer-autor strong { color: #4a5568; }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
            .modulos-grid-landscape { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container-landscape">
    
     <!-- Cabecera con Logo y Usuario (Ya la tienes lista) -->
     <!-- CABECERA UNIFICADA Y CENTRADA -->
    <header style="width: 100%; max-width: 1200px; margin: 20px auto 0 auto; background: rgba(255, 255, 255, 0.95); padding: 15px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); box-sizing: border-box; display: flex; justify-content: space-between; align-items: center;">
        
        <!-- Logo y Título -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="img/logo.jpeg" alt="Logo" style="height: 40px; width: auto; border-radius: 6px;">
            <span style="font-size: 16px; font-weight: bold; color: #1a365d;">
                Nercovalch | Inglés desde Cero
            </span>
        </div>

        <!-- Opciones de Usuario y Botones -->
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
            <span style="font-size: 12px; font-weight: bold; color: #4a5568;">
                <?php echo $es_admin ? '🛡️ [ADMINISTRADOR]' : 'Usuario:'; ?> 
            <span style="color: #2b6cb0;"><?php echo htmlspecialchars($nombre_usuario ?? $_SESSION['nombre'] ?? ''); ?></span>
             </span>
            
            <a href="pagos.php" style="background-color: #319795; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold;">💳 Reportar Pago</a>
            
            <a href="logout.php" style="background-color: #e53e3e; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold;">Cerrar Sesión</a>
        </div>

    </header>

     <!-- CONTENEDOR PRINCIPAL HORIZONTAL -->
    <div class="container-landscape" style="max-width: 1200px; margin: 20px auto; padding: 0 20px;">

    <!-- Mensaje de alerta si existe -->
    <?php if (!empty($mensaje_alerta)): ?>
        <div class="alerta"><?php echo $mensaje_alerta; ?></div>
    <?php endif; ?>

    <!-- SECCIÓN ÚNICA ANCHA (Ocupa todo el horizontal) -->
    <div class="panel-der" style="width: 100% !important; margin: 0 auto;">
        
        <!-- Bloque de Bienvenida -->
        <div class="intro-box" style="display: flex; align-items: center; justify-content: space-between; background: #308bbd; padding: 25px; border-radius: 8px; border: 1px solid #90cdf4; margin-bottom: 25px;">
            <div class="intro-texto">
                <h2 style="color: #2b6cb0; margin-bottom: 8px;">¡Aprende Inglés de Forma Fácil y Cotidiana!</h2>
                <p style="color: #718096; font-size: 14px;">Software interactivo de consulta y autoaprendizaje estructurado en módulos prácticos.</p>
            </div>
            <div style="font-size: 35px;">🎧✨</div>
        </div>

        <!-- Título de Módulos -->
        <h1 style="font-size: 20px; color: #2d3748; margin-bottom: 15px;">Listado General de Módulos (Nivel Básico)</h1>
          <?php
      if (!function_exists('verificarEstadoModulo')) {
    
    }        function verificarEstadoModulo($conexion, $usuario_id, $mod_actual) {
        // 1. VERIFICAR PAGO PENDIENTE PRIMERO QUE NADA
    
    
    // 1. Si es admin, aprobado directo
    if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true) {
        return 'aprobado';
    }
    
    // 2. Los módulos 1 y 2 son libres por defecto
    if ($mod_actual == 1 || $mod_actual == 2) {
        return 'aprobado';
    }

    // 3. LA REGLA DE ORO: Si ESTE módulo ya está aprobado en accesos_modulos, RETORNAR 'aprobado' DE INMEDIATO
    $sql_actual_directo = "SELECT estado FROM accesos_modulos WHERE usuario_id = $usuario_id AND numero_modulo = $mod_actual";
    $res_actual_directo = mysqli_query($conexion, $sql_actual_directo);
    if ($res_actual_directo && $row_dir = mysqli_fetch_assoc($res_actual_directo)) {
        if ($row_dir['estado'] == 'aprobado') {
            return 'aprobado';
        }
    }

    // 4. REGLA SECUENCIAL: Verificar si el módulo ANTERIOR está aprobado
    $modulo_anterior = $mod_actual - 1;
    $sql_check = "SELECT estado FROM accesos_modulos WHERE usuario_id = $usuario_id AND numero_modulo = $modulo_anterior";
    $resultado = mysqli_query($conexion, $sql_check);

    $anterior_aprobado = false;
    if ($fila = mysqli_fetch_assoc($resultado)) {
        if ($fila['estado'] == 'aprobado') {
            $anterior_aprobado = true;
        }
    }

    // 5. Verificar si hay un pago PENDIENTE para este módulo
    $sql_vp = "SELECT estado FROM pagos WHERE usuario_id = $usuario_id AND numero_modulo = $mod_actual AND estado = 'pendiente'";
    $res_vp = mysqli_query($conexion, $sql_vp);
    if ($res_vp && $fila_vp = mysqli_fetch_assoc($res_vp)) {
        return 'pendiente';
    }

    // 6. Si el anterior NO está aprobado, se mantiene bloqueado por secuencia
    if (!$anterior_aprobado) {
        return 'bloqueado_secuencia';
    }

    // 7. Si el anterior SÍ está aprobado, pero no hay pago ni registro, está listo para reportar
    return 'no_solicitado';
}

          
             
             ?>
        <!-- Grilla de Módulos (Se acomodará horizontalmente en varias columnas según tu CSS) -->
        <div class="modulos-grid-landscape">
            <?php foreach ($lista_modulos as $id => $mod): ?>
                <?php $es_accesible = $mod['gratis'] || $tiene_acceso; ?>
                <div class="modulo-card <?php echo !$es_accesible ? 'modulo-bloqueado' : ''; ?>">
                    <div class="modulo-info">
                        <h3><?php echo $mod['titulo']; ?></h3>
                        <p><?php echo $mod['desc']; ?></p>
                    </div>
                    <div>
                       
                                      <?php
                                
    $estado_modulo = verificarEstadoModulo($conexion,$usuario_id, $id);

    // Si el módulo está marcado como gratis, lo aprobamos de una vez
    if (!empty($mod['gratis'])) {
        $estado_modulo = 'aprobado';
    }
    ?>                    
    
                                    <!-- Ahora cualquier módulo bloqueado o disponible se vuelve clickeable y lleva al pago con la manita -->
                                 <?php if ($estado_modulo == 'bloqueado_secuencia'): ?>
    <!-- Cambiamos el botón deshabilitado por un enlace de pago -->
    <a href="#datos-pago" class="btn-entar btn-bloqueado">🔒 Bloqueado (Pagar)</a>

<?php elseif ($estado_modulo == 'no_solicitado'): ?>
    <a href="pagos.php?modulo=<?php echo $id; ?>" class="btn-entar btn-comprar">Reportar Pago 💳</a>

<?php elseif ($estado_modulo == 'pendiente'): ?>
    <button class="btn-entar btn-warning" disabled>⏳ Pendiente</button>

<?php elseif ($estado_modulo == 'aprobado'): ?>
    <a href="detalle_modulo.php?id=<?php echo $id; ?>" class="btn-entar">Entrar &rarr;</a>
<?php endif; ?>
                    </div> 
                   
                    </div> 
             <?php endforeach; ?>

               </div> 
            



       
         <?php
            // Si es administrador o tiene pago, el avance es 100%
                $porcentaje_avance = $tiene_acceso ? 100 : 20; 
            ?>

            <!-- SECCIÓN DE CONSTANCIA DE AVANCE -->
            <div style="background: linear-gradient(135deg, #e6fffa 0%, #ebf8ff 100%); border: 2px dashed #319795; padding: 18px; border-radius: 8px; text-align: center;">
                <h3 style="color: #1a365d; margin-top: 0; font-size: 15px;">🎓 Constancia de Avance: Nivel Básico</h3>
                <p style="font-size: 12px; color: #4a5568; margin-bottom: 10px;">
                    Progreso general en la plataforma: <strong><?php echo $porcentaje_avance; ?>% completado</strong>
                </p>

                <!-- Barra de progreso visual -->
                <div style="background: #cbd5e0; border-radius: 10px; height: 10px; width: 100%; max-width: 450px; margin: 0 auto 12px auto; overflow: hidden;">
                    <div style="background: #319795; height: 100%; width: <?php echo $porcentaje_avance; ?>%; border-radius: 10px;"></div>
                </div>

              <?php if ($tiene_acceso): ?>
    <div style="background: white; border: 1px solid #319795; padding: 12px; border-radius: 6px; max-width: 550px; margin: 0 auto;">
        <p style="color: #234e52; font-size: 12px; margin: 0 0 8px 0; font-weight: bold;">
            ☀️ ¡Constancia de culminación disponible para visualización e impresión!
        </p>
        <a href="certificado.php" target="_blank" style="display: inline-block; background: #319795; color: white; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 11px; text-decoration: none;">
            Ver mi Certificado de Culminación
        </a>
    </div>

    
    </p><?php else: ?>
    <!-- Tu aviso original de bloqueo -->
    <p style="color: #718096; font-size: 11px; margin: 0 0 10px 0;">
        🔒 Desbloquea el acceso total a los 10 módulos para completar tu porcentaje al 100% y obtener tu constancia.
    </p>

    <!-- Menú Desplegable Oculto por Defecto -->
    <details style="background: #f7fafc; border: 1px solid #319795; border-radius: 6px; max-width: 380px; margin: 10px auto 0 auto; padding: 10px; text-align: left;">
        <summary style="cursor: pointer; color: #1a365d; font-size: 11px; font-weight: bold; outline: none; text-align: center;">
            💳 ¿Deseas desbloquear? Ver datos de pago móvil (500 Bs)
            
        </summary>
        <div style="text-align: center; margin-top: 15px;">
    <a href="pagos.php" style="background-color: #319795; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; display: inline-block; box-shadow: 0 3px 6px rgba(0,0,0,0.15); transition: background 0.2s;">
        💳 Reportar Pago para Desbloquear Modulo
    </a>
</div>
        
        <!-- Contenido de los datos que se despliega al hacer clic -->

        <div id="datos-pago" style="margin-top: 10px; border-top: 1px dashed #cbd5e0; padding-top: 8px;">
            <p style="font-size: 11px; color: #2d3748; margin: 4px 0;"><strong>Banco:</strong> Banco de Venezuela</p>
            <p style="font-size: 11px; color: #2d3748; margin: 4px 0;"><strong>Tipo de Cuenta:</strong> Corriente</p>
            <p style="font-size: 11px; color: #2d3748; margin: 4px 0;"><strong>Teléfono:</strong> 04246163113</p>
            <p style="font-size: 11px; color: #2d3748; margin: 4px 0;"><strong>Cédula / RIF:</strong> V-11282487</p>
            <p style="font-size: 10px; color: #718096; margin-top: 8px; text-align: center; border-top: 1px dashed #cbd5e0; padding-top: 6px;">
                * Reporta tu pago para obtener al Momento de 20 min el acceso al Modulo pagado.
            </p>
        </div>
    </details>
<?php endif; ?>



        

    </div>

   

</div>

<!-- Cierra las secciones de arriba y deja el pie de página libre al final -->

</div> <!-- Cierre del contenedor principal o grid si lo tienes -->
<div style="margin: 20px 0;">
    <button onclick="history.back()" style="cursor: pointer; display: inline-block; background: #e2e8f0; color: #4a5568; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #cbd5e0;">← Volver atrás</button>
</div>

<!-- Pie de página completo a lo ancho de toda la pantalla -->
<footer style="width: 100%; background-color: #1a365d; color: #ffffff; text-align: center; padding: 15px 20px; margin-top: 30px; box-sizing: border-box;">
    <p style="font-size: 11px; margin: 0; line-height: 1.4;">
           Esta plataforma opera exclusivamente como un repositorio tecnológico y recurso de consulta digital basado en guías de estudio libre. No constituye una institución formal ni imparte enseñanza reglada.<br>
        Desarrollo de infraestructura y arquitectura de software por la <strong>Ingeniera Civil e Informática Nerbelis Valdeblanques</strong>.<br>
        El uso de este sistema es de carácter lúdico y de autoaprendizaje, quedando exento el desarrollador de cualquier responsabilidad sobre acreditaciones académicas o de dominio lingüístico. &copy; 2026.
    
    </p>
</footer>
</body>
</html>