<h1>Заказ № [+sb.order.id+]</h1>
<h2>Заказчик</h2>
<p>ФИО: [+sb.customer.fullname+]</p>
<p>Телефон: [+sb.customer.phone+]</p>
<p>Адрес: [+sb.customer.city+], [+sb.customer.address+]</p>
<h2>Информация о товарах</h2>
[+sb.products+]
<h2>Полная стоимость заказа</h2>
[+sb.order.price+]

[+sb.action+]
<!-- ### -->
<h2>Действие с заказом</h2>
<form method="post" action="">
<select name="sb_status_list">[+sb.wrapper+]</select>
<input type="submit" name="sb_set_status" value="Изменить статус" />
</form>
<!-- ### -->
<option value="[+sb.value+]">[+sb.title+]</option>
<!-- ### -->
<option value="[+sb.value+]" selected="selected">[+sb.title+]</option>
<!-- ### -->
<div class="products">
	[+sb.wrapper+]
</div>
<!-- ### -->
<div class="prod">
	<p>
		<span class="title"><a href="[+sb.url+]">[+sb.title+]</a></span>
		<span class="price">
			[+sb.price+] руб (x[+sb.quantity+])
		</span>
	</p>
	[+sb.options+]
</div>
<!-- ### -->
<ul class="optlist">[+sb.wrapper+]</ul>
<!-- ### -->
<li>[+sb.title+]: [+sb.value+]</li>