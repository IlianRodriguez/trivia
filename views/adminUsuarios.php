<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario']['rol'] !== 'administrador') {
    echo "Acceso denegado. Esta sección es solo para administradores.";
    exit;
}
?>

<!DOCTYPE html> <!--- views/adminUusarios.php--->
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/adminBase.css" />
    <link rel="stylesheet" href="../css/adminNavbar.css" />
    <link rel="stylesheet" href="../css/registro.css" />
</head>
<body>
    <div class="contenedor d-flex flex-column min-vh-100">
        <header>
            <h1>Panel de administrador</h1>
        </header>

        <div class="d-flex flex-grow-1">
            <?php include 'navbar.php'; ?>

            <div class="flex-grow-1 p-3">
                <div class="mb-5 p-4 bg-white rounded shadow-sm">
                    <h2>Registrar Usuarios</h2>
                    <form action="" method="post" id="frmRegistrar">
                        <input type="hidden" name="Accion" id="Accion" value="registrar" />
                        <div class="form-group">
                            <label for="cedula">Cédula:</label>
                            <input type="text" name="cedula" id="cedula" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="usuario">Usuario:</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" name="correo" id="correo" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="clave">Clave:</label>
                            <input type="password" name="clave" id="clave" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="estadoSelect">Estado:</label>
                            <select id="estadoSelect" class="form-control" disabled>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <input type="hidden" name="estado" id="estadoHidden" value="activo">

                        <div class="form-group">
                            <label for="rol">Rol:</label>
                            <select name="rol" id="rol" class="form-control" required>
                                <option value="" disabled selected></option>
                                <option value="jugador">Jugador</option>
                                <option value="operador">Operador</option>
                            </select>
                        </div>
                        <div class="form-group d-flex" style="gap: 500px;">
                        <input type="reset" value="Cancelar" id="cancelar" class="btn btn-danger" />
                        <input type="button" value="Registrar" id="registrar" class="btn btn-success w-20" />
                        </div>
                    </form>
                </div>

                <div class="p-4 bg-white rounded shadow-sm">
                    <h2>Actualizar Usuarios</h2>
                    <form action="" method="post" class="mb-3">
                        <div class="form-group">
                            <label for="buscar">Buscar:</label>
                            <input type="text" name="buscar" id="buscar" placeholder="Buscar por cédula o rol..." class="form-control" />
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="tablaUsuarios" class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="resultado">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
    <script src="../js/registroScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</body>
</html>
