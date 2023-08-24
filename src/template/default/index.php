{include common/header@psrphp/admin}
<h1>插件管理</h1>

<div>
    <span>插件位于<code>/plugin</code>目录</span>
</div>

<div style="display: flex;flex-direction: column;gap: 20px;margin-top: 20px;">
    <fieldset>
        <legend>已启用</legend>
        <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 10px;">
            {foreach $plugins as $vo}
            {if $vo['install'] && !$vo['disabled']}
            <div>
                <div>
                    <img src="{echo $vo['logo']}" width="100" alt="">
                </div>
                <div>
                    <div><span>{$vo['title']??'-'}</span><sup>{$vo['version']??''}</sup></div>
                    <div>{$vo['description']??''}</div>
                    <div><code>{$vo.name}</code> </div>
                    <div style="display: flex;flex-wrap: column;gap: 5px;">
                        <form action="{echo $router->build('/psrphp/plugin/disable')}" method="POST">
                            <input type="hidden" name="name" value="{$vo.name}">
                            <input type="hidden" name="disabled" value="1">
                            <button type="submit">停用该插件</button>
                        </form>
                    </div>
                </div>
            </div>
            {/if}
            {/foreach}
        </div>
    </fieldset>

    <fieldset>
        <legend>未启用</legend>
        <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 10px;">
            {foreach $plugins as $vo}
            {if $vo['install'] && $vo['disabled']}
            <div>
                <div>
                    <img src="{echo $vo['logo']}" width="100" alt="">
                </div>
                <div>
                    <div><span>{$vo['title']??'-'}</span><sup>{$vo['version']??''}</sup></div>
                    <div>{$vo['description']??''}</div>
                    <div><code>{$vo.name}</code> </div>
                    <div style="display: flex;flex-wrap: column;gap: 5px;">
                        <form action="{echo $router->build('/psrphp/plugin/disable')}" method="POST">
                            <input type="hidden" name="name" value="{$vo.name}">
                            <input type="hidden" name="disabled" value="0">
                            <button type="submit">启用该插件</button>
                        </form>
                        <form action="{echo $router->build('/psrphp/plugin/un-install')}" method="POST">
                            <input type="hidden" name="name" value="{$vo.name}">
                            <button type="submit" onclick="return confirm('确定卸载该插件吗？卸载会删除数据')">卸载该插件</button>
                        </form>
                    </div>
                </div>
            </div>
            {/if}
            {/foreach}
        </div>
    </fieldset>

    <fieldset>
        <legend>未安装</legend>
        <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 10px;">
            {foreach $plugins as $vo}
            {if !$vo['install']}
            <div>
                <div>
                    <img src="{echo $vo['logo']}" width="100" alt="">
                </div>
                <div>
                    <div><span>{$vo['title']??'-'}</span><sup>{$vo['version']??''}</sup></div>
                    <div>{$vo['description']??''}</div>
                    <div><code>{$vo.name}</code> </div>
                    <div style="display: flex;flex-wrap: column;gap: 5px;">
                        <form action="{echo $router->build('/psrphp/plugin/install')}" method="POST">
                            <input type="hidden" name="name" value="{$vo.name}">
                            <button type="submit">安装该插件</button>
                        </form>
                        <form action="{echo $router->build('/psrphp/plugin/delete')}" method="POST">
                            <input type="hidden" name="name" value="{$vo.name}">
                            <button type="submit" onclick="return confirm('确定删除该插件吗？会彻底删除插件及相关文件');">删除该插件</button>
                        </form>
                    </div>
                </div>
            </div>
            {/if}
            {/foreach}
        </div>
    </fieldset>
</div>
{include common/footer@psrphp/admin}