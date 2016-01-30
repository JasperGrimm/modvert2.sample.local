<?php
/**
* snippets/eform/lang/svenska.inc.php
* Swedish language file for eForm 1.4.4
*
* Translation: Pontus �gren (Pont)
* Date: 2007-04-18
*/


$_lang["ef_thankyou_message"] = "<h3>Tack!</h3><p>Din information skickades utan problem.</p>";
$_lang["ef_no_doc"] = "Varken dokument eller chunk kunde inte hittas f�r mall-ID: ";
$_lang["ef_validation_message"] = "<div class=\"errors\"><strong>N�gra fel uppt�cktes i ditt formul�r:</strong><br />[+ef_wrapper+]</div>";
$_lang['ef_rule_passed'] = 'Utf�rdes med regeln [+rule+] (input="[+input+]").';
$_lang['ef_rule_failed'] = '<span style="color:red;">Misslyckades</span> med regeln [+rule+] (input="[+input+]")';
$_lang["ef_required_message"] = " De f�ljande, n�dv�ndiga, f�lt(en) saknas: {fields}<br />";
$_lang['ef_error_list_rule'] = 'Ett fel uppstod n�r formul�rf�ltet validerades! En #LIST-regel �r deklarerad, men inga listv�rden funna: ';
$_lang["ef_invalid_number"] = " �r inte ett giltigt nummer";
$_lang["ef_invalid_date"] = " �r inte ett giltigt datum";
$_lang["ef_invalid_email"] = " �r inte en giltig epostadress";
$_lang["ef_upload_exceeded"] = " har �verskridit den maximala uppladdningsstorleken.";
$_lang["ef_upload_error"] = ": fel vid uppladdning av fil."; //NEW
$_lang["ef_failed_default"] = "Falaktigt v�rde";
$_lang["ef_failed_vericode"] = "Felaktig verifieringskod.";
$_lang["ef_failed_range"] = "V�rdet �r inte inom det till�tna omr�det";
$_lang["ef_failed_list"] = "V�rdet finns inte i listan med till�tna v�rden";
$_lang["ef_failed_eval"] = "V�rdet validerar inte";
$_lang["ef_failed_ereg"] = "V�rdet validerar inte";
$_lang["ef_failed_upload"] = "Felaktig filtyp.";
$_lang["ef_error_validation_rule"] = "Valideringsregeln k�nns inte igen";
$_lang["ef_error_filter_rule"] = "Textfiltret k�ndes inte igen";
$_lang["ef_tamper_attempt"] = "Manipuleringsf�rs�k uppt�ckt!";
$_lang["ef_error_formid"] = "Ogiltigt ID-nummer eller -namn i formul�ret.";
$_lang["ef_debug_info"] = "Debugg-info: "; 
$_lang["ef_is_own_id"] = "<span class=\"ef-form-error\">Formul�rmallen �r satt till samma ID som sidan som inneh�ller snippet-anropet! Du kan inte ha formul�ret i samma dokument som snippet-anropet.</span> ID="; 
$_lang["ef_sql_no_result"] = " klarade valideringen i smyg. <span style=\"color:red;\"> SQL returnerade inget resultat!</span> "; 
$_lang['ef_regex_error'] = 'fel i regulj�ra uttrycket '; 
$_lang['ef_debug_warning'] = '<p style="color:red;"><span style="font-size:1.5em;font-weight:bold;">VARNING - DEBUGGNING �R P�</span> <br />Var noga med att st�nga av debuggningen innan du b�rjar anv�nda formul�ret live!</p>'; 
$_lang['ef_mail_abuse_subject'] = 'Potentiellt manipuleringsf�rs�k av mailformul�r uppt�ckt f�r formul�r-ID'; 
$_lang['ef_mail_abuse_message'] = '<p>Ett formul�r p� din webbplats kan vara f�rem�l f�r ett mailinjiceringsf�rs�k. De inmatade v�rdena �r utskrivna nedan. Misst�nkt text har b�ddats in i \[..]\-taggar.</p>'; 
$_lang['ef_mail_abuse_error'] = '<strong>Felaktiga eller os�kra v�rden uppt�cktes i ditt formul�r</strong>.';
$_lang['ef_eval_deprecated'] = "#EVAL-regeln anv�nds inte l�ngre och kommer kanske inte att fungera i framtida versioner. Anv�nd #FUNCTION ist�llet.";
$_lang['ef_multiple_submit'] = "<p>Det h�r formul�ret har redan skickats utan problem. Du beh�ver inte skicka din information flera g�nger.</p>";
$_lang['ef_submit_time_limit'] = "<p>Det h�r formul�ret har redan skickats utan problem. Omskickning av formul�ret �r blockerat i  ".($submitLimit/60)." minuter.</p>";
$_lang['ef_version_error'] = "<strong>VARNING!</strong> Versionen p� eForm-snippeten (version:&nbsp;$version) skiljer sig fr�n den inkluderade eForm-filen (version:&nbsp;$fileVersion). Kontrollera att du anv�nder samma version p� b�da.";
$_lang['ef_thousands_separator'] = ''; //leave empty to use (php) locale, only needed if you want to overide locale setting!
$_lang['ef_date_format'] = '%Y-%b-%d %H:%M:%S';
$_lang['ef_mail_error'] = 'Mailscriptet kunde inte skicka eposten';
?>
