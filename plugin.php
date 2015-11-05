<?php
/**
 * Prophet plugin for GodMode
 *
 * Provides helpers to allow use of Iddqd in testing context to rewrite
 * classes and replace them with mocks.
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2015-11-05
 * @company Linus Shops
 */

require __DIR__.'/vendor/autoload.php';

$dir = __DIR__;

$loader = function($classname) use($dir) {
    $path = explode('_', $classname);
    include $dir
        .'/vendor/linusshops/iddqd/src/app/code/community/'
        .implode('/',$path)
        .'.php'
    ;
};

spl_autoload_register($loader);

$config = new Linus_Iddqd_Model_Config();
