<?php
// Directorio de migraciones
$migrations_dir = __DIR__;

// Obtener todos los archivos PHP en el directorio de migraciones
$migration_files = glob($migrations_dir . '/*.php');

// Ordenar los archivos por nombre
sort($migration_files);

// Ejecutar cada migración
foreach ($migration_files as $migration_file) {
    if (basename($migration_file) === 'run_migrations.php') {
        continue; // Saltar este archivo
    }
    
    echo "Ejecutando migración: " . basename($migration_file) . "\n";
    require_once $migration_file;
    echo "Migración completada: " . basename($migration_file) . "\n\n";
}

echo "Todas las migraciones han sido ejecutadas.\n";
?> 