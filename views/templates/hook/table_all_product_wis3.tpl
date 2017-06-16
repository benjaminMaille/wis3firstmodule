<div class="emplacement">
    {foreach $same_products as $product}
      <a href="{$product.link}">
        <div class="prod">
        {$product.name}
        <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default' )}" alt="">
        </div>
      </a>
    {/foreach}
</div>
