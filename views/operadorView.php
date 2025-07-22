<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario']['rol'] !== 'operador') {
    echo "Acceso denegado. Esta sección es solo para operadores.";
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
            <h1>Panel de operador</h1>
        </header>

        <div class="d-flex flex-grow-1">
            <?php include 'navbar.php'; ?>
        </div> 
    </div> 
</body>
</html>