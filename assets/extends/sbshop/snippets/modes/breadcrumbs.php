<?php

/**
 * @name SBShop
 * @author Mukharev Maxim
 * @version 0.1a
 * 
 * @desription
 * 
 * Электронный магазин для MODx
 * 
 * Экшен сниппета электронного магазина: Вывод категорий
 * 
 */

class breadcrumbs_mode {
	
	protected $oBreadcrumbs; // Хлебные крошки
	protected $aTemplates; // Массив с набором шаблонов
	
	/**
	 * Конструктор
	 */
	public function __construct() {
		global $modx;
		/**
		 * Задаем набор шаблонов
		 */
		$sTemplate = $modx->sbshop->getSnippetTemplate('breadcrumbs');
		/**
		 * Разбиваем стандартный набор шаблонов
		 */
		list($sBreadcrumbsOuter,$sBreadcrumbsRow,$sBreadcrumbsLastRow,$sBreadcrumbsSeparator) = explode('<!-- ### -->',$sTemplate);
		/**
		 * Устанавливаем шаблоны
		 */
		$this->aTemplates['breadcrumbs_outer'] = $sBreadcrumbsOuter;
		$this->aTemplates['breadcrumbs_row'] = $sBreadcrumbsRow;
		$this->aTemplates['breadcrumbs_lastrow'] = $sBreadcrumbsLastRow;
		$this->aTemplates['breadcrumbs_separator'] = $sBreadcrumbsSeparator;
		/**
		 * Получаем "хлебные крошки"
		 */
		$this->oBreadcrumbs = new SBBreadcrumbs();
		/**
		 * Вывод "хлебных крошек" в плейсхолдер [+sb.innercat+]
		 */
		$modx->setPlaceholder('sb.breadcrumbs',$this->outputBreadcrumbs());
	}
	
	/**
	 * Вывод "хлебных крошек" для навигации
	 */
	public function outputBreadcrumbs() {
		global $modx;
		/**
		 * Записываем в содержимое основной контейнер
		 */
		$sOutput = $this->aTemplates['breadcrumbs_outer'];
		/**
		 * Инициализируем временный массив для рядов
		 */
		$aRows = array();
		/**
		 * Получаем список пунктов
		 */
		$aBreadcrumbs = $this->oBreadcrumbs->getBreadcrumbs();
		/**
		 * Обрабатываем каждый пункт
		 */
		$iCnt = count($aBreadcrumbs);
		for($i=0;$i<$iCnt;$i++) {
			/**
			 * Подготавливаем информацию для вставки в шаблон
			 */
			$aRepl = $modx->sbshop->arrayToPlaceholders($aBreadcrumbs[$i]);
			/**
			 * Если последний элемент
			 */
			if($i == ($iCnt - 1)) {
				$aRows[] = str_replace(array_keys($aRepl),array_values($aRepl),$this->aTemplates['breadcrumbs_lastrow']);
			} else {
				$aRows[] = str_replace(array_keys($aRepl),array_values($aRepl),$this->aTemplates['breadcrumbs_row']);
			}
		}
		/**
		 * Добавляем разделитель
		 */
		$sRepl = implode($this->aTemplates['breadcrumbs_separator'],$aRows);
		/**
		 * Делаем вставку в контейнер
		 */
		$sOutput = str_replace('[+sb.wrapper+]',$sRepl,$sOutput);
		/**
		 * Отдаем результат
		 */
		return $sOutput;
	}
	
}

?>