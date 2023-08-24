<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Psrphp;

use App\Psrphp\Admin\Http\Plugin\Index;
use App\Psrphp\Admin\Model\MenuProvider;
use Psr\EventDispatcher\ListenerProviderInterface;
use PsrPHP\Framework\Framework;
use PsrPHP\Psr11\Container;
use PsrPHP\Framework\App;
use PsrPHP\Psr14\Event;

class ListenerProvider implements ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable
    {
        if (is_a($event, App::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    App $app,
                    Event $event,
                    Container $container,
                ) {
                    $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
                    spl_autoload_register(function (string $class) use ($root) {
                        $paths = explode('\\', $class);
                        if (isset($paths[3]) && $paths[0] == 'App' && $paths[1] == 'Plugin') {
                            $file = $root . '/plugin/'
                                . strtolower(preg_replace('/([A-Z])/', "-$1", lcfirst($paths[2])))
                                . '/src/library/'
                                . str_replace('\\', '/', substr($class, strlen($paths[0]) + strlen($paths[1]) + strlen($paths[2]) + 3))
                                . '.php';
                            if (file_exists($file)) {
                                include $file;
                            }
                        }
                    });

                    $dir = $root . '/plugin';
                    foreach (scandir($dir) as $vo) {
                        if (in_array($vo, array('.', '..'))) {
                            continue;
                        }
                        if (!is_dir($dir . DIRECTORY_SEPARATOR . $vo)) {
                            continue;
                        }
                        $appname = 'plugin/' . $vo;
                        if (file_exists($root . '/config/' . $appname . '/disabled.lock')) {
                            continue;
                        }
                        if (!file_exists($root . '/config/' . $appname . '/install.lock')) {
                            continue;
                        }
                        $app->set($appname, $root . '/' . $appname);

                        $cls = 'App\\' . str_replace(['-', '/'], ['', '\\'], ucwords($appname, '/-')) . '\\Psrphp\\ListenerProvider';
                        if (class_exists($cls) && is_subclass_of($cls, ListenerProviderInterface::class)) {
                            $event->addProvider($container->get($cls));
                        }
                    }
                }, [
                    App::class => $event,
                ]);
            };
        }

        if (is_a($event, MenuProvider::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    MenuProvider $provider
                ) {
                    $provider->add('插件管理', Index::class);
                }, [
                    MenuProvider::class => $event,
                ]);
            };
        }
    }
}
