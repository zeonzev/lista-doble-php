Estudiante: Jonathan Emmanuel Ticona Perez

Lista Doblemente Enlazada de Estudiantes (PHP + MySQL)

Aplicación web que gestiona estudiantes usando lista doblemente enlazada. Los datos se guardan en mysql(xampp)

LISTA DOBLEMENTE ENLAZADA

Cada nodo guarda un estudiante y tiene dos enlaces: uno al nodo "anterior" y otro al "siguiente". La lista guarda la "cabeza" (primer nodo) y la "cola" (último nodo), así que se puede recorrer en los dos sentidos.


Método | Descripción 

agregarInicio | Agrega un nodo antes de la cabeza
agregarFinal | Agrega un nodo después de la cola
eliminarInicio | Quita la cabeza
eliminarFinal | Quita la cola
eliminarPorCodigo(codigo) | Busca el nodo y lo desconecta de sus vecinos
mostrarAdelante | Recorre de la cabeza a la cola
mostrarAtras | Recorre de la cola a la cabeza
tamaño | Cantidad de nodos

Estudiante

codigo, nombres, apellidos, email, fechaNacimiento (fecha) y genero (M/F).

Cómo ejecutar

En mi PC usé el puerto 3307; con XAMPP normal, cambiar DB_PUERTO a '3306' en config/conexion.php (solo si no quiere abrir en el navegador)

Abrir en el navegador: `http://localhost/lista-doble-php/`

La base de datos `estructura_db` y la tabla `estudiantes` se crean solas al abrir la página. También está el script `database.sql` por si se quiere crear a mano desde phpMyAdmin.

Cómo usar

- Llenar el formulario y elegir *Agregar al inicio* o *Agregar al final*.
- *Eliminar inicio*, *Eliminar final* o *Eliminar por código*.
- *Recorrer adelante* o *Recorrer atrás* para ver la lista en los dos sentidos.
- Los datos guardados se pueden ver en `http://localhost/phpmyadmin` → `estructura_db` → `estudiantes`.
