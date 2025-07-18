<?php
spl_autoload_register(function ($class) {
    require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../app/utils/Utils.php";
    require_once __DIR__ . '/../app/utils/BlockUtils.php';

    $baseDir = realpath(__DIR__ . '/../app');

    // Mapeia pastas para autoload
    $folders = ['controllers', 'models', 'repositorios', 'views'];

    foreach ($folders as $folder) {
        $file = $baseDir . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // die("Erro: Classe '$class' não encontrada.");
});
?>
