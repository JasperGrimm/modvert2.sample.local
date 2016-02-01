<?php
/**
 * @name ChunkFile
 * @version 0.0.1
 *
 * @description Хранение чанков в файлах
 * @Events: [
 *         OnChunkFormRender,
 *         OnBeforeChunkFormSave,
 *         OnChunkFormPrerender
 * ]
 * @author Jasper Grimm
 */

global $_lang;

$e = $modx->Event;

$output = '';

/**
 * Подготовка информации перед рендером формы редактирования чанка
 */
switch ($e->name) {
        case 'OnWebPageInit':
                global $content;
                var_dump($content);
        break;
        case 'OnChunkFormPrerender':
                global $content;
                if(substr(trim($content['snippet']),0,40) == 'include MODX_BASE_PATH . \'assets/chunks/'){
                        $content['file_binding'] = str_replace(array(';','\''),'',trim(substr(trim($content['snippet']),40,250)));
                        $snippetPath = MODX_BASE_PATH . 'assets/chunks/' . $content['file_binding'];
                        $content['snippet'] = file_get_contents($snippetPath);
                        $_SESSION['itemname']=$content['name'];
                } else {
                        $_SESSION['itemname']="New chunk";
                }
                break;
        case 'OnChunkFormRender':
                global $content;
                $output = '
                        <script type="text/javascript">
                                mE1 = new Element("tr");
                                mE11 = new Element("td",{"align":"left", "styles":{"padding-top":"5px"}});
                                mE12 = new Element("td",{"align":"left","styles":{"padding-top":"5px"}});
                                mE122 = new Element("input",{"name":"filebinding","type":"text","maxlength":"45","value":"'.$content["file_binding"].'","class":"inputBox","styles":{"width":"300px","margin-left":"14px"},"events":{"change":function(){documentDirty=true;}}});

                                mE11.appendText("Файл чанка(отн. assets/chunks):");
                                mE11.inject(mE1);
                                mE122.inject(mE12);
                                mE12.inject(mE1);
                                setPlace = $$("tr")[4];
                                mE1.inject(setPlace,"before");

                        </script>
                ';
                break;
        case 'OnBeforeChunkFormSave':
                if(!empty($_POST['filebinding'])) {
                        global $snippet;
                        $pathsnippet = trim($modx->db->escape($_POST['filebinding']));
                        $fullpathsnippet = MODX_BASE_PATH . 'assets/chunks/' . $pathsnippet;

                        if($fl = fopen($fullpathsnippet,'w')) {
                                fwrite($fl, $_POST['post']);
                                fclose($fl);
                                $snippet = $modx->db->escape('include MODX_BASE_PATH . \'assets/chunks/' . $pathsnippet . '\';');
                        }
                }
                break;
}

if($output != '') {
        $e->output($output);
}
