<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 12/21/14
 * Time: 8:03 PM
 */

if (!function_exists('_createChunkTab')) {
    function _createChunkTab($name, $id)
    {
        $tabs_template = <<<'TABS'
<div class="dynamic-tab-pane-control tab-pane" id="htmlsnipetPane" style="display:none;">
    <script type="text/javascript">
    var tabPane = new WebFXTabPane( document.getElementById( "htmlsnipetPane" ) );
    </script>
  <!-- RU -->
  <div class="tab-page" id="tabRU">
        <h2 class="tab">
          <span>RU</span>
        </h2>
        <script type="text/javascript">
          tabPane.addTabPage( document.getElementById( "tabRU" ) );
        </script>
        <!-- PHP text editor start -->
  </div>

  <!-- UA -->
  <div class="tab-page" id="tabUA">
    <h2 class="tab">
      <span>UA</span>
    </h2>
    <script type="text/javascript">
      tabPane.addTabPage( document.getElementById( "tabUA" ) );
    </script>
  </div>
</div>
TABS;

        $scr = <<<'JS'
setTimeout(function(){
    var container = $j('.sectionBody');
    var table = container.find('table:first');
    var textbox = table.next('div');
    var textarea = textbox.find('textarea');

    //var htmlsnipetPane = new WebFXTabPane( document.getElementById( "htmlsnipetPane"), false );
    //console.log($j( "#tabRU" ));
    //htmlsnipetPane.addTabPage( document.getElementById( "tabRU" ) );
    ////htmlsnipetPane.addTabPage( document.getElementById( "tabUA" ) );
    var tabPane = $j('#htmlsnipetPane');
    tabPane.insertAfter(table).show();

    if ('undefined' !== typeof tinymce) {
        tinymce.execCommand('mceRemoveControl', false, textarea.attr('id'));
        textbox.appendTo(tabPane.find('#tabRU'));
        tinymce.execCommand('mceAddControl', false, textarea.attr('id'));
    }else{
        textbox.appendTo(tabPane.find('#tabRU'));
    }
}, 100);

JS;
        echo '<script type="text/javascript" src="/manager/media/script/tabpane.js"></script>';
        echo $tabs_template;
        echo '<script>' . $scr . '</script>';

    }
}

/** @var SystemEvent $event */
$event = &$modx->Event;

if (!in_array($event->name, array('OnDocFormRender', 'OnDocFormPrerender', 'OnTVFormRender'))) {
    echo '<script src="/themes/questoria/js/jquery-1.10.2.min.js"></script><script>$j = jQuery.noConflict();</script>';
}

switch ($event->name) {
    case 'OnChunkFormRender':
        _createChunkTab('Test', 'tt');
        break;

}