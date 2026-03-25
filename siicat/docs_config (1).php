<?php
// Configuración del módulo de documentos escaneados

// Incluir funciones del sistema
include_once "function.php";

// Configuración de archivos
define('DOCS_MAX_FILE_SIZE', 5 * 1024 * 1024); // 10MB en bytes
define('DOCS_BASE_PATH', 'C:/apache/htdocs/');

// Función para obtener extensiones permitidas (compatible con PHP 5.3)
function get_allowed_extensions() {
    return array('pdf', 'jpg', 'jpeg', 'png');
}

// Función para validar archivos
function validar_archivo_documento($archivo) {
    $errores = array();
    $extensiones_permitidas = get_allowed_extensions();
    
    // Debug: Verificar que el archivo llegue correctamente
    if (!is_array($archivo)) {
        $errores[] = "Error: Datos del archivo no válidos.";
        return $errores;
    }
    
    // Verificar si hay errores en la subida
    if (!isset($archivo['error']) || $archivo['error'] !== UPLOAD_ERR_OK) {
        $error_code = isset($archivo['error']) ? $archivo['error'] : 'desconocido';
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errores[] = "El archivo es demasiado grande.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errores[] = "El archivo se subió parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errores[] = "No se seleccionó ningún archivo.";
                break;
            default:
                $errores[] = "Error al subir el archivo (código: $error_code).";
        }
        return $errores;
    }
    
    // Verificar que el nombre del archivo existe
    if (!isset($archivo['name']) || empty($archivo['name'])) {
        $errores[] = "Nombre de archivo no válido.";
        return $errores;
    }
    
    // Validar tamaño
    if (!isset($archivo['size']) || $archivo['size'] <= 0) {
        $errores[] = "Tamaño de archivo no válido.";
    } elseif ($archivo['size'] > DOCS_MAX_FILE_SIZE) {
        $errores[] = "El archivo excede el tamaño máximo permitido de " . (DOCS_MAX_FILE_SIZE / 1024 / 1024) . "MB.";
    }
    
    // Validar extensión de manera más robusta
    $nombre_archivo = $archivo['name'];
    $extension = '';
    
    // Obtener extensión usando múltiples métodos
    if (function_exists('pathinfo')) {
        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    } else {
        // Método alternativo para PHP más antiguos
        $pos = strrpos($nombre_archivo, '.');
        if ($pos !== false) {
            $extension = strtolower(substr($nombre_archivo, $pos + 1));
        }
    }
    
    if (empty($extension)) {
        $errores[] = "No se pudo determinar la extensión del archivo.";
    } elseif (!in_array($extension, $extensiones_permitidas)) {
        $errores[] = "Tipo de archivo no permitido. Solo se permiten: " . strtoupper(implode(', ', $extensiones_permitidas)) . ". Detectado: " . strtoupper($extension);
    }
    
    // Validar nombre del archivo
    if (strlen($nombre_archivo) > 255) {
        $errores[] = "Nombre de archivo demasiado largo (máximo 255 caracteres).";
    }
    
    // Validar caracteres peligrosos en el nombre
    if (preg_match('/[<>:"|?*]/', $nombre_archivo)) {
        $errores[] = "El nombre del archivo contiene caracteres no permitidos.";
    }
    
    return $errores;
}

// Función para crear directorio de documentos
function crear_directorio_documentos($folder, $cod_dis, $id_inmu) {
    $directorio_base = DOCS_BASE_PATH . $folder . '/docs';
    $id_inmu_cero = str_pad($id_inmu, 6, "0", STR_PAD_LEFT); // Rellena a la izquierda hasta 6 dígitos
    $directorio_distrito = $directorio_base . '/' . $cod_dis;
    $directorio_predio = $directorio_distrito . '/' . $id_inmu_cero;
    try {
        if (!is_dir($directorio_base)) {
            if (!mkdir($directorio_base, 0755, true)) {
                return false;
            }
        }

        if (!is_dir($directorio_distrito)) {
            if (!mkdir($directorio_distrito, 0755, true)) {
                return false;
            }
        }

        if (!is_dir($directorio_predio)) {
            if (!mkdir($directorio_predio, 0755, true)) {
                return false;
            }
        }
        
        return $directorio_predio;
    } catch (Exception $e) {
        return false;
    }
}

// Función para generar nombre único de archivo
function generar_nombre_archivo($nombre_original) {
    $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
    $nombre_base = pathinfo($nombre_original, PATHINFO_FILENAME);
    
    // Limpiar el nombre base
    $nombre_base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombre_base);
    $nombre_base = substr($nombre_base, 0, 50); // Limitar longitud
    
    $timestamp = date('Y-m-d_H-i-s');
    $random = substr(md5(uniqid()), 0, 6);
    
    return $timestamp . '_' . $random . '_' . $nombre_base . '.' . $extension;
}

// Función para obtener MIME type seguro
function obtener_mime_type($archivo_path) {
    $extension = strtolower(pathinfo($archivo_path, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'pdf':
            return 'application/pdf';
        case 'jpg':
        case 'jpeg':
            return 'image/jpeg';
        case 'png':
            return 'image/png';
        default:
            return 'application/octet-stream';
    }
}

// Función para registrar actividad de documentos
function registrar_actividad_documento($dbconn, $id_inmu, $accion, $detalles = '') {
    $usuario = isset($_SESSION['username']) ? $_SESSION['username'] : 'sistema';
    $fecha = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $sql = "INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat, observaciones) 
            VALUES ('$usuario', '$ip', '$fecha', '$fecha', '$accion', 
                    (SELECT cod_cat FROM info_inmu WHERE id_inmu = $id_inmu LIMIT 1), 
                    '$detalles')";
    
    pg_query($dbconn, $sql);
}

?>