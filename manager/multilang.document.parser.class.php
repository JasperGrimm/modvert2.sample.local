<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 12/21/14
 * Time: 7:34 PM
 */

class MLDocumentParser extends DocumentParser {

    var $lang = 'ru';

//    function mergeChunkContent($content) {
//        $replace= array ();
//        $matches= array ();$this->chunkCache = array();
//        if (preg_match_all('~{{(.*?)}}~', $content, $matches)) {
//            $settingsCount= count($matches[1]);
//            for ($i= 0; $i < $settingsCount; $i++) {
//                if (isset ($this->chunkCache[$matches[1][$i]])) {
//                    $replace[$i]= $this->chunkCache[$matches[1][$i]];
//                } else {
//                    $sql= "SELECT `snippet` FROM " . $this->getFullTableName("site_htmlsnippets") . " WHERE " . $this->getFullTableName("site_htmlsnippets") . ".`name`='" . $this->db->escape($matches[1][$i]) . "';";
//                    $result= $this->db->query($sql);
//                    $limit= $this->db->getRecordCount($result);
//                    if ($limit < 1) {
//                        $this->chunkCache[$matches[1][$i]]= "";
//                        $replace[$i]= "";
//                    } else {
//                        $row= $this->db->getRow($result);
//                        if ("include MODX_BASE_PATH . 'assets/chunks/" == substr(trim($row['snippet']),0,40)) {
//                            if (array_key_exists($matches[1][$i], $this->chunkCache)){
//                                unset($this->chunkCache[$matches[1][$i]]);
//                            }
//                            preg_match('/include MODX_BASE_PATH.+\'([^;]+)\'/', $row['snippet'], $m);
//                            if (count($m) > 1 && $m[1]) {
//                                $row['snippet'] = file_get_contents(MODX_BASE_PATH . $m[1]);
//                            }
//                        }else{
//                            //$this->chunkCache[$matches[1][$i]]= $row['snippet'];
//                        }
//                        $replace[$i]= $row['snippet'];
//                    }
//                }
//            }
//            $content= str_replace($matches[0], $replace, $content);
//        }
//        return $content;
//    }

}