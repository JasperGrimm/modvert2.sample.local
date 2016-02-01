<?php
/**
* snippets/eform/francais.inc.php
* Fichier Langue en francais pour eForm
*/


$_lang["ef_thankyou_message"] = "<h3>Merci !</h3><p>Vos informations ont �t� correctement transmises.</p>";
$_lang["ef_no_doc"] = "Document ou chunk introuvable pour le mod�le n�=";
//$_lang["ef_no_chunk"] = ""; //obsol�te
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>Des erreurs ont �t� detect�es dans le formulaire :</strong><br />[+ef_wrapper+]</div>";
$_lang["ef_required_message"] = " Les champs indispensables ci-dessous sont introuvables: {fields}<br />";
$_lang["ef_invalid_number"] = " n'est pas un nombre valide";
$_lang["ef_invalid_date"] = " n'est pas une date valide";
$_lang["ef_invalid_email"] = " n'est pas une adresse mail valide";
$_lang["ef_upload_exceeded"] = " a d�pass� la limite de taille en upload.";
$_lang["ef_failed_default"] = "Valeur incorrecte";
$_lang["ef_failed_vericode"] = "Code de v�rification invalide.";
$_lang["ef_failed_range"] = "La valeur n'est pas dans l'intervalle permis";
$_lang["ef_failed_list"] = "La valeur n'est pas dans la liste des valeurs permises";
$_lang["ef_failed_eval"] = "Valeur non valid�e";
$_lang["ef_failed_ereg"] = "Valeur non valid�e";
$_lang["ef_failed_upload"] = "type de fichier incorrect.";
$_lang["ef_error_validation_rule"] = "R�gle de validation non reconnue";
$_lang["ef_tamper_attempt"] = "tentative pour trafiquer le code du formulaire detect�e!";
$_lang["ef_error_formid"] = "identifiant ou nom du formulaire incorrect.";
$_lang["ef_debug_info"] = "Information de d�boggage: ";
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Le mod�le de formulaire � utiliser est identifi� par une page contenant un appel du snippet! Vous ne pouvez pas avoir le formulaire et l'appel du snippet dans le m�me document.</span> id=";
$_lang["ef_sql_no_result"] = " Validation silencieuse pass�e. <span style=\"color:red;\"> SQL n'a retoun� aucun resultat!</span> ";
$_lang['ef_regex_error'] = 'Erreur dans l\'expression r�guli�re';
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">ATTENTION - LE MODE DEBUG EST ACTIV�</span> <br />Assurez-vous de d�sactiver le mode debuggage avant de deployer ce formulaire!</p>';
$_lang['ef_mail_abuse_subject'] = 'un abus potentiel de formualaire a �t� d�tect� avec le formulaire n�';
$_lang['ef_mail_abuse_message'] = '<p>Un formulaire de votre site Web a peut-�tre fait l\'objet d\'une tentative d\'injection d\'email. Le d�tail des valeurs envoy�es sont imprim�es ci-dessous. Le texte suspect� est mis en valeur � l\'aide des tags \[..]\ .  </p>';
$_lang['ef_mail_abuse_error'] = '<strong>Des entr�es invalides ou dangereuses ont �t� detect�es dans votre formulaire</strong>.';
$_lang['ef_eval_deprecated'] = "La r�gle #EVAL est obsol�te et ne devrait plus fonctionner dans les versions futures. Utilisez #FUNCTION � la place.";
$_lang['ef_multiple_submit'] = "<p>Ce formulaire a d�j� �t� soumis avec succ�s. Il est inutile de soumettre le m�me formulaire plusieurs fois.</p>";
$_lang['ef_submit_time_limit'] = "<p>Ce formulaire a d�j� �t� soumis avec succ�s. La soumission du formulaire est bloqu�e pour ".($submitLimit/60)." minutes.</p>";
$_lang['ef_version_error'] = "<strong>ATTENTION!</strong> La version du snippet eForum (version:&nbsp;$version) est diff�rente de celle des fichiers eForm pr�sents sur votre serveur (version:&nbsp;$fileVersion). Attention de bien mettre � jour les fichiers lorsque vous mettez � jour le snippet.";
$_lang['ef_thousands_separator'] = ''; //leave empty to use (php) locale, only needed if you want to overide locale setting!
$_lang['ef_date_format'] = '%d-%b-%Y %H:%M:%S';
$_lang['ef_mail_error'] = 'Le serveur mail est incapable d\'exp�dier l\'email';
?>