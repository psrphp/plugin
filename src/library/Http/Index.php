<?php

declare(strict_types=1);

namespace App\Psrphp\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use Composer\InstalledVersions;
use PsrPHP\Template\Template;
use ReflectionClass;

class Index extends Common
{
    public function get(
        Template $template
    ) {

        $plugins = [];

        $root = dirname(dirname(dirname((new ReflectionClass(InstalledVersions::class))->getFileName())));

        foreach (glob($root . '/plugin/*/src/library/App.php') as $file) {

            $app = substr($file, strlen($root . '/'), -strlen('/src/library/App.php'));

            $json_file = $root . '/' . $app . '/config.json';
            $json = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

            $logo = file_exists($root . '/' . $app . '/logo.svg') ? ('data:image/svg+xml;base64,' . base64_encode(file_get_contents($root . '/' . $app . '/logo.svg'))) : ('data:image/svg+xml;base64,' . base64_encode('<svg class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="14741"><path d="M902.4 486.4h-73.6V291.2c0-54.4-44.8-96-96-96h-195.2V121.6C537.6 54.4 483.2 0 416 0S294.4 54.4 294.4 121.6v73.6H96c-54.4 0-96 44.8-96 96v185.6h73.6c73.6 0 131.2 57.6 134.4 128 0 73.6-57.6 131.2-128 134.4H0V928c0 54.4 44.8 96 96 96h185.6v-73.6c0-73.6 57.6-131.2 131.2-131.2s131.2 57.6 131.2 131.2V1024h185.6c54.4 0 96-44.8 96-96v-195.2h73.6c67.2 0 121.6-54.4 121.6-121.6 3.2-70.4-51.2-124.8-118.4-124.8z" fill="#ddd" p-id="14742"></path></svg>'));

            $plugins[] = [
                'name' => $app,
                'version' => $json['version'] ?? '',
                'title' => $json['title'] ?? '',
                'description' => $json['description'] ?? '',
                'logo' => $logo,
                'install' => file_exists($root . '/config/' . $app . '/install.lock'),
                'disabled' => file_exists($root . '/config/' . $app . '/disabled.lock')
            ];
        }

        return $template->renderFromFile('index@psrphp/plugin', [
            'plugins' => $plugins,
        ]);
    }
}
