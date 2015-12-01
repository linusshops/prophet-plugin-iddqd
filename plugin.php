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
    const EVENT_INJECT = 'IDDQD_EVENT_INJECT';
    private $config = array();

    public function load()
    {
        require __DIR__ . '/vendor/autoload.php';

        $dir = __DIR__;
        $this->config = json_decode(file_get_contents(__DIR__.'/config.json'),true);
        //Use the installed version of godmode, instead of the one bundled with the plugin.
        $useMagentoGodmode = isset($this->config['use_magento_godmode']) ?
            $this->config['use_magento_godmode'] : false;
        ;

        $loader = function ($classname) use ($dir, $useMagentoGodmode) {
            if ($useMagentoGodmode) {
                return;
            }
            $path = explode('_', $classname);
            require $dir
                . '/vendor/linusshops/iddqd/src/app/code/community/'
                . implode('/', $path)
                . '.php';
        };

        spl_autoload_register($loader);
    }

    public function register()
    {
        PD::listen(Events::PROPHET_PREMAGENTO, function(Options $options){
            $options->set('config_model', 'Linus_Iddqd_Model_Config');
        });

        Events::dispatch(self::EVENT_INJECT, $options = new Events\Options());
    }

    public function injectEvent($eventName, $className, $method)
    {
        $handle = md5($eventName.$className.$method);
        /** @var \Linus_Iddqd_Model_Config $config */
        $config = \Mage::getConfig();
        $merge = clone $config->getPrototype();
        $merge->loadString("
    <config>
    <global>
        <events>
            <{$eventName}>
                <observers>
                    <{$handle}>
                        <type>object</type>
                        <class>{$className}</class>
                        <method>{$method}</method>
                    </{$handle}>
                </observers>
            </{$eventName}>
        </events>
    </global>
    </config>
    ");
        $config->extend($merge);
    }
}

return new Iddqd();
