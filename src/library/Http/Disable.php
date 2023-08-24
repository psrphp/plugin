<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;

class Disable extends Common
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

        $disabled_file = $root . '/config/' . $name . '/disabled.lock';
        if ($request->post('disabled')) {
            if (!file_exists($disabled_file)) {
                if (!is_dir(dirname($disabled_file))) {
                    mkdir(dirname($disabled_file), 0755, true);
                }
                touch($disabled_file);
            }
        } else {
            if (file_exists($disabled_file)) {
                unlink($disabled_file);
            }
        }
        return Response::redirect($router->build('/psrphp/plugin/index'));
    }
}
