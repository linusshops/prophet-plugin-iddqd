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

namespace LinusShops\Prophet\Plugins;

use LinusShops\Prophet\Events;
use LinusShops\Prophet\Events\Options;
use LinusShops\Prophet\Plugin;
use LinusShops\Prophet\PluginRepository;
use PD;

class Iddqd implements Plugin
{
    public function load()
    {
        require __DIR__ . '/vendor/autoload.php';

        $dir = __DIR__;

        $loader = function ($classname) use ($dir) {
            $path = explode('_', $classname);
            $path = $dir
                . '/vendor/linusshops/iddqd/src/app/code/community/'
                . implode('/', $path)
                . '.php';
            if (file_exists($path)) {
                require ($path);
            }
        };

        spl_autoload_register($loader);
    }

    public function register()
    {
        PD::listen(Events::PROPHET_PREMAGENTO, function(Options $options){
            echo "Initializing GodMode...".PHP_EOL;
            $options->set('config_model', 'Linus_Iddqd_Model_Config');
        });
    }
}

return new Iddqd();
