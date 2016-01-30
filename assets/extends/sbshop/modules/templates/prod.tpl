<h1>[+lang.product_edit+]</h1>

<form name="mutate" id="mutate" class="content" method="post" enctype="multipart/form-data" action="[+module.link+]&mode=prod&act=[+module.act+]">
<input type="hidden" name="prodid" value="[+product.id+]" />
<input type="hidden" name="catid" value="[+product.category+]" />
<input type="hidden" name="ok" value="true" />
			
<div id="actions">
	<ul class="actionButtons">
		<li id="Button1">
			<a href="#" onclick="document.mutate.submit();">
				<img src="[+style.icons_save+]" />[+lang.save+]
			</a>
		</li>
		<li id="Button4"><a href="#" onclick="document.location.href='[+product.modulelink+]';"><img src="[+style.icons_cancel+]" />[+lang.cancel+]</a></li>
	</ul>
</div>

[+product.error+]

<div class="sectionBody">
	<div class="tab-pane" id="docManagerPane"> 
		<script type="text/javascript"> 
			tpResources = new WebFXTabPane(document.getElementById("docManagerPane")); 
		</script>
		
		<div class="tab-page" id="tabGeneral">  
			<h2 class="tab">[+lang.product_tab_general+]</h2>  
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabGeneral"));</script>
			<table width="99%" border="0" cellspacing="5" cellpadding="0">
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.product_title+]</span></td>
					<td><input name="title" type="text" maxlength="255" value='[+product.title+]' class="inputBox" onchange="documentDirty=true;" spellcheck="true" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.product_title_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.product_alias+]</span></td>
					<td><input name="alias" type="text" maxlength="255" value="[+product.alias+]" class="inputBox" onchange="documentDirty=true;" spellcheck="true" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.product_alias_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.product_sku+]</span></td>
					<td><input name="sku" type="text" maxlength="255" value="[+product.sku+]" class="inputBox" onchange="documentDirty=true;" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.product_sku_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.product_price+]</span></td>
					<td><input name="price" type="text" maxlength="255" value="[+product.price+]" class="inputBox" onchange="documentDirty=true;" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.product_price_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.product_published+]</span></td>
					<td><input name="published" type="checkbox" maxlength="255" value="1" class="inputBox" onchange="documentDirty=true;" spellcheck="true" [+product.published+] />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.product_published_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
			</table>
			
			<div class="sectionHeader">[+lang.product_content+]</div>
			<div class="sectionBody">
				<div style="width:100%">
					<textarea id="ta" name="description" style="width:100%; height: 400px;" onchange="documentDirty=true;">[+product.description+]</textarea>
				</div>
			</div>
		</div>
		<div class="tab-page" id="tabImages">
			<h2 class="tab">[+lang.product_tab_images+]</h2>
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabImages"));</script>
			<div class="sectionHeader">[+lang.product_images+]</div>
			<div class="sectionBody">
				<div>[+lang.product_images_tips+]</div>
				<div style="width:100%">
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.1+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.2+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.3+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.4+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.5+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.6+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.7+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.8+]</p>
					<p><input type="file" name="img[]" onchange="documentDirty=true;" />[+product.images.9+]</p>
				</div>
			</div>
		</div>
		<div class="tab-page" id="tabAttributes">
			<h2 class="tab">[+lang.product_tab_attributes+]</h2>
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabAttributes"));</script>
			<div class="sectionHeader">[+lang.product_attributes+]</div>
			<div class="sectionBody">
				<div>[+lang.product_attribute_tips+]</div>
				<div style="width:100%">
					<textarea id="ta" name="attributes" style="width:100%; height: 400px;" onchange="documentDirty=true;">[+product.attributes+]</textarea>
				</div>
			</div>
		</div>
		<div class="tab-page" id="tabOptions">
			<h2 class="tab">[+lang.product_tab_options+]</h2>
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabOptions"));</script>
			<div class="sectionHeader">[+lang.product_options+]</div>
			<div class="sectionBody">
				<div>[+lang.product_options_tips+]</div>
				<div style="width:100%">
					<textarea id="ta" name="options" style="width:100%; height: 400px;" onchange="documentDirty=true;">[+product.options+]</textarea>
				</div>
			</div>
		</div>
    </div>
</div>
</form>