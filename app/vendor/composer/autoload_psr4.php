<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Slim\\Views\\' => array($vendorDir . '/slim/php-view/src'),
    'Slim\\' => array($vendorDir . '/slim/slim/Slim'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Psr\\Container\\' => array($vendorDir . '/psr/container/src'),
    'Model\\' => array($baseDir . '/src/model'),
    'Interop\\Container\\' => array($vendorDir . '/container-interop/container-interop/src/Interop/Container'),
    'FastRoute\\' => array($vendorDir . '/nikic/fast-route/src'),
    'Controller\\' => array($baseDir . '/src/controller'),
);
