<?php
/**
 * Filename:       assets/plugins/tinymce/lang/francais.inc.php
 * Function:       French language file for TinyMCE
 * Encoding:       ISO
 * Author:         French community
 * Date:           2007/04/13
 * Version:        2.1.0
 * MODx version:   0.9.6
*/

include_once(dirname(__FILE__).'/english.inc.php'); // fallback for missing defaults or new additions

$_lang['tinymce_editor_theme_title'] = "Th�me:";
$_lang['tinymce_editor_theme_message'] = "Vous pouvez s�lectionner quel th�me ou template utiliser avec la barre d'outils TinyMCE.";
$_lang['tinymce_editor_custom_plugins_title'] = "Plugins :";
$_lang['tinymce_editor_custom_plugins_message'] = "Indiquez les plugins � utiliser pour le th�me 'personnalis�', en les s�parant par une virgule.";
$_lang['tinymce_editor_custom_buttons_title'] = "Boutons :";
$_lang['tinymce_editor_custom_buttons_message'] = "Indiquez les boutons � utiliser pour le th�me 'personnalis�', en les s�parant par une virgule. Chaque champ correspond � une ligne dans la barre d'outils. Assurez-vous que pour chacun des boutons s�lectionn�s, le plugin correspondant est indiqu� dans le champ de saisie 'Plugins'.";
$_lang["tinymce_editor_css_selectors_title"] = "S�lecteurs CSS:";
$_lang["tinymce_editor_css_selectors_message"] = "Vous pouvez sp�cifier une liste de s�lecteurs disponibles depuis la barre d'outils. D�finissez-les de la mani�re suivante :<br /> 'Nom de la classe 1=class1;Nom de la classe 2=class2'<br />Prenons l'exemple de la classe <b>.mono</b> et <b>.smallText</b> dans votre feuille de style. Vous pouvez les appeler de la fa�on suivante : <br />'Monospaced text=mono;Small text=smallText'<br />La derni�re entr�e de la ligne ne doit pas �tre suivie du point-virgule ( ; ).";
$_lang['tinymce_editor_relative_urls_title'] = "Chemin d'acc�s aux fichiers:";
$_lang['tinymce_editor_relative_urls_message'] = "Cette option vous permet de d�finir comment g�rer les chemins d'acc�s pour les liens internes. Note: Document relative links may not work right with friendly alias paths. Also, links might need to be changed if you move your site to a different domain or subdirectory if links are root relative or full path.";
$_lang["tinymce_compressor_title"] = "Compression:";
$_lang["tinymce_compressor_message"] = "Cette option active/d�sactive la compression Gzip de TinyMCE afin de r�duire le temps de chargement de la barre d'outils. Si votre serveur ne supporte pas la compression Gzip, laissez cette option sur disable.";
$_lang['tinymce_settings'] = "Configuration de TinyMCE";
$_lang['tinymce_theme_simple'] = "Simple";
$_lang['tinymce_theme_advanced'] = "Avanc�";
$_lang['tinymce_theme_editor'] = "Content Editor";
$_lang['tinymce_theme_custom'] = "Personnalis�";
$_lang['tinymce_theme_global_settings'] = "Utilisez le param�tre global";
?>
