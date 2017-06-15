{if $product_wis3->id_product == 0}
<div id="product-wis3" class="panel product-tab">
	<h3>{l s='WIS3 - Commentaire Produit'}</h3>
  <div><p>{l s='Enregistrez votre produit avant d\'accéder à cet onglet'}</p></div>
</div>
{else}
<div id="product-wis3" class="panel product-tab">
	<h3>{l s='WIS3 - Commentaire Produit'}</h3>
	<div class="form-group">
		<label class="control-label col-lg-3" for="comment">
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='Commentaire sur ce produit'}">{l s='Commentaire'}
			</span>
		</label>
		<div class="col-lg-1">
			<input type="text" name="comment" id="comment" value="{$product_wis3->comment|htmlentities}" />
		</div>
	</div>

	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}
      {if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}"
      class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}
    </a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled">
      <i class="process-icon-loading"></i> {l s='Save'}
    </button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled">
      <i class="process-icon-loading"></i> {l s='Save and stay'}
    </button>
	</div>

</div>
{/if}
