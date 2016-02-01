<?php

class ScienceStyle {
	
	function __construct() {
	}
	
    function makeIcon($ph=array()) {
    	$tpl = '<span class="wm_button"><a class="hometblink" href="[+action+]" alt="[+alt+]"><img src="[+imgsrc+]" /><br />[+title+]</a></span>';
    	return $this->ph($tpl,$ph);
    }

    function ph($tpl='', $ph=array()) {
    	foreach($ph as $k=>$v) {
    		$k = '[+' . $k . '+]';
    		$tpl = str_replace($k, $v, $tpl);
    	}
    	return $tpl;
    }
}
