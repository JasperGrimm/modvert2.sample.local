<div class="content">
	<div class="regform">
		[+sb.error+]
		<form method="post" action="[+sb.link_action+]">
			<h3>[+lang.customer_fullname+]</h3>
			<p><input type="text" name="sb_customer_fullname" value="[+sb.fullname+]" class="[+error_sb_customer_fullname+]" /> <span class="required">обязательное поле</span></p>
			<h3>[+lang.customer_phone+]</h3>
			<p><input type="text" name="sb_customer_phone" value="[+sb.phone+]" class="[+error_sb_customer_phone+]" /> <span class="required">обязательное поле</span></p>
			<h3>[+lang.customer_email+]</h3>
			<p><input type="text" name="sb_customer_email" value="[+sb.email+]" class="[+error_sb_customer_email+]" /></p>
			<h3>[+lang.customer_city+]</h3>
			<p><input type="text" name="sb_customer_city" value="[+sb.city+]" class="[+error_sb_customer_city+]" /> <span class="required">обязательное поле</span></p>
			<h3>[+lang.customer_address+]</h3>
			<p><input type="text" name="sb_customer_address" value="[+sb.address+]" class="[+error_sb_customer_address+]" /> <span class="required">обязательное поле</span></p>
			<p>
				<input class="button_active" type="submit" name="sb_customer_submit" value="[+lang.customer_submit+]" />
			</p>
		</form>
	</div>
</div>
<!-- ### -->
<div class="error">
	<p>Обратите внимание на правильность заполнения:</p>
	<ul>
		[+sb.wrapper+]
	</ul>
</div>
<!-- ### -->
<li>[+sb.row+]</li>