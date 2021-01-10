<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7060bcef5bc9e611d40ddf90a660ccc8
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Engine\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Engine\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application/core/engine',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7060bcef5bc9e611d40ddf90a660ccc8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7060bcef5bc9e611d40ddf90a660ccc8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}