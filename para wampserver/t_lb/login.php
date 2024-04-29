<?php
session_start();

require_once 'Connections/db.php'; // Asegúrate de incluir el archivo de conexión adecuado

if (isset($_POST["usuario"]) && isset($_POST["password"])) {
    $usuario = strtolower(htmlspecialchars($_POST["usuario"]));
    $password = $_POST["password"];

    $query = "SELECT DISTINCT U.USUAID IDUSUARIO,
                    P.PERSCEDU ID_PERSONA,
                    U.USUACONT PASS,
                    U.USUAESTA ESTADO,
                    P.PERSNOMB PERSONA,
                    R.ROLENOMB ROL,
                    R.ROLEID IDROL
            --   FROM PERSONAS P
              INNER JOIN ROLES R ON U.USUAIDRO = R.ROLEID
              INNER JOIN USUARIOS U ON U.USUAIDPE = P.PERSCONS
              WHERE U.USUANOMB = :usuario";

    $statement = $db->prepare($query);
    $statement->bindParam(':usuario', $usuario);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row["ESTADO"] != 1 || isset($_SESSION["IDUSU"]) == $row['IDUSUARIO'] || isset($_SESSION["IDUSU"]) == '') {
            if ($row["PASS"] == $password) {
                if (isset($_SESSION["MODULO"]) == $_POST['modulo'] || isset($_SESSION["IDUSU"]) == $row['IDUSUARIO']) {

                    $query_RsActualizarModulo = "UPDATE modulos SET MODUESTA = '0' WHERE MODUID = :modulo";
                    $statement_RsActualizarModulo = $db->prepare($query_RsActualizarModulo);
                    $statement_RsActualizarModulo->bindParam(':modulo', $_POST['modulo']);
                    $statement_RsActualizarModulo->execute();

                    $query_RsActualizarUsuario = "UPDATE usuarios SET USUAESTA = '0' WHERE USUAID = :id_usuario";
                    $statement_RsActualizarUsuario = $db->prepare($query_RsActualizarUsuario);
                    $statement_RsActualizarUsuario->bindParam(':id_usuario', $row['IDUSUARIO']);
                    $statement_RsActualizarUsuario->execute();

                    echo '<script>alert("Intente Nuevamente, su cuenta se cerró inesperadamente o el módulo ya está en servicio.");';
                    echo 'location.href = "logout.php";</script>';
                } else {
                    $_SESSION["USU_AUTENTICADO"] = $row['ID_PERSONA'];
                    $_SESSION["USU_AUT_NOMB"] = $row['PERSONA'];
                    $_SESSION["AUTENTICADO"] = 'SI';
                    $_SESSION["ROLNOM"] = $row['ROL'];
                    $_SESSION["ROL"] = $row['IDROL'];
                    $_SESSION["IDUSU"] = $row['IDUSUARIO'];
                    $_SESSION["MODULO"] = $_POST['modulo'];
                    $_SESSION["TABLE"] = 'turnosfull';
                    $_SESSION["TABLE2"] = 'turnosfull';

                    $query_RsNombreModulo = "SELECT MODUNOMB, MODUSALA  FROM modulos WHERE MODUID = :modulo";
                    $statement_RsNombreModulo = $db->prepare($query_RsNombreModulo);
                    $statement_RsNombreModulo->bindParam(':modulo', $_POST['modulo']);
                    $statement_RsNombreModulo->execute();
                    $row_RsNombreModulo = $statement_RsNombreModulo->fetch(PDO::FETCH_ASSOC);

                    $_SESSION["NOMBRE_MODULO"] = $row_RsNombreModulo['MODUNOMB'];
                    $_SESSION["SALA"] = $row_RsNombreModulo['MODUSALA'];

                    $query_RsActualizarModulo = "UPDATE modulos SET MODUESTA = '1', MODUUSUA = :id_usuario WHERE MODUID = :modulo";
                    $statement_RsActualizarModulo = $db->prepare($query_RsActualizarModulo);
                    $statement_RsActualizarModulo->bindParam(':id_usuario', $row['IDUSUARIO']);
                    $statement_RsActualizarModulo->bindParam(':modulo', $_POST['modulo']);
                    $statement_RsActualizarModulo->execute();

                    $query_RsActualizarUsuario = "UPDATE usuarios SET USUAESTA = '1' WHERE USUAID = :id_usuario";
                    $statement_RsActualizarUsuario = $db->prepare($query_RsActualizarUsuario);
                    $statement_RsActualizarUsuario->bindParam(':id_usuario', $row['IDUSUARIO']);
                    $statement_RsActualizarUsuario->execute();

                    echo '<script>location.href = "sevicio.php";</script>';
                }
            } else {
                echo '<script>alert("Contraseña incorrecta.");';
                echo 'location.href = "index.php";</script>';
            }
        } else {
            echo '<script>alert("Su cuenta de usuario ya está en uso.");';
            echo 'location.href = "index.php";</script>';
        }
    } else {
        echo '<script>alert("Usuario no existente.");';
        echo 'location.href = "index.php";</script>';
    }
} else {
    echo '<script>alert("Debe especificar un usuario y contraseña.");';
    echo 'location.href = "index.php";</script>';
}
?>
