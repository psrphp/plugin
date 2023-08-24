<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Dir;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Request\Request;

class Delete extends Common
{
    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        $root = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
        $install_lock = $root . '/config/' . $name . '/install.lock';
        if (file_exists($install_lock)) {
            return Response::error('请先卸载！');
        }
        $disabled_lock = $root . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return Response::error('请先停用！');
        }
        Dir::del($root . '/' . $name);
        Dir::del($root . '/config/' . $name);
        return Response::success('操作成功！');
    }
}
