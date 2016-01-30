<div>
	<p>Заказ пуст!</p>
</div>
<!-- ### -->
<div class="cart activ">
	<form id="sbcart" method="post" action="[+sb.link_action+]">
		[+sb.wrapper+]
		<div class="resume">
			Общая стоимость: <span class="price"><strong>[+sb.price+]</strong> руб</span>
		</div>
		<input class="button_neutral" type="submit" name="sb_cart_update" value="Пересчитать стоимость">
		<input class="button_active" type="submit" name="sb_order_next" value="Продолжить оформление" />
	</form>
</div>
<!-- ### -->
<div class="cartprod">
	<p>
		<span class="title"><a href="[+sb.url+]">[+sb.title+]</a></span>
		<span class="price">[+sb.price+] руб (x <input type="text" maxlength="2" style="width:15px; text-align:right;" name="sb_product_quantity[[+sb.set_id+]]" value="[+sb.quantity+]" />) / <input type="checkbox" name="sb_order_remove[]" value="[+sb.set_id+]" /> - удалить</span>
	</p>

	[+sb.options+]
</div>
<!-- ### -->
<p class="optlist">[+sb.wrapper+]</p>
<!-- ### -->
[+sb.title+]: [+sb.value+]<!-- ### -->, 