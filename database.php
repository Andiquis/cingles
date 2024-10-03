<?php
// database.php
function getDatabaseConnection() {
    $db = new PDO('sqlite:vocabulario.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creación de tabla si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS palabras (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        palabra_ingles TEXT,
        palabra_espanol TEXT
    )");

    return $db;
}
?>
