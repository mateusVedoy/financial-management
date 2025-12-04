<?php

/**
 * Autoloader simples para o projeto
 * 
 * Registra um autoloader que mapeia namespaces para diretórios.
 */
spl_autoload_register(function ($className) {
    // Remove o namespace prefixo se houver
    $prefix = '';
    $baseDir = __DIR__ . '/';
    
    // Mapeia namespaces para diretórios
    $prefixMap = [
        'Domain\\' => $baseDir . 'domain/',
        'Infrastructure\\' => $baseDir . 'infrastructure/',
        'UseCase\\' => $baseDir . 'usecase/',
        'Interface\\' => $baseDir . 'interface/',
        'Shared\\' => $baseDir . 'shared/',
    ];
    
    foreach ($prefixMap as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $className, $len) !== 0) {
            continue;
        }
        
        $relativeClass = substr($className, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

