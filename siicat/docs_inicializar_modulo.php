<?php
// Este script crea las tablas necesarias para el módulo de gestión de documentos.
// Se debe ejecutar una sola vez.

echo "<h1>Inicialización del Módulo de Documentos</h1>";

// Se asume que este script es llamado desde un punto de entrada que ya ha establecido la conexión a la BD ($dbconn).
// Por lo tanto, se eliminan las inclusiones a los archivos de configuración obsoletos.

if (!isset($dbconn)) {
    die("<p style='color:red;'>Error: La variable de conexión a la base de datos (\$dbconn) no está disponible. Asegúrese de que este script se incluya después de la conexión.</p>");
}

// --- 1. Crear la tabla tipos_documento ---
$sql_tipos_documento = "
CREATE TABLE tipos_documento (
    id_tipo_documento SERIAL PRIMARY KEY,
    nombre_documento VARCHAR(150) NOT NULL UNIQUE,
    descripcion TEXT
);";

// Verificar si la tabla ya existe
$check_table = pg_query($dbconn, "SELECT to_regclass('public.tipos_documento')");
if (pg_fetch_result($check_table, 0, 0)) {
    echo "<p style='color:orange;'>AVISO: La tabla 'tipos_documento' ya existe. No se realizarán cambios en su estructura.</p>";
} else {
    $result = pg_query($dbconn, $sql_tipos_documento);
    if ($result) {
        echo "<p style='color:green;'>ÉXITO: Tabla 'tipos_documento' creada correctamente.</p>";
    } else {
        die("<p style='color:red;'>ERROR al crear la tabla 'tipos_documento': " . pg_last_error($dbconn) . "</p>");
    }
}

// --- 2. Insertar los tipos de documento iniciales ---
$tipos_iniciales = array(
    'Alodial DDRR',
    'Planos Aprobados',
    'Carnet de Identidad',
    'Aviso de Luz',
    'Aviso de Agua'
);

$sql_insert_tipos = "INSERT INTO tipos_documento (nombre_documento) VALUES ";
foreach ($tipos_iniciales as $tipo) {
    // Verificar si el tipo ya existe para no duplicarlo
    $check_tipo = pg_query($dbconn, "SELECT 1 FROM tipos_documento WHERE nombre_documento = '" . pg_escape_string($tipo) . "'");
    if (pg_num_rows($check_tipo) == 0) {
        $result = pg_query($dbconn, "INSERT INTO tipos_documento (nombre_documento) VALUES ('" . pg_escape_string($tipo) . "')");
        if ($result) {
            echo "<p style='color:green;'>ÉXITO: Tipo de documento '" . htmlspecialchars($tipo) . "' insertado.</p>";
        } else {
            echo "<p style='color:red;'>ERROR al insertar tipo de documento '" . htmlspecialchars($tipo) . "': " . pg_last_error($dbconn) . "</p>";
        }
    } else {
        echo "<p style='color:orange;'>AVISO: El tipo de documento '" . htmlspecialchars($tipo) . "' ya existe.</p>";
    }
}


// --- 3. Crear la tabla documentos_escaneados ---
// NOTA: Usamos id_inmu como referencia principal ya que es la clave que se usa en el sistema
$sql_documentos_escaneados = "
CREATE TABLE documentos_escaneados (
    id_documento SERIAL PRIMARY KEY,
    id_inmu INTEGER NOT NULL,
    id_tipo_documento INTEGER NOT NULL,
    fecha_presentacion DATE NOT NULL DEFAULT CURRENT_DATE,
    nombre_archivo_original VARCHAR(255) NOT NULL,
    ruta_archivo_guardado VARCHAR(512) NOT NULL,
    observaciones TEXT,
    usuario_carga VARCHAR(50),
    fecha_carga TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tipo_documento
        FOREIGN KEY(id_tipo_documento)
        REFERENCES tipos_documento(id_tipo_documento)
);";

// Verificar si la tabla ya existe
$check_table = pg_query($dbconn, "SELECT to_regclass('public.documentos_escaneados')");
if (pg_fetch_result($check_table, 0, 0)) {
    echo "<p style='color:orange;'>AVISO: La tabla 'documentos_escaneados' ya existe. No se realizarán cambios.</p>";
} else {
    $result = pg_query($dbconn, $sql_documentos_escaneados);
    if ($result) {
        echo "<p style='color:green;'>ÉXITO: Tabla 'documentos_escaneados' creada correctamente.</p>";
    } else {
        die("<p style='color:red;'>ERROR al crear la tabla 'documentos_escaneados': " . pg_last_error($dbconn) . "</p>");
    }
}

echo "<h2>Proceso de inicialización finalizado.</h2>";
echo "<p>Puede cerrar esta ventana. Es recomendable eliminar este archivo del servidor por seguridad.</p>";

?>