<?php
header('Content-Type: application/json');

require_once '../config/baseDatosConfig.php';
require_once '../models/usuarioModel.php';
require_once '../models/SanitizarEntrada.php';

$pdo = new DB();
$usuario = new Usuario($pdo);

// Inicializar respuesta por defecto
$response = ['success' => false, 'message' => 'Acción no válida'];

// Obtener acción desde POST o GET
$accion = $_POST['Accion'] ?? $_GET['Accion'] ?? '';

switch ($accion) {
    case 'registrar':
        $data = $_POST;

        // Asignar y limpiar datos
        $usuario->RecibirDatos($data);
        $usuario->RegistrarDatos();

        // Validar campos requeridos
        $usuario->setRequiredFields(['cedula', 'nombre', 'apellido', 'usuario', 'correo', 'clave', 'rol']);
        $usuario->validate();

        if (!empty($usuario->Errores)) {
            echo json_encode([
                'success' => false,
                'message' => 'Errores de validación',
                'errors'  => $usuario->Errores
            ]);
            exit;
        }

        // Hashear clave y registrar usuario
        $usuario->hashearClave();

        if ($usuario->registrarUsuario()) {
            $response = [
                'success' => true,
                'message' => 'Usuario registrado correctamente'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error al registrar usuario en la base de datos'
            ];
        }
        break;

        case 'buscar':
            $busqueda = $_GET['valor'] ?? ''; // viene por GET
        
            $resultados = $usuario->buscarUsuarioMultiplesCampos($busqueda);
        
            if ($resultados !== false) {
                $response = [
                    'success' => true,
                    'message' => 'Búsqueda exitosa',
                    'data'    => $resultados
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Error al realizar la búsqueda'
                ];
            }
            break;

            case 'actualizar':
                $data = $_POST;
            
                $usuario->RecibirDatos($data);
                $usuario->RegistrarDatos();
            
                // Validar campos obligatorios (sin clave, si no quieres que sea obligatorio actualizarla)
                $usuario->setRequiredFields(['cedula', 'nombre', 'apellido', 'usuario', 'correo', 'rol', 'estado']);
            
                if (!empty($usuario->Errores)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Errores de validación',
                        'errors'  => $usuario->Errores
                    ]);
                    exit;
                }
            
                // Si clave viene y no está vacía, hashearla
                if (!empty($usuario->clave)) {
                    $usuario->hashearClave();
                } else {
                    // Opción: cargar clave actual de BD para no perderla
                    // O simplemente quitar 'clave' del array $data antes de actualizar en Usuario.php
                }
            
                if ($usuario->actualizarUsuario()) {
                    $response = [
                        'success' => true,
                        'message' => 'Usuario actualizado correctamente'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Error al actualizar usuario'
                    ];
                }
                break;
            
}

echo json_encode($response);
