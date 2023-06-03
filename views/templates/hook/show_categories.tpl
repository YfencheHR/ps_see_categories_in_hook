{if $categories}
    <div class="row show-categories">
        {foreach from=$categories item=category}
            {if !empty($category.image)}
                <div class="col-xs-12 col-sm-4">
                    <a href="{$link->getCategoryLink($category.id_category)}">
                        <img src="{$category['image']}" alt="{$category['name']}" class="img-fluid" loading="lazy">
                    </a>
                </div>
            {/if}
        {/foreach}
    </div>
{/if}
