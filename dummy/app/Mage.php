<?php

/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2015-11-05
 * @company Linus Shops
 */
class Mage
{

}

/** AUTOLOADER PATCH **/
if (file_exists($autoloaderPath = BP . DS . '../vendor/autoload.php') ||
    file_exists($autoloaderPath = BP . DS . 'vendor/autoload.php')
) {
    require $autoloaderPath;
}
/** AUTOLOADER PATCH **/
