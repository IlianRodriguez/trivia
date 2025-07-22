<?php // models/usuarioModel.php

class Usuario { 

    private $cedula;
    private $nombre;
    private $apellido;
    private $usuario; // apodo corto
    private $correo;  // login
    private $clave;   // registro/clave
    private $rol;     //operador o jugador //ANA DICE QUE ASI NO ES. DICE QUE NO PERO SI LO DIJO.
    private $estado;  // activo (default) o inactivo
    private $hash;    // clave hasheada
    private $puntos;  // 0 (default)
    private $avatar;  // foto seleccionada en el sistema
    private $pdo;     // instancia bd
    private $tabla;   // tabla bd

    public function __construct($pdo) { 
        $this->pdo = $pdo; // conexion a la bd
        $this->tabla = "usuario"; // tabla bd de la clase
    }

    // registrar un nuevo usuario en la bd
    public function registrarUsuario(){
        $data = array(
            "cedula"   => $this->cedula,
            "nombre"   => $this->nombre,
            "apellido" => $this->apellido,
            "usuario"  => $this->usuario,
            "correo"   => $this->correo,
            "clave"    => $this->clave,
            "estado"   => $this->estado,
            "rol"      => $this->rol
        );
        return $this->pdo->insertSeguro($this->tabla, $data);
    }

    // Actualiza un usuario en la bd
    public function actualizarUsuario() {
        $data = array(
            "cedula"   => $this->cedula,
            "nombre"   => $this->nombre,
            "apellido" => $this->apellido,
            "usuario"  => $this->usuario,
            "correo"   => $this->correo,
            "clave"    => $this->clave,
            "rol"      => $this->rol,
            "estado"   => $this->estado,
            "avatar"   => $this->avatar,
        );

        $condiciones = array("cedula" => $this->cedula);
        return $this->pdo->updateSeguro($this->tabla, $data, $condiciones);
    }

     public function setRequiredFields(array $fields) {
        $this->requiredFields = $fields;
    }

    public function cedulaExiste($cedula) {
        $result = $this->pdo->selectSeguro(
            $this->tabla,
            ['id'],        
            'cedula',
            $cedula,
            true           
        );

        return !empty($result); 
    }

    public function correoExiste($correo) {
        $result = $this->pdo->selectSeguro(
            $this->tabla,
            ['id'],
            'correo',
            $correo,
            true
        );
        return !empty($result);
    }

    public function claveExiste($clave) {
        $result = $this->pdo->selectSeguro(
            $this->tabla,
            ['id'],
            'clave',
            $clave,
            true
        );
        return !empty($result);
    }

    public function usuarioExiste($usuario) {
        $result = $this->pdo->selectSeguro(
            $this->tabla,
            ['id'],
            'usuario',
            $usuario,
            true
        );
        return !empty($result);
    }
    
