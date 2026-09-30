<?php
require_once 'Nodo.php';

class ListaDoble
{
    private ?Nodo $cabeza = null;
    private ?Nodo $cola = null;
    private int $tamanyo = 0;

    public function agregarInicio($dato): void
    {
        $nuevo = new Nodo($dato);
        if ($this->estaVacia()) {
            $this->cabeza = $nuevo;
            $this->cola = $nuevo;
        } else {
            $nuevo->siguiente = $this->cabeza;
            $this->cabeza->anterior = $nuevo;
            $this->cabeza = $nuevo;
        }
        $this->tamanyo++;
    }

    public function agregarFinal($dato): void
    {
        $nuevo = new Nodo($dato);
        if ($this->estaVacia()) {
            $this->cabeza = $nuevo;
            $this->cola = $nuevo;
        } else {
            $this->cola->siguiente = $nuevo;
            $nuevo->anterior = $this->cola;
            $this->cola = $nuevo;
        }
        $this->tamanyo++;
    }

    public function eliminarInicio()
    {
        if ($this->estaVacia()) {
            throw new Exception("La lista está vacía");
        }
        $dato = $this->cabeza->dato;
        if ($this->cabeza === $this->cola) { 
            $this->cabeza = null;
            $this->cola = null;
        } else {
            $this->cabeza = $this->cabeza->siguiente;
            $this->cabeza->anterior = null;
        }
        $this->tamanyo--;
        return $dato;
    }

    public function eliminarFinal()
    {
        if ($this->estaVacia()) {
            throw new Exception("La lista está vacía");
        }
        $dato = $this->cola->dato;
        if ($this->cabeza === $this->cola) {
            $this->cabeza = null;
            $this->cola = null;
        } else {
            $this->cola = $this->cola->anterior;
            $this->cola->siguiente = null;
        }
        $this->tamanyo--;
        return $dato;
    }

 
    public function eliminarPorCodigo(string $codigo)
    {
        $aux = $this->cabeza;
        while ($aux !== null) {
            if ($aux->dato->getCodigo() === $codigo) {
                if ($aux === $this->cabeza) {
                    return $this->eliminarInicio();
                }
                if ($aux === $this->cola) {
                    return $this->eliminarFinal();
                }

                $aux->anterior->siguiente = $aux->siguiente;
                $aux->siguiente->anterior = $aux->anterior;
                $this->tamanyo--;
                return $aux->dato;
            }
            $aux = $aux->siguiente;
        }
        return null;
    }

  
    public function mostrarAdelante(): array
    {
        $resultado = [];
        $aux = $this->cabeza;
        while ($aux !== null) {
            $resultado[] = $aux->dato;
            $aux = $aux->siguiente;
        }
        return $resultado;
    }


    public function mostrarAtras(): array
    {
        $resultado = [];
        $aux = $this->cola;
        while ($aux !== null) {
            $resultado[] = $aux->dato;
            $aux = $aux->anterior;
        }
        return $resultado;
    }

    public function primero() { return $this->cabeza?->dato; }
    public function ultimo() { return $this->cola?->dato; }

    public function tamanyo(): int
    {
        return $this->tamanyo;
    }

    public function estaVacia(): bool
    {
        return $this->cabeza === null;
    }
}
