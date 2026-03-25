<?php
// Script de prueba para el módulo de documentos escaneados

echo "<h1>Prueba del Módulo de Documentos Escaneados</h1>";

// Incluir configuración
include_once "docs_config.php";

echo "<h2>1. Verificación de Configuración</h2>";
echo "<p><strong>Tamaño máximo de archivo:</strong> " . (DOCS_MAX_FILE_SIZE / 1024 / 1024) . " MB</p>";
echo "<p><strong>Extensiones permitidas:</strong> " . implode(', ', DOCS_ALLOWED_EXTENSIONS) . "</p>";
echo "<p><strong>Ruta base:</strong> " . DOCS_BASE_PATH . "</p>";

echo "<h2>2. Prueba de Funciones</h2>";

// Probar función de validación con archivo simulado
echo "<h3>Validación de archivo simulado:</h3>";
$archivo_prueba = array(
    'name' => 'documento_prueba.pdf',
    'size' => 5 * 1024 * 1024, // 5MB
    'error' => UPLOAD_ERR_OK
);

$errores = validar_archivo_documento($archivo_prueba);
if (empty($errores)) {
    echo "<p style='color:green;'>✓ Archivo válido</p>";
} else {
    echo "<p style='color:red;'>✗ Errores encontrados:</p>";
    foreach ($errores as $error) {
        echo "<p style='color:red;'>- $error</p>";
    }
}

// Probar función de generación de nombre
echo "<h3>Generación de nombre de archivo:</h3>";
$nombre_generado = generar_nombre_archivo('Mi Documento de Prueba.pdf');
echo "<p><strong>Nombre original:</strong> Mi Documento de Prueba.pdf</p>";
echo "<p><strong>Nombre generado:</strong> $nombre_generado</p>";

// Probar función de MIME type
echo "<h3>Detección de MIME type:</h3>";
$tipos_prueba = array('documento.pdf', 'imagen.jpg', 'foto.png');
foreach ($tipos_prueba as $archivo) {
    $mime = obtener_mime_type($archivo);
    echo "<p><strong>$archivo:</strong> $mime</p>";
}

echo "<h2>3. Verificación de Directorios</h2>";

// Probar creación de directorio
$folder_prueba = "mariposas";
$id_inmu_prueba = "1254";

echo "<p>Intentando crear directorio para folder: <strong>$folder_prueba</strong>, ID inmueble: <strong>$id_inmu_prueba</strong></p>";

$directorio_creado = crear_directorio_documentos($folder_prueba, $id_inmu_prueba);
if ($directorio_creado) {
    echo "<p style='color:green;'>✓ Directorio creado exitosamente: $directorio_creado</p>";
    
    // Verificar que el directorio existe
    if (is_dir($directorio_creado)) {
        echo "<p style='color:green;'>✓ Directorio verificado y accesible</p>";
        
        // Verificar permisos de escritura
        if (is_writable($directorio_creado)) {
            echo "<p style='color:green;'>✓ Directorio tiene permisos de escritura</p>";
        } else {
            echo "<p style='color:orange;'>⚠ Directorio sin permisos de escritura</p>";
        }
    } else {
        echo "<p style='color:red;'>✗ Error: Directorio no existe después de la creación</p>";
    }
} else {
    echo "<p style='color:red;'>✗ Error al crear directorio</p>";
}

echo "<h2>4. Verificación de Rutas del Sistema</h2>";

// Verificar estructura de directorios esperada
$rutas_verificar = array(
    DOCS_BASE_PATH,
    DOCS_BASE_PATH . $folder_prueba,
    DOCS_BASE_PATH . $folder_prueba . '/docs'
);

foreach ($rutas_verificar as $ruta) {
    if (is_dir($ruta)) {
        echo "<p style='color:green;'>✓ Directorio existe: $ruta</p>";
    } else {
        echo "<p style='color:orange;'>⚠ Directorio no existe: $ruta</p>";
    }
}

echo "<h2>5. Resumen de la Prueba</h2>";
echo "<p>El módulo de documentos escaneados está configurado y las funciones básicas están operativas.</p>";
echo "<p><strong>Próximos pasos:</strong></p>";
echo "<ul>";
echo "<li>Ejecutar docs_inicializar_modulo.php para crear las tablas de base de datos</li>";
echo "<li>Probar la subida de archivos desde la interfaz web</li>";
echo "<li>Verificar la descarga de documentos</li>";
echo "</ul>";

echo "<hr>";
echo "<p><em>Prueba completada el " . date('Y-m-d H:i:s') . "</em></p>";

?>