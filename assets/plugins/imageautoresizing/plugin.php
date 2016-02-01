<?php
/*
$Id: plugin_phpthumb.php 506 2008-07-04 19:29:50Z maff $

Name: phpthumb_plugin
Version: 0.2.1
Author: maFF (maff@ailoo.net)
License: GPL

Takes width and height values from images (set in Rich Text Editor) and passes the images to phpthumb for resizing.
So you can upload you original images and resize them in RTE.

For extended editing you can add additional parameters to the URL of the image directly in HTML (thanks to netProphET for this addition)

Example:
<img height="100" width="500" src="assets/images/testimage.jpg?fltr{}=gray&zc=1" /> will become this:
<img src="/image.php?src=/assets/images/testimage.jpg&amp;w=500&amp;h=100&amp;aoe=1&amp;fltr[]=gray&amp;zc=1&amp;hash=..." />

Dependencies: phx:phpthumb has to be installed for this plugin to work.
This plugin is based on the ImgResizer plugin by Magnatron (http://modxcms.com/ImgResizer-1134.html).
*/

$e = &$modx->Event;
switch ($e->name) {
    case "OnWebPagePrerender":

        $o = $modx->documentOutput;

        $imagesearch = array();
        $imagereplace = array();

        $pattern = '/<img[^>]*>/';
        preg_match_all($pattern, $o, $replace);

        for($n = 0; $n < count($replace[0]); $n++) {

            $imagetag = $replace[0][$n];

            if (strpos($imagetag, 'veriword.php') !== false)
                break;

            $search = array();
            $result = array();
            $image = array();

            $search['width'] = '/width="[0-9]+"/';
            $search['height'] = '/height="[0-9]+"/';
            $search['src'] = '/src="([^"]*)"/';
            $search['imagephp'] = '/image.php/';
            $search['phpthumb'] = '/phpThumb.php/';

            preg_match($search['width'], $imagetag, $result['width']);
            preg_match($search['height'], $imagetag, $result['height']);
            preg_match($search['src'], $imagetag, $result['src']);

            if(!empty($result['width'][0])) {
                $image['width'] = str_replace('width="', '', $result['width'][0]);
                $image['width'] = trim(str_replace('"', '', $image['width']));
            }

            if(!empty($result['height'][0])) {
                $image['height'] = str_replace('height="', '', $result['height'][0]);
                $image['height'] = trim(str_replace('"', '', $image['height']));
            }

            if(!empty($result['src'][1])) $image['src'] = $result['src'][1];

            preg_match($search['imagephp'], $image['src'], $result['imagephp']);
            preg_match($search['phpthumb'], $image['src'], $result['phpthumb']);
            if(count($result['imagephp']) == 0 && count($result['phpthumb']) == 0) {
                if(!empty($image['width']) || !empty($image['height'])) {

                    $snippetoptions = array();
                    if(!empty($image['width'])) $snippetoptions[] = 'w='.$image['width'];
                    if(!empty($image['height'])) $snippetoptions[] = 'h='.$image['height'];

                    // allow enlargement of images, uncomment this if you want
                    $snippetoptions[] = 'aoe=1';

                    // thanks to netProphET for this addition
                    // this allows you to set phpthumb parameters in the image URL
                    // see http://modxcms.com/forums/index.php/topic,14858.msg102750.html#msg102750
                    if(preg_match("/^([^\?]*)\?(.*)$/", $image['src'], $ma)) {
                        $aParam = array();
                        $image['src'] = $ma[1];
                        parse_str($ma[2], $aParam);

                        foreach($aParam as $k => $param) {
                            $snippetoptions[] = "{$k}={$param}";
                        }
                    }
                    unset($aParam, $param);

                    $snippetoptionstring = '';
                    for($i = 0; $i < count($snippetoptions); $i++) {
                        if($i != 0) $snippetoptionstring .= '&';
                        $snippetoptionstring .= $snippetoptions[$i];
                    }

                    if(substr($image['src'], 0, 1) != '/' && substr($image['src'], 0, 7) != 'http://')
                    {
                        $snippetoptionstring .= '#'.$modx->config['base_url'];
                    }

                    $snippetparams = array(
                        'output' 	=>		$image['src'],
                        'options' 	=>		$snippetoptionstring);

                    $newsrc = $modx->runSnippet('phx:phpthumb', $snippetparams);
                    $newtag = preg_replace('/src="([^"]*)"/', 'src="'.$newsrc.'&f=png"', $imagetag);
                    $newtag = preg_replace('/ width="[^"]*"/', '', $newtag);
                    $newtag = preg_replace('/ height="[^"]*"/', '', $newtag);
                    $newtag = str_replace('  ', ' ', $newtag);

                    $imagesearch[$n] = $imagetag;
                    $imagereplace[$n] = $newtag;
                }
            }
        }

        if(count($imagesearch) > 0 && count($imagesearch) == count($imagereplace)) {
            $modx->documentOutput = str_replace($imagesearch, $imagereplace, $o);
        }
        break;

    default :
        return;
        break;
}