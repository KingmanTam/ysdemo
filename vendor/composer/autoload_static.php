<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited1ac91dbc94e13c1a27fad37254eab7
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInited1ac91dbc94e13c1a27fad37254eab7::$classMap;

        }, null, ClassLoader::class);
    }
}
