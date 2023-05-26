{include common/header@psrphp/admin}
<script>
    function change(name, disabled) {
        $.ajax({
            type: "POST",
            url: "{echo $router->build('/psrphp/plugin/disable')}",
            data: {
                name: name,
                disabled: disabled
            },
            dataType: "JSON",
            success: function(response) {
                if (response.errcode) {
                    alert(response.message);
                } else {
                    location.reload();
                }
            },
            error: function() {
                alert('发生错误~');
            }
        });
    }

    function del(name) {
        if (confirm('确定彻底删除该插件吗？删除后无法恢复！')) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/psrphp/plugin/delete')}",
                data: {
                    name: name
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.errcode) {
                        alert(response.message);
                    } else {
                        location.reload();
                    }
                },
                error: function() {
                    alert('发生错误~');
                }
            });
        }
    }

    function install(name) {
        if (confirm('确定安装该插件吗？')) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/psrphp/plugin/install')}",
                data: {
                    name: name
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.errcode) {
                        alert(response.message);
                    } else {
                        location.reload();
                    }
                },
                error: function() {
                    alert('发生错误~');
                }
            });
        }
    }

    function uninstall(name) {
        if (confirm('确定卸载该插件吗？')) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/psrphp/plugin/un-install')}",
                data: {
                    name: name
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.errcode) {
                        alert(response.message);
                    } else {
                        location.reload();
                    }
                },
                error: function() {
                    alert('发生错误~');
                }
            });
        }
    }
</script>
<div class="container">
    <div class="my-4">
        <div class="h1">插件管理</div>
        <div class="text-muted fw-light">
            <span>插件位于<code>/plugin</code>目录</span>
            <span>，开发者请阅读：<a href="https://github.com/psrphp" target="_blank" class="mx-1 fw-bold">[https://github.com/psrphp]</a>.</span>
        </div>
    </div>
    <div class="d-flex flex-column gap-4">
        {foreach $plugins as $plugin}
        <div class="d-flex gap-3">
            <div>
                <img src="{echo $plugin['logo']}" width="100" alt="">
            </div>
            <div class="d-flex flex-column gap-2 flex-grow-1 bg-light p-3">
                <div><span class="fs-6 fw-bold">{$plugin['title']?:'-'}</span><sup class="ms-1 text-secondary">{$plugin['version']??''}</sup></div>
                <div>{$plugin.description}</div>
                <div><code>{$plugin.name}</code> </div>
                <div class="d-flex gap-2">
                    {if $plugin['install']}
                    {if $plugin['disabled']}
                    <button class="btn btn-sm btn-warning" type="button" onclick="change('{$plugin.name}', 0);" data-bs-toggle="tooltip" data-bs-placement="right" title="插件已停用，点此切换">已停用</button>
                    <button class="btn btn-sm btn-warning" type="button" onclick="uninstall('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="点此卸载此插件">卸载</button>
                    {else}
                    <button class="btn btn-sm btn-primary" type="button" onclick="change('{$plugin.name}', 1);" data-bs-toggle="tooltip" data-bs-placement="right" title="插件已启用，点此切换">已启用</button>
                    {/if}
                    {else}
                    <button class="btn btn-sm btn-primary" type="button" onclick="install('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="该插件未安装，点此安装">安装</button>
                    <button class="btn btn-sm btn-warning" type="button" onclick="del('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="彻底删除该插件">删除</button>
                    {/if}
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>
{include common/footer@psrphp/admin}