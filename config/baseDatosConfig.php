<?php //config/baseDatosConfig.php

class DB {

	private $conexion;           
	private $debug = false;       

	public function __construct() {
		$sql_host = "localhost";
		$sql_name = "trivia";
		$sql_user = "root";
		$sql_pass = "";
		$charset = 'utf8mb4';

		$dsn = "mysql:host=$sql_host;dbname=$sql_name;charset=utf8mb4";
		try {
			$this->conexion = new PDO($dsn, $sql_user, $sql_pass);
			$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if ($this->debug) {
				echo "Conexión exitosa a la base de datos<br>";
			}
		} catch (PDOException $e) {
			echo "Error de conexión: " . $e->getMessage();
			exit;
		}
	}

    public function insertSeguro($tb_name, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $tb_name ($columns) VALUES ($placeholders)";
        try { 
            $stmt = $this->conexion->prepare($sql);
            foreach ($data as $key => $value) { 
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error en INSERT: " . $e->getMessage();
            return false;
        }
    }

    public function selectSeguroMultiple($tabla, $columnas = ['*'], $camposBusqueda = [], $valor = "") {
        $cols = implode(", ", $columnas); // convertir un arreglo en una cadena de texto
        $sql = "SELECT $cols FROM $tabla";

        // Arma condiciones WHERE con varios campos usando OR
        if (!empty($camposBusqueda) && !empty($valor)) {
            $whereParts = [];
            foreach ($camposBusqueda as $campo) {
                $whereParts[] = "$campo LIKE :valor";
            }
            $sql .= " WHERE " . implode(" OR ", $whereParts);
        }
        // Orden descendente por ID
        $sql .= " ORDER BY id DESC";
    
        try {
            $stmt = $this->conexion->prepare($sql);
            if (!empty($camposBusqueda) && !empty($valor)) {// Vincula el valor si hay búsqueda
                $stmt->bindValue(":valor", "%$valor%");
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en BUSQUEDA múltiple: " . $e->getMessage();
            return false;
        }
        
    }

    public function updateSeguro($tabla, $data, $condiciones) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setSQL = implode(", ", $set);
        $where = [];
        foreach ($condiciones as $key => $value) {
            $where[] = "$key = :cond_$key";
        }
        $whereSQL = implode(" AND ", $where);
        $sql = "UPDATE $tabla SET $setSQL WHERE $whereSQL";
        try {
            $stmt = $this->conexion->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            foreach ($condiciones as $key => $value) {
                $stmt->bindValue(":cond_$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en UPDATE: " . $e->getMessage();
            return false;
        }
    }

    public function selectSeguro($tabla, $columnas = ['*'], $campo = "", $valor = "", $exacto = false) {
        $cols = implode(", ", $columnas);
        $sql = "SELECT $cols FROM $tabla";
    
        if (!empty($campo) && !empty($valor)) {
            $sql .= $exacto ? " WHERE $campo = :valor" : " WHERE $campo LIKE :valor";
            $sql .= " ORDER BY id DESC";
        } else {
            $sql .= " ORDER BY id DESC";
        }
    
        try {
            $stmt = $this->conexion->prepare($sql);
            if (!empty($campo) && !empty($valor)) {
                $stmt->bindValue(":valor", $exacto ? $valor : "%$valor%");
            }
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en BUSQUEDA: " . $e->getMessage();
            return false;
        }
    }

    public function getConexion() {
        return $this->conexion;
    }
    
}
