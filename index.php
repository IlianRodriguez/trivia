<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/adminBase.css" />
    <link rel="stylesheet" href="css/login.css" />

    <title>Quiz Game</title>
</head>
<body>
    <div class="contenedor-login">
        <h1>QUIZ GAME</h1>
        <div class="contenedor-form">
            <h3>Administrador</h3>
            <hr />
            <form action="/TAURUS7/controllers/loginCtrl.php" method="post">
            <div class="fila">
                    <label for="">Usuario</label>
                    <div class="entrada">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="usuario" required />
                    </div>
                </div>
                <div class="fila">
                    <label for="">Clave</label>
                    <div class="entrada">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="clave" required />
                    </div>
                </div>
                <hr />
                <input type="submit" name="login" value="Ingresar" class="btn" />
            </form>
        </div>
    </div>
</body>
</html>