    public function validate() {
        $this->Errores = [];
    
        foreach ($this->requiredFields as $campo) {
            $valor = $this->$campo;
    
            // Verifica que el campo no esté vacío
            if (empty($valor)) {
                $this->Errores[$campo] = "El campo $campo es obligatorio.";
                continue;
            }
    
            switch ($campo) {
                case 'cedula':
                    if (!SanitizarEntrada::validarCedulaFormato($valor)) {
                        $this->Errores[$campo] = "La cédula solo puede contener números y guiones.";
                    } elseif (!SanitizarEntrada::validarCedulaLongitud($valor)) {
                        $this->Errores[$campo] = "La cédula debe tener entre 5 y 15 caracteres.";
                    } elseif ($this->cedulaExiste($valor)) {
                        $this->Errores[$campo] = "La cédula ya está registrada.";
                    }
                    break;
                    case 'nombre':
                        if (!SanitizarEntrada::validarSoloLetrasYTildesSinEspacios($valor)) {
                            $this->Errores[$campo] = "El nombre solo debe contener letras y tildes.";
                        } elseif (!SanitizarEntrada::validarSinEspacios($valor)) {
                            $this->Errores[$campo] = "El nombre no debe contener espacios.";
                        } elseif (!SanitizarEntrada::validarLongitudEntre2y15($valor)) {
                            $this->Errores[$campo] = "El nombre debe tener entre 2 y 15 caracteres.";
                        }
                        break;
                
                    case 'apellido':
                        if (!SanitizarEntrada::validarSoloLetrasYTildesSinEspacios($valor)) {
                            $this->Errores[$campo] = "El apellido solo debe contener letras y tildes.";
                        } elseif (!SanitizarEntrada::validarSinEspacios($valor)) {
                            $this->Errores[$campo] = "El apellido no debe contener espacios.";
                        } elseif (!SanitizarEntrada::validarLongitudEntre2y15($valor)) {
                            $this->Errores[$campo] = "El apellido debe tener entre 2 y 15 caracteres.";
                        }
                        break;
    
                case 'usuario':
                    case 'usuario':
                        if (!SanitizarEntrada::validarLongitudEntre5y10($valor)) {
                            $this->Errores[$campo] = "El usuario debe tener entre 5 y 10 caracteres.";
                        } elseif (!SanitizarEntrada::validarSinCaracteresEspeciales($valor)) {
                            $this->Errores[$campo] = "El usuario no puede contener caracteres especiales.";
                        } elseif (!SanitizarEntrada::validarSinEspacios($valor)) {
                            $this->Errores[$campo] = "El usuario no puede contener espacios.";
                        } elseif ($this->usuarioExiste($valor)) {
                            $this->Errores[$campo] = "El usuario ya está registrado.";
                        }
                        break;
                    
    
                case 'correo':
                    if (!SanitizarEntrada::validarCorreo($valor)) {
                        $this->Errores[$campo] = "El correo electrónico no es válido.";
                    } elseif ($this->correoExiste($valor)) {
                        $this->Errores[$campo] = "El correo ya está registrado.";
                    }                
                    break;
    
                case 'clave':
                    if (!SanitizarEntrada::validarClaveLongitud($valor)) {
                        $this->Errores[$campo] = "La clave debe tener entre 8 y 10 caracteres.";
                    } elseif (!SanitizarEntrada::validarClaveDosNumeros($valor)) {
                        $this->Errores[$campo] = "La clave debe contener al menos 2 números.";
                    } elseif (!SanitizarEntrada::validarClaveCaracterEspecial($valor)) {
                        $this->Errores[$campo] = "La clave debe contener al menos un caracter especial.";
                    } elseif ($this->claveExiste($valor)) {
                        $this->Errores[$campo] = "La clave ya está registrada.";
                    } elseif (!SanitizarEntrada::validarSinEspacios($valor)) {
                        $this->Errores[$campo] = "El usuario no puede contener espacios.";
                    }
                    
                    break;
    
                case 'rol':
                    if (!SanitizarEntrada::validarRol($valor)) {
                        $this->Errores[$campo] = "El rol debe ser 'operador' o 'jugador'.";
                    }
                    break;
    
                case 'estado':
                    if (!SanitizarEntrada::validarEstado($valor)) {
                        $this->Errores[$campo] = "El estado debe ser 'activo' o 'inactivo'.";
                    }
                    break;
            }
        }
    }
    

// Recibe datos en forma de arreglo y los asigna a las propiedades
public function RecibirDatos($data) {
    $this->cedula   = $data['cedula']   ?? '';
    $this->nombre   = $data['nombre']   ?? '';
    $this->apellido = $data['apellido'] ?? '';
    $this->usuario  = $data['usuario']  ?? '';
    $this->correo   = $data['correo']   ?? '';
    $this->clave    = $data['clave']    ?? '';
    $this->rol      = $data['rol']      ?? '';
    $this->estado   = $data['estado']   ?? '';
}


// Normaliza los datos recibidos a tipos adecuados para almacenar
public function RegistrarDatos() {
    $this->cedula   = trim($this->cedula);
    $this->nombre   = ucwords(strtolower(trim($this->nombre)));
    $this->apellido = ucwords(strtolower(trim($this->apellido)));
    $this->usuario  = strtolower(trim($this->usuario));
    $this->correo   = strtolower(trim($this->correo));
    $this->clave    = trim($this->clave); // Aquí podrías hashearla si es necesario
    $this->rol      = strtolower(trim($this->rol));
    $this->estado   = strtolower(trim($this->estado));
}

public function hashearClave() {
    $this->clave = password_hash($this->clave, PASSWORD_DEFAULT);
}

// Busca productos usando varios campos para la búsqueda con OR
public function buscarUsuarioMultiplesCampos($busqueda = "") {
     $campos = ['cedula', 'nombre', 'apellido', 'usuario', 'correo', 'clave', 'rol', 'estado']; 
    return $this->pdo->selectSeguroMultiple($this->tabla, ['cedula', 'nombre', 'apellido', 'usuario', 'correo', 'clave', 'rol', 'estado'], $campos, $busqueda);
}

public function buscarPorUsuario($usuario) {
    $pdo = $this->pdo->getConexion();
    $sql = "SELECT * FROM usuario WHERE usuario = :usuario LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


} 

?>