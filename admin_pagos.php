<?php
session_start();
include 'conexion.php';

// 1. Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_actual_id = $_SESSION['usuario_id'];

// Verificar si es administrador (asumiendo que tienes una columna 'es_admin' o validas por ID/correo)
$stmt_rol = $conexion->prepare("SELECT es_admin FROM usuarios WHERE id = ?");
$stmt_rol->bind_param("i", $usuario_actual_id);
$stmt_rol->execute();
$res_rol = $stmt_rol->get_result()->fetch_assoc();
$stmt_rol->close();

if (!$res_rol || $res_rol['es_admin'] != 1) {
    header("Location: dashboard.php");
    exit();
}

$mensaje_alerta = "";
$tipo_alerta = "";

// 2. Procesar la aprobación del pago con un clic
if (isset($_GET['aprobar_id'])) {
    $pago_id = intval($_GET['aprobar_id']);

    // Buscar los datos del pago pendiente en tu tabla real 'pagos'
    $stmt_pago = $conexion->prepare("SELECT usuario_id,numero_modulo FROM pagos WHERE id = ? AND estado = 'pendiente'");
    $stmt_pago->bind_param("i", $pago_id);
    $stmt_pago->execute();
    $datos_pago = $stmt_pago->get_result()->fetch_assoc();
    $stmt_pago->close();

    if ($datos_pago) {
        $id_cliente = $datos_pago['usuario_id'];
        $num_modulo = $datos_pago['numero_modulo'];

        // A) Cambiar el estado del pago a 'aprobado' en la tabla 'pagos'
        $stmt_up = $conexion->prepare("UPDATE pagos SET estado = 'aprobado' WHERE id = ?");
        $stmt_up->bind_param("i", $pago_id);
        $stmt_up->execute();
        $stmt_up->close();

       // B) Dar de alta el acceso al módulo en la tabla 'accesos_modulos'
        $stmt_acc = $conexion->prepare("INSERT INTO accesos_modulos (usuario_id, numero_modulo, estado) VALUES (?, ?, 'aprobado') ON DUPLICATE KEY UPDATE estado = 'aprobado'");
        $stmt_acc->bind_param("ii", $id_cliente, $num_modulo);
        $stmt_acc->execute();
        $stmt_acc->close();

        $mensaje_alerta = "¡Pago aprobado con éxito! El módulo ha sido desbloqueado.";
        $tipo_alerta = "exito";
    } else {
        $mensaje_alerta = "El pago ya fue aprobado anteriormente o no existe.";
        $tipo_alerta = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/fondo.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0; padding: 0;
            display: flex; flex-direction: column; min-height: 100vh;
        }
        .admin-container {
            width: 100%; max-width: 1200px; margin: 30px auto;
            background: rgba(255, 255, 255, 0.95); padding: 30px;
            border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            border: 6px solid #1a365d; box-sizing: border-box;
        }
        h2 { color: #1a365d; text-transform: uppercase; margin-top: 0; text-align: center; }
        .tabla-pagos { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        .tabla-pagos th, .tabla-pagos td { border: 1px solid #cbd5e0; padding: 12px; text-align: left; }
        .tabla-pagos th { background-color: #1a365d; color: white; }
        .tabla-pagos tr:nth-child(even) { background-color: #f7fafc; }
        .btn-aprobar {
            background-color: #38a169; color: white; padding: 8px 12px;
            border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block;
        }
        .btn-aprobar:hover { background-color: #2f855a; }
        .alerta-exito { background: #c6f6d5; color: #22543d; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        .alerta-error { background: #fed7d7; color: #742a2a; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

    <header style="width: 100%; max-width: 1200px; margin: 20px auto 0 auto; background: rgba(255, 255, 255, 0.95); padding: 15px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center; box-sizing: border-box;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="img/logo.jpeg" alt="Logo" style="height: 40px; border-radius: 6px;">
            <span style="font-size: 16px; font-weight: bold; color: #1a365d;">Panel de Control Administrativo</span>
        </div>
        <div>
            <a href="dashboard.php" style="background-color: #319795; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold;">Volver al Dashboard</a>
        </div>
    </header>

    <main style="flex: 1; padding: 0 20px; box-sizing: border-box;">
        <div class="admin-container">
            <h2>Gestión de Pagos Pendientes</h2>
            <p style="text-align: center; color: #4a5568; font-size: 14px;">Verifica las referencias en tu banco y haz clic en aprobar para activar el acceso al usuario al instante.</p>

            <?php if (!empty($mensaje_alerta)): ?>
                <div class="<?php echo ($tipo_alerta === 'exito') ? 'alerta-exito' : 'alerta-error'; ?>">
                    <?php echo $mensaje_alerta; ?>
                </div>
            <?php endif; ?>

            <?php
            // Consulta directa utilizando tu tabla real 'pagos' y 'usuarios'
           $query = "SELECT p.id, p.usuario_id, p.numero_modulo, p.metodo_pago, p.monto, p.referencia_transaccion, p.fecha_pago, u.nombre, u.email FROM pagos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.estado = 'pendiente'";
                     
            $resultado = $conexion->query($query);
            ?>

            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <table class="tabla-pagos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                                <td><?php echo htmlspecialchars($row['monto']); ?></td>
                                <td><code style="background: #edf2f7; padding: 3px 6px; border-radius: 4px; font-weight: bold; color: #2d3748;"><?php echo htmlspecialchars($row['referencia_transaccion']); ?></code></td>
                                <td><?php echo htmlspecialchars($row['fecha_pago']); ?></td>
                                <td>
                                    <a href="admin_pagos.php?aprobar_id=<?php echo $row['id']; ?>" class="btn-aprobar" onclick="return confirm('¿Aprobar este pago y activar la suscripción del usuario?');">
                                        ✅ Aprobar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #718096; font-size: 16px;">
                    🎉 No hay pagos pendientes por verificar en este momento.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer style="width: 100%; max-width: 1200px; margin: 20px auto; background: #1a202c; color: #a0aec0; padding: 20px; border-radius: 12px; text-align: center; box-sizing: border-box;">
        <p style="color: #ffffff; font-weight: bold; margin: 0 0 5px 0; font-size: 14px;">Audiolibro English - Administración</p>
    </footer>

</body>
</html>