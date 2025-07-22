<?php //Modelo/SanitizarEntrada

// Clase con métodos estáticos para limpiar y validar datos de entrada
class SanitizarEntrada {
    
    // Limpia un string: elimina espacios extras y caracteres no permitidos

    public static function limpiarString($valor) {
        return trim(filter_var($valor, FILTER_SANITIZE_STRING));
    }

    // Limpia un valor para que sea un entero válido, eliminando caracteres no numéricos
    public static function limpiarInt($valor) {
        return filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
    }

    // Limpia un valor para que sea un número flotante válido, permitiendo decimales
    public static function limpiarFloat($valor) {
        return filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

     // Para cédula y para nombre/apellido: solo letras y espacios
     public static function validarDescripcion($valor) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $valor);
    }

    // Para usuario: alfanumérico sin espacios
    public static function validarUsuario($valor) {
        return preg_match('/^[a-zA-Z0-9]+$/', $valor);
    }

    // Para clave: mínimo 6 caracteres
    public static function validarClave($valor) {
        return strlen($valor) >= 6;
    }

//////////////////////////////////////////////////////////////////
    // Valida que la cédula contenga solo números y guiones
    public static function validarCedulaFormato($valor) {
        return preg_match('/^[0-9-]+$/', $valor);
    }

    // Valida que la longitud de la cédula esté entre 5 y 15 caracteres
    public static function validarCedulaLongitud($valor) {
        $len = strlen($valor);
        return $len >= 5 && $len <= 15;
    }

    // Valida que solo contenga letras (incluye tildes y ñ) y sin espacios ni otros caracteres
    public static function validarSoloLetrasYTildesSinEspacios($valor) {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/u', $valor);
    }

    // Valida que no contenga espacios en blanco en toda la cadena
    public static function validarSinEspacios($valor) {
        return strpos($valor, ' ') === false;
    }

    // Valida que la longitud esté entre 2 y 15 caracteres
    public static function validarLongitudEntre2y15($valor) {
        $len = mb_strlen($valor); // mb_strlen para contar caracteres unicode correctamente
        return $len >= 2 && $len <= 15;
    }

    // Valida que la longitud esté entre 5 y 10 caracteres
    public static function validarLongitudEntre5y10($valor) {
        $len = mb_strlen($valor);
        return $len >= 5 && $len <= 10;
    }

    // Valida que no contenga caracteres especiales (solo letras y números)
    public static function validarSinCaracteresEspeciales($valor) {
        return preg_match('/^[a-zA-Z0-9]+$/', $valor);
    }

    // Para correo electrónico válido
    public static function validarCorreo($valor) {
        return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Valida que la clave tenga al menos 2 números
    public static function validarClaveDosNumeros($valor) {
        preg_match_all('/\d/', $valor, $matches);
        return count($matches[0]) >= 2;
    }

    // Valida que la clave tenga al menos un caracter especial
    public static function validarClaveCaracterEspecial($valor) {
        return preg_match('/[\W_]/', $valor); // \W incluye cualquier no palabra, _ incluido
    }

    // Valida que la longitud de la clave esté entre 8 y 10 caracteres
    public static function validarClaveLongitud($valor) {
        $len = mb_strlen($valor);
        return $len >= 8 && $len <= 10;
    }

    public static function validarRol($valor) {
        return in_array($valor, ['operador', 'jugador']);
    }

    public static function validarEstado($valor) {
        return in_array($valor, ['activo', 'inactivo']);
    }
    
}

?>