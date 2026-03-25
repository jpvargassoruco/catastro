<?php
// Script seguro para descargar/visualizar documentos

// Solo ejecutar si se llama directamente o con el módulo correcto
if (isset($_GET['doc_id']) && is_numeric($_GET['doc_id'])) {
    
    // Incluir configuración del sistema si no está incluida
    if (!isset($dbconn)) {
        include_once "igm_include.php";
    }
    
    // Incluir configuración del módulo de documentos
    include_once "docs_config.php";

    // Verificar que tenemos conexión a la base de datos
    if (!isset($dbconn)) {
        die("Error: No se pudo establecer la conexión con la base de datos.");
    }

    $id_documento = intval($_GET['doc_id']);

    // Buscar la información del documento en la base de datos
    $sql = "SELECT ruta_archivo_guardado, nombre_archivo_original, id_inmu FROM documentos_escaneados WHERE id_documento = $id_documento";
    $result = pg_query($dbconn, $sql);

    if (!$result || pg_num_rows($result) == 0) {
        die("Error: Documento no encontrado en la base de datos.");
    }

    $documento = pg_fetch_assoc($result);
    $ruta_archivo = $documento['ruta_archivo_guardado'];
    $nombre_original = $documento['nombre_archivo_original'];
    $id_inmu = $documento['id_inmu'];

    // Verificar que el archivo exista en el servidor
    if (!file_exists($ruta_archivo)) {
        die("Error: El archivo no se encuentra en el servidor.<br>Ruta: " . htmlspecialchars($ruta_archivo));
    }

    // Validar que la ruta del archivo esté dentro del directorio permitido
    $ruta_real = realpath($ruta_archivo);
    if (!$ruta_real || strpos($ruta_real, realpath(DOCS_BASE_PATH)) !== 0) {
        die("Error: Acceso no autorizado al archivo.");
    }

    // Determinar el tipo de contenido (MIME type) usando función de configuración
    $mime_type = obtener_mime_type($ruta_archivo);

    // Registrar el acceso al documento
    registrar_actividad_documento($dbconn, $id_inmu, "Documento visualizado", "Archivo: $nombre_original");

    // Limpiar cualquier salida previa
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Servir el archivo al navegador
    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: inline; filename="' . basename($nombre_original) . '"');
    header('Content-Length: ' . filesize($ruta_archivo));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    // Leer y enviar el archivo
    readfile($ruta_archivo);
    exit;
    
} else {
    die("Error: Parámetros no válidos para visualizar el documento.");
}
?>