<?php

class Estudiante
{
    private string $codigo;
    private string $nombres;
    private string $apellidos;
    private string $email;
    private DateTime $fechaNacimiento;
    private string $genero;
    private int $posicion;  

    public function __construct(string $codigo, string $nombres, string $apellidos, string $email,
                                DateTime $fechaNacimiento, string $genero, int $posicion = 0)
    {
        $this->codigo = $codigo;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->genero = $genero;
        $this->posicion = $posicion;
    }

    public function getCodigo(): string { return $this->codigo; }
    public function getNombres(): string { return $this->nombres; }
    public function getApellidos(): string { return $this->apellidos; }
    public function getEmail(): string { return $this->email; }
    public function getFechaNacimiento(): DateTime { return $this->fechaNacimiento; }
    public function getGenero(): string { return $this->genero; }
    public function getPosicion(): int { return $this->posicion; }
    public function setPosicion(int $posicion): void { $this->posicion = $posicion; }
}
