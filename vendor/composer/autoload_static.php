<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit99f627d15bc8cb3bfd1ea1203f36dc0b
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit99f627d15bc8cb3bfd1ea1203f36dc0b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit99f627d15bc8cb3bfd1ea1203f36dc0b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit99f627d15bc8cb3bfd1ea1203f36dc0b::$classMap;

        }, null, ClassLoader::class);
    }
}
