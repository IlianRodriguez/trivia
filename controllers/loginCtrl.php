<?php // controllers/loginCtrl.php
session_start();

require_once '../config/baseDatosConfig.php';
require_once '../models/usuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioInput = $_POST['usuario'] ?? '';
    $claveInput = $_POST['clave'] ?? '';

    $db = new DB();
    $usuarioModel = new Usuario($db);

    $userData = $usuarioModel->buscarPorUsuario($usuarioInput);

    if ($userData) {
        if (password_verify($claveInput, $userData['clave'])) {
            // Guardar datos en sesión
            $_SESSION['usuario'] = [
                'usuario' => $userData['usuario'],
                'rol' => $userData['rol'],
                'nombre' => $userData['nombre'],
            ];

            // Redirigir según rol con casos separados
            switch ($userData['rol']) {
                case 'administrador':
                    header('Location: /TAURUS7/views/adminUsuarios.php');
                    exit;
                case 'jugador':
                    header('Location: /TAURUS7/views/jugadorView.php');
                    exit;
                case 'operador':
                    header('Location: /TAURUS7/views/operadorView.php');
                    exit;
                default:
                    // Rol no reconocido, redirigir con error
                    header('Location: /TAURUS7/index.php?error=rol');
                    exit;
            }
        } else {
            header('Location: /TAURUS7/index.php?error=clave');
            exit;
        }
    } else {
        header('Location: /TAURUS7/index.php?error=usuario');
        exit;
    }
} else {
    header('Location: /TAURUS7/index.php');
    exit;
}
?>