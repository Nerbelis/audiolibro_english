<?php
/**
 * pagos.php - Módulo de Reporte de Pagos Secuencial
 * Configurado con el Chat ID de Telegram: 1526051706
 */

session_start();
include_once 'conexion.php'; 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$modulo = isset($_GET['modulo']) ? intval($_GET['modulo']) : 3;

// Los módulos 1 y 2 son gratuitos
if ($modulo < 3) {
    header("Location: dashboard.php");
    exit();
}

if ($modulo > 10) {
    $modulo = 3;
}

$mensaje = "";
$tipo_alerta = "";

// Procesar el formulario cuando se envía el reporte de pago

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
 // Esto detiene la ejecución para que veas qué datos llegaron exactamente
    $nivel_solicitado = intval($_POST['nivel_solicitado'] ?? $modulo);
    
    if ($nivel_solicitado < 3) {
        header("Location: dashboard.php");
        exit();
    }
  // --- AQUÍ METES LA VALIDACIÓN DE SECUENCIA ESTRICTA ---
    if ($nivel_solicitado >= 3) {
        
        $sql_secuencia = "SELECT estado FROM accesos_modulos WHERE usuario_id = ? AND numero_modulo = ?";
        $stmt_sec = $conexion->prepare($sql_secuencia);
        $stmt_sec->bind_param("ii", $usuario_id, $modulo_anterior);
        $stmt_sec->execute();
        $res_sec = $stmt_sec->get_result();
        $fila_sec = $res_sec->fetch_assoc();
        $stmt_sec->close();

        if (!$fila_sec || $fila_sec['estado'] !== 'aprobado') {
            $mensaje = "Error: No puedes reportar este módulo porque aún no tienes aprobado el Módulo " . $modulo_anterior . ".";
            $tipo_alerta = "error";
        } else {
            $nivel_solicitado = $modulo_anterior;
        }
    }
    
    // 1. Captura limpia de datos

    $nivel_solicitado = intval($_POST['nivel_solicitado'] ?? $modulo);
    $referencia = trim($_POST['referencia_transaccion'] ?? '');
    $banco = trim($_POST['metodo_pago'] ?? '');
    $monto = floatval($_POST['monto'] ?? 0);
    $fecha = trim($_POST['fecha_pago'] ?? ''); 
 }
    // 2. Validación de campos obligatorios
    if (empty($referencia) || empty($banco) || $monto <= 0 || empty($fecha)) {
        $mensaje = "Por favor, complete todos los campos obligatorios del pago."; 
        $tipo_alerta = "error";
         } else {
        // Verificar si ya existe un registro previo para este módulo
        $stmt_check = $conexion->prepare("SELECT id FROM pagos WHERE usuario_id = ? AND numero_modulo = ?");
        $stmt_check->bind_param("ii", $usuario_id, $nivel_solicitado);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $mensaje = "Ya tienes un registro o reporte para este módulo.";
            $tipo_alerta = "error";
            $stmt_check->close();
        } else {
            $stmt_check->close();
            $numero_modulo = $nivel_solicitado;
            $metodo_pago = $banco;
            $referencia_transaccion = $referencia;
            $fecha_pago = $fecha;
            // 1. Insertar el pago directamente
            $stmt_pago = $conexion->prepare("INSERT INTO pagos (usuario_id, numero_modulo, metodo_pago, monto, referencia_transaccion,fecha_pago) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_pago->bind_param("iisdss", $usuario_id,$numero_modulo,$metodo_pago,$monto,$referencia_transaccion,$fecha_pago);
            
            if ($stmt_pago->execute()) {
                $stmt_pago->close();
                
                // 2. Registrar o actualizar en accesos_modulos el estado 'pendiente'
                $stmt_acc = $conexion->prepare("SELECT id FROM accesos_modulos WHERE usuario_id = ? AND numero_modulo = ?");
                $stmt_acc->bind_param("ii", $usuario_id, $nivel_solicitado);
                $stmt_acc->execute();
                $stmt_acc->store_result();
                
                if ($stmt_acc->num_rows > 0) {
                    $stmt_acc->close();
                    $stmt_upd = $conexion->prepare("UPDATE accesos_modulos SET estado = 'pendiente' WHERE usuario_id = ? AND numero_modulo = ?");
                    $stmt_upd->bind_param("ii", $usuario_id, $nivel_solicitado);
                    $stmt_upd->execute();
                    $stmt_upd->close();
                } else {
                    $stmt_acc->close();
                    $stmt_ins = $conexion->prepare("INSERT INTO accesos_modulos (usuario_id, numero_modulo, estado) VALUES (?, ?, 'pendiente')");
                    $stmt_ins->bind_param("ii", $usuario_id, $nivel_solicitado);
                    $stmt_ins->execute();
                    $stmt_ins->close();
                }
                
                // 3. Notificación a Telegram con tu ID exacto
                $bot_token = "8705847621:AAFwpWtjqL_aodD87jsEQMv3YrmBF3e6gAA"; 
                $chat_id = "1526051706"; 
                
                if (!empty($bot_token)) {
                    $stmt_user = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
                    $stmt_user->bind_param("i", $usuario_id);
                    $stmt_user->execute();
                    $res_user = $stmt_user->get_result()->fetch_assoc();
                    $nombre_usuario = $res_user['nombre'] ?? 'Estudiante';
                    $email_usuario = $res_user['email'] ?? 'N/A';
                    $stmt_user->close();
                    
                    $mensaje_telegram = "🔔 NUEVO PAGO REPORTADO\n\n"
                                      . "👤 Cliente: " . $nombre_usuario . " (" . $email_usuario . ")\n"
                                      . "🔒 Nivel solicitado: Módulo " . $nivel_solicitado . " (De pago)\n"
                                      . "💳 Método: " . $banco . "\n"
                                      . "💵 Monto: " . number_format($monto, 2) . "\n"
                                      . "🔢 Referencia: " . $referencia . "\n\n"
                                      . "⚠️ Entra a tu panel para verificar y autorizar.";
                    
                    $url_telegram = "https://api.telegram.org/bot" . $bot_token . "/sendMessage";
                    $data_telegram = [
                        'chat_id' => $chat_id,
                        'text' => $mensaje_telegram,
                        'parse_mode' => 'Markdown'
                    ];
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url_telegram);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_telegram);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($ch);
                    curl_close($ch);
                }
                
                $mensaje = "¡Reporte de pago enviado con éxito! Pendiente de validación.";
                $tipo_alerta = "success";
                
            } else {
                $mensaje = "Error al guardar el pago en la base de datos: " . $conexion->error;
                $tipo_alerta = "error";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pago - Módulo <?php echo $modulo; ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .container { background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); width: 100%; max-width: 500px; }
        h2 { margin-bottom: 20px; color: #2c3e50; text-align: center; font-size: 24px; }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 14px; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
        input[type="text"], input[type="number"], input[type="date"], .modulo-display { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; background-color: #fafafa; }
        .modulo-display { background-color: #e9ecef; color: #495057; font-weight: bold; }
        input:focus { border-color: #3498db; outline: none; background-color: #fff; }
        .btn-submit { background-color: #3498db; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.3s; }
        .btn-submit:hover { background-color: #2980b9; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
        .back-link:hover { color: #333; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reportar Pago</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipo_alerta; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <form action="pagos.php?modulo=<?php echo $modulo; ?>" method="POST">
            <input type="hidden" name="nivel_solicitado" value="<?php echo $modulo; ?>">
            <div class="form-group">
                <label>Módulo Secuencial a Desbloquear:</label>
                <div class="modulo-display">Módulo <?php echo $modulo; ?> (De Pago)</div>
            </div>
            <div class="form-group">
                <label for="metodo_pago">Banco / Método de Pago:</label>
                <input type="text" id="metodo_pago" name="metodo_pago" placeholder="Ej. Banco Mercantil, BNC..." required>
            </div>
            <div class="form-group">
                <label for="referencia_transaccion">Número de Referencia / Comprobante:</label>
                <input type="text" id="referencia_transaccion" name="referencia_transaccion" placeholder="Número de confirmación" required>
            </div>
            <div class="form-group">
                <label for="monto">Monto Pagado (Bs) :</label>
                <input type="number" step="0.01" id="monto" name="monto" placeholder="500.00" required>
            </div>
            <div class="form-group">
                <label for="fecha_pago">Fecha de la Transferencia / Pago:</label>
                <input type="date" id="fecha_pago" name="fecha_pago" required>
            </div>
            <button type="submit" class="btn-submit">Enviar Reporte de Pago</button>
        </form>
        <a href="dashboard.php" class="back-link">← Volver al Panel Principal</a>
    </div>
</body>
</html>