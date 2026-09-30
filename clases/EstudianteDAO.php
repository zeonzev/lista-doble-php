<?php
require_once 'Estudiante.php';

class EstudianteDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(): array
    {
        $sql = "SELECT * FROM estudiantes ORDER BY posicion";
        $filas = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $estudiantes = [];
        foreach ($filas as $f) {
            $estudiantes[] = new Estudiante(
                $f['codigo'], $f['nombres'], $f['apellidos'], $f['email'],
                new DateTime($f['fecha_nacimiento']), $f['genero'], (int) $f['posicion']
            );
        }
        return $estudiantes;
    }

    public function guardar(Estudiante $e): void
    {
        $sql = "INSERT INTO estudiantes (codigo, nombres, apellidos, email, fecha_nacimiento, genero, posicion)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $e->getCodigo(), $e->getNombres(), $e->getApellidos(), $e->getEmail(),
            $e->getFechaNacimiento()->format('Y-m-d'), $e->getGenero(), $e->getPosicion()
        ]);
    }

    public function eliminar(string $codigo): void
    {
        $this->pdo->prepare("DELETE FROM estudiantes WHERE codigo = ?")->execute([$codigo]);
    }

    public function existe(string $codigo): bool
    {
        $consulta = $this->pdo->prepare("SELECT COUNT(*) FROM estudiantes WHERE codigo = ?");
        $consulta->execute([$codigo]);
        return $consulta->fetchColumn() > 0;
    }
}
