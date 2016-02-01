<div class="productinfo">
	<h1>[+sb.title+]</h1>
	<div class="photos">
		[+sb.thumbs+]
		<div class="bigphoto">
			<img src="[+sb.image.x480.1+]" class="bgimg">
		</div>
	</div>
</div>
<div class="content">
	<div class="baseprice">
		[+sb.price+] руб
		<span>цена без опций</span>
	</div>
	<h2>Характеристики</h2>
	[+sb.attributes+]
	<form id="product_[+sb.id+]" method="post" action="[+sb.link_action+]">
		<input type="hidden" name="sb_order_add" value="[+sb.id+]">
		<input type="hidden" name="sbprod[quantity]" value="1">
		<input type="hidden" name="baseprice" value="[+sb.price+]" />
		[+sb.options+]
		<div class="summary">
			<input class="button_active" type="submit" name="sb_order_submit" value="Добавить к заказу" />
		</div>
	</form>
	[+sb.description+]
</div>
<!-- ### -->
<div class="thumbs">[+sb.wrapper+]</div>
<!-- ### -->
<div><img src="[+sb.image+]" class="thmb"></div>
<!-- ### -->
<ul class="attributes">[+sb.wrapper+]</ul>
<!-- ### -->
<li><span class="attr">[+sb.title+]</span> <span class="val">[+sb.value+]</span></li>
<!-- ### -->
<h2>Возможные опции</h2>
<div class="option">
	<div class="option_title">[+sb.option.title+]</div>
	<div class="option_values">
		<ul>
			[+sb.wrapper+]
		</ul>
	</div>
</div>
<!-- ### -->
<li>
	<input type="hidden" name="[+sb.id+]_sbprod[sboptions][[+sb.option.id+]]" value="[+sb.value+]" />
	<input type="checkbox" class="optval" name="sbprod[sboptions][[+sb.option.id+]]" value="[+sb.id+]"> [+sb.title+] <span class="option_price">[+sb.value+]</span>
</li>
<!-- ### -->
<li>
	<input type="hidden" name="[+sb.id+]_sbprod[sboptions][[+sb.option.id+]]" value="[+sb.value+]" />
	<input type="radio" class="optval" name="sbprod[sboptions][[+sb.option.id+]]" value="[+sb.id+]"> [+sb.title+] <span class="option_price">[+sb.value+]</span>
</li>