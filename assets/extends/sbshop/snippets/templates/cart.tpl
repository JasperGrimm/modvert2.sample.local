<div class="cart">
	Заказ пуст
</div>
<!-- ### -->
<div class="cart activ">
	[+sb.wrapper+]
	<div class="resume">
		Общая стоимость: <span class="price"><strong>[+sb.price+]</strong> руб</span>
	</div>
	<div class="actions">
		<form id="sbcart" method="post" action="[+sb.link_action+]">
			<input class="button_neutral" type="submit" name="sb_order_clear" value="Очистить" />
			<input class="button_active" type="submit" name="sb_order_next" value="Оформить заказ">
		</form>
	</div>
</div>
<!-- ### -->
[+sb.wrapper+]
<!-- ### -->
<div class="cartprod">
	<p>
		<form method="post" action="[+sb.link_action+]">
		<span class="title"><a href="[+sb.url+]">[+sb.title+]</a></span>
		<span class="price">
			[+sb.price+] руб (x[+sb.quantity+]) /
			<input type="hidden" name="sb_order_remove[]" value="[+sb.set_id+]" />
			<input type="image"  class="imgdel" src="[(site_url)]assets/templates/sbshop/images/del.gif" size="16,16" />
		</span>
		</form>
	</p>
	[+sb.options+]
</div>
<!-- ### -->
<p class="optlist">[+sb.wrapper+]</p>
<!-- ### -->
[+sb.title+]: [+sb.value+]<!-- ### -->, 