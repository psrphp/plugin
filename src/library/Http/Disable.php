<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use Composer\InstalledVersions;
use PsrPHP\Request\Request;
use ReflectionClass;

class Disable extends Common
{

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return Response::error('系统应用不支持该操作！');
        }
        $root = dirname(dirname(dirname((new ReflectionClass(InstalledVersions::class))->getFileName())));
        if (!InstalledVersions::isInstalled($name)) {
            $install_lock = $root . '/config/' . $name . '/install.lock';
            if (!file_exists($install_lock)) {
                return Response::error('未安装！');
            }
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
        return Response::success('操作成功！');
    }
}
