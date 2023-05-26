<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use Composer\InstalledVersions;
use PsrPHP\Request\Request;
use PsrPHP\Framework\Framework;
use ReflectionClass;

class UnInstall extends Common
{

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return Response::error('系统应用不支持该操作！');
        }

        $root = dirname(dirname(dirname((new ReflectionClass(InstalledVersions::class))->getFileName())));

        $install_lock = $root . '/config/' . $name . '/install.lock';
        if (!file_exists($install_lock)) {
            return Response::error('未安装！');
        }

        $disabled_lock = $root . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return Response::error('请先停用！');
        }

        $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $name . '\\PsrPHP\Script', '/\\-'));
        $action = 'onUninstall';
        if (method_exists($class_name, $action)) {
            Framework::execute([$class_name, $action]);
        }

        unlink($install_lock);

        return Response::success('操作成功！');
    }
}
