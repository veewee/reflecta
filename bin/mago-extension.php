<?php
declare(strict_types=1);

use Mago\Sdk\Extension;
use Mago\Sdk\Worker;
use VeeWee\Reflecta\Mago\Plugin;

foreach ([__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php'] as $autoloader) {
    if (file_exists($autoloader)) {
        require_once $autoloader;

        break;
    }
}

(new Worker(new Extension(
    'veewee/reflecta',
    'Reflecta',
    '1.0.0',
    analyzerPlugins: [new Plugin()],
)))->run();
