<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Dir;
use App\Psrphp\Admin\Lib\Response;
use Composer\InstalledVersions;
use PsrPHP\Request\Request;
use ReflectionClass;

class Delete extends Common
{
    public function post(
        Request $request,
        Dir $dir
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return Response::error('系统应用不支持该操作！');
        }
        $root = dirname(dirname(dirname((new ReflectionClass(InstalledVersions::class))->getFileName())));
        $install_lock = $root . '/config/' . $name . '/install.lock';
        if (file_exists($install_lock)) {
            return Response::error('请先卸载！');
        }
        $disabled_lock = $root . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return Response::error('请先停用！');
        }
        $dir->del($root . '/' . $name);
        $dir->del($root . '/config/' . $name);
        return Response::success('操作成功！');
    }
}
