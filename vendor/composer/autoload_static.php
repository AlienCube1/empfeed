<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf226a25ed87f27a75471f009956cc22d
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EmpFeed\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EmpFeed\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf226a25ed87f27a75471f009956cc22d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf226a25ed87f27a75471f009956cc22d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf226a25ed87f27a75471f009956cc22d::$classMap;

        }, null, ClassLoader::class);
    }
}
