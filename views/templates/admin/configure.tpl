<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> {$module_name}
    </div>
    <form action="{$module_link}" method="post" class="defaultForm form-horizontal">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-lg-3">
                    Categorías a mostrar
                </label>
                <div class="col-lg-9">
                    <select name="showcategories_categories[]" class="form-control" multiple="multiple">
                        {foreach $options as $option}
                        <option value="{$option.id}" {if $option.selected}selected="selected"{/if}>
                        {$option.name|escape:'htmlall':'UTF-8'}
                        </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="submit{$module_name}" class="btn btn-default pull-right">
                <i class="icon-save"></i> Guardar
            </button>
        </div>
    </form>
</div>