<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit576d93a4eb38ff7fca36dd300a725afc
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Medoo\\' => 6,
        ),
        'K' => 
        array (
            'Klein\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
        'Klein\\' => 
        array (
            0 => __DIR__ . '/..' . '/klein/klein/src/Klein',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit576d93a4eb38ff7fca36dd300a725afc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit576d93a4eb38ff7fca36dd300a725afc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
