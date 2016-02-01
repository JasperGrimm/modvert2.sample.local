<h1>[+lang.category_edit+]</h1>

<form name="mutate" id="mutate" class="content" method="post" enctype="multipart/form-data" action="[+module.link+]&mode=cat&act=[+module.act+]">
<input type="hidden" name="catid" value="[+category.id+]" />
<input type="hidden" name="parid" value="[+category.parent+]" />
<input type="hidden" name="ok" value="true" />
			
<div id="actions">
	<ul class="actionButtons">
		<li id="Button1">
			<a href="#" onclick="document.mutate.submit();">
				<img src="[+style.icons_save+]" />[+lang.save+]
			</a>
		</li>
		<li id="Button4"><a href="#" onclick="document.location.href='[+category.modulelink+]';"><img src="[+style.icons_cancel+]" />[+lang.cancel+]</a></li>
	</ul>
</div>

[+category.error+]

<div class="sectionBody">
	<div class="tab-pane" id="docManagerPane"> 
		<script type="text/javascript"> 
			tpResources = new WebFXTabPane(document.getElementById("docManagerPane")); 
		</script>
		
		<div class="tab-page" id="tabGeneral">  
			<h2 class="tab">[+lang.category_tab_general+]</h2>  
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabGeneral"));</script>
			<table width="99%" border="0" cellspacing="5" cellpadding="0">
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.category_title+]</span></td>
					<td><input name="title" type="text" maxlength="255" value="[+category.title+]" class="inputBox" onchange="documentDirty=true;" spellcheck="true" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.category_title_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.category_alias+]</span></td>
					<td><input name="alias" type="text" maxlength="255" value="[+category.alias+]" class="inputBox" onchange="documentDirty=true;" spellcheck="true" />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.category_alias_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
				<tr style="height: 24px;"><td width="100" align="left"><span class="warning">[+lang.category_published+]</span></td>
					<td><input name="published" type="checkbox" maxlength="255" value="1" class="inputBox" onchange="documentDirty=true;" spellcheck="true" [+category.published+] />
					&nbsp;&nbsp;<img src="[+style.icons_tooltip_over+]" onmouseover="this.src='[+style.icons_tooltip+]';" onmouseout="this.src='[+style.icons_tooltip_over+]';" alt="[+lang.category_published_description+]" onclick="alert(this.alt);" style="cursor:help;" /></td></tr>
			</table>
			
			<div class="sectionHeader">[+lang.category_content+]</div>
			<div class="sectionBody">
				<div style="width:100%">
					<textarea id="ta" name="description" style="width:100%; height: 400px;" onchange="documentDirty=true;">[+category.description+]</textarea>
				</div>
			</div>
		</div>
		<div class="tab-page" id="tabAttributes">
			<h2 class="tab">[+lang.category_tab_attributes+]</h2>
			<script type="text/javascript">tpResources.addTabPage(document.getElementById("tabAttributes"));</script>
			Пока управление сделано в текстовом виде. Затем управление параметрами будет более умное.
			<div class="sectionHeader">[+lang.category_attributes+]</div>
			<div class="sectionBody">
				<div>[+category.attribute_tips+]</div>
				<div style="width:100%">
					<textarea id="ta" name="attributes" style="width:100%; height: 400px;" onchange="documentDirty=true;">[+category.attributes+]</textarea>
				</div>
			</div>
		</div>
    </div>
</div>
</form>