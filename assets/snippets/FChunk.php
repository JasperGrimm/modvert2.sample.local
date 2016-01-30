<?php
$chunk_name = isset($chunk)?$chunk:null;
var_dump($chunk_name);
if (!$chunk_name) { echo ''; exit; }
$content = $modx->parseChunk($chunk_name, $modx->event->params,'[+','+]');
var_dump($content);
echo $content;
if (substr(trim($content),0,40) == 'include MODX_BASE_PATH . \'assets/chunks/'){
    
}
?>