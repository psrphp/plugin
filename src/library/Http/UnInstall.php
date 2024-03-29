<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Request\Request;
use PsrPHP\Framework\Framework;
use PsrPHP\Router\Router;

class UnInstall extends Common
{

    public function post(
        Router $router,
        Request $request
    ) {
        $name = $request->post('name');
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        $install_lock = $root . '/config/' . $name . '/install.lock';
        if (!file_exists($install_lock)) {
            return Response::error('未安装！');
        }

        $disabled_lock = $root . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return Response::error('请先停用！');
        }

        $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $name . '\\PsrPHP\Script', '/\\-'));
        $action = 'onUnInstall';
        if (method_exists($class_name, $action)) {
            Framework::execute([$class_name, $action]);
        }
        unlink($install_lock);

        return Response::redirect($router->build('/psrphp/plugin/index'));
    }
}
