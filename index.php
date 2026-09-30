<?php
require_once 'config/conexion.php';
require_once 'clases/ListaDoble.php';
require_once 'clases/EstudianteDAO.php';

session_start();

try {
    $dao = new EstudianteDAO(conectar());
} catch (PDOException $e) {
    die("<h2>No se pudo conectar a MySQL.</h2><p>Revisa que MySQL esté encendido en XAMPP.</p><p>" . $e->getMessage() . "</p>");
}

$lista = new ListaDoble();
foreach ($dao->listar() as $est) {
    $lista->agregarFinal($est);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    try {
        if ($accion === 'agregarInicio' || $accion === 'agregarFinal') {
            $codigo    = trim($_POST['codigo'] ?? '');
            $nombres   = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $fecha     = DateTime::createFromFormat('Y-m-d', $_POST['fechaNacimiento'] ?? '');
            $genero    = strtoupper(trim($_POST['genero'] ?? ''));

            if ($codigo === '' || $nombres === '' || $apellidos === '') {
                throw new Exception("El código, nombres y apellidos son obligatorios");
            }
            if ($dao->existe($codigo)) {
                throw new Exception("Ya existe un estudiante con el código $codigo");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El email no es válido");
            }
            if ($fecha === false) {
                throw new Exception("La fecha de nacimiento no es válida");
            }
            if ($genero !== 'M' && $genero !== 'F') {
                throw new Exception("El género debe ser M o F");
            }

            $est = new Estudiante($codigo, $nombres, $apellidos, $email, $fecha, $genero);

            if ($accion === 'agregarInicio') {
                
                $est->setPosicion($lista->estaVacia() ? 0 : $lista->primero()->getPosicion() - 1);
                $lista->agregarInicio($est);
                $_SESSION['mensaje'] = ['ok', "Se agregó al inicio a $nombres $apellidos"];
            } else {
                
                $est->setPosicion($lista->estaVacia() ? 0 : $lista->ultimo()->getPosicion() + 1);
                $lista->agregarFinal($est);
                $_SESSION['mensaje'] = ['ok', "Se agregó al final a $nombres $apellidos"];
            }
            $dao->guardar($est);

        } elseif ($accion === 'eliminarInicio') {
            $est = $lista->eliminarInicio();
            $dao->eliminar($est->getCodigo());
            $_SESSION['mensaje'] = ['ok', "Se eliminó a " . $est->getNombres() . " " . $est->getApellidos()];

        } elseif ($accion === 'eliminarFinal') {
            $est = $lista->eliminarFinal();
            $dao->eliminar($est->getCodigo());
            $_SESSION['mensaje'] = ['ok', "Se eliminó a " . $est->getNombres() . " " . $est->getApellidos()];

        } elseif ($accion === 'eliminarCodigo') {
            $codigo = trim($_POST['codigoEliminar'] ?? '');
            $est = $lista->eliminarPorCodigo($codigo);
            if ($est === null) {
                throw new Exception("No existe un estudiante con el código $codigo");
            }
            $dao->eliminar($codigo);
            $_SESSION['mensaje'] = ['ok', "Se eliminó a " . $est->getNombres() . " " . $est->getApellidos()];
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = ['error', $e->getMessage()];
    }

    
    header('Location: index.php?sentido=' . urlencode($_POST['sentido'] ?? 'adelante'));
    exit;
}


$sentido = ($_GET['sentido'] ?? 'adelante') === 'atras' ? 'atras' : 'adelante';
$datos = $sentido === 'atras' ? $lista->mostrarAtras() : $lista->mostrarAdelante();
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

function e($texto) { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista Doble de Estudiantes - PHP</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Lista Doblemente Enlazada de Estudiantes</h1>
    <p class="sub">PHP + MySQL (XAMPP) - Estructura de Datos</p>

    <?php if ($mensaje): ?>
        <div class="mensaje <?= $mensaje[0] ?>"><?= e($mensaje[1]) ?></div>
    <?php endif; ?>

    <div class="caja">
        <h3>Agregar estudiante</h3>
        <form method="post" action="index.php">
            <input type="hidden" name="sentido" value="<?= $sentido ?>">
            <div class="fila">
                <div class="campo"><label>Código</label><input name="codigo" placeholder="SIS-001" required></div>
                <div class="campo"><label>Nombres</label><input name="nombres" required></div>
                <div class="campo"><label>Apellidos</label><input name="apellidos" required></div>
            </div>
            <div class="fila">
                <div class="campo"><label>Email</label><input name="email" type="email" required></div>
                <div class="campo"><label>Fecha de nacimiento</label><input name="fechaNacimiento" type="date" required></div>
                <div class="campo"><label>Género</label>
                    <select name="genero">
                        <option value="M">M - Masculino</option>
                        <option value="F">F - Femenino</option>
                    </select>
                </div>
            </div>
            <div class="botones">
                <button type="submit" name="accion" value="agregarInicio" class="verde">Agregar al inicio</button>
                <button type="submit" name="accion" value="agregarFinal" class="verde">Agregar al final</button>
            </div>
        </form>
    </div>

    <div class="caja">
        <h3>Eliminar</h3>
        <div class="botones">
            <form method="post" action="index.php">
                <input type="hidden" name="sentido" value="<?= $sentido ?>">
                <button type="submit" name="accion" value="eliminarInicio" class="rojo">Eliminar inicio</button>
                <button type="submit" name="accion" value="eliminarFinal" class="rojo">Eliminar final</button>
            </form>
            <form method="post" action="index.php" class="en-linea">
                <input type="hidden" name="sentido" value="<?= $sentido ?>">
                <input type="hidden" name="accion" value="eliminarCodigo">
                <input name="codigoEliminar" placeholder="Código a eliminar" required>
                <button type="submit" class="rojo">Eliminar por código</button>
            </form>
        </div>
    </div>

    <div class="caja">
        <h3>Contenido de la lista</h3>
        <div class="botones">
            <a class="boton azul" href="index.php?sentido=adelante">Recorrer adelante (cabeza → cola)</a>
            <a class="boton gris" href="index.php?sentido=atras">Recorrer atrás (cola → cabeza)</a>
            <span>Tamaño: <b><?= $lista->tamanyo() ?></b></span>
        </div>
        <p class="recorrido">Recorrido: <b><?= $sentido === 'atras' ? 'hacia atrás' : 'hacia adelante' ?></b></p>

        <div class="nodos">
            <span class="flecha">null ⇄</span>
            <?php foreach ($datos as $i => $est): ?>
                <div class="nodo"><?= e($est->getCodigo()) ?><br><?= e($est->getNombres()) ?></div>
                <span class="flecha"><?= $i < count($datos) - 1 ? '⇄' : '⇄ null' ?></span>
            <?php endforeach; ?>
        </div>

        <table>
            <thead>
            <tr><th>#</th><th>Código</th><th>Nombres</th><th>Apellidos</th><th>Email</th><th>F. Nacimiento</th><th>Género</th></tr>
            </thead>
            <tbody>
            <?php if ($lista->estaVacia()): ?>
                <tr><td colspan="7" class="vacia">La lista está vacía</td></tr>
            <?php endif; ?>
            <?php foreach ($datos as $i => $est): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($est->getCodigo()) ?></td>
                    <td><?= e($est->getNombres()) ?></td>
                    <td><?= e($est->getApellidos()) ?></td>
                    <td><?= e($est->getEmail()) ?></td>
                    <td><?= $est->getFechaNacimiento()->format('d/m/Y') ?></td>
                    <td><?= e($est->getGenero()) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
