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
 * Экшен сниппета электронного магазина: Вывод товара
 * 
 */

class product_mode {
	
	protected $aTemplates; // Массив с набором шаблонов
	
	/**
	 * Конструктор
	 */
	public function __construct($sMode) {
		global $modx;
		/**
		 * Задаем набор шаблонов
		 */
		$sTemplate = $modx->sbshop->getSnippetTemplate('product');
		/**
		 * Разбиваем шаблон на части
		 */
		list($sProduct,$sOuterTmb,$sRowTmb,$sOuterAttr,$sRowAttr,$sOuterOpt,$sRowSimpleOpt,$sRowMultOpt) = explode('<!-- ### -->',$sTemplate);
		/**
		 * Устанавливаем шаблоны
		 */
		$this->aTemplates['product'] = $sProduct;
		$this->aTemplates['thumbs_outer'] = $sOuterTmb;
		$this->aTemplates['thumbs_row'] = $sRowTmb;
		$this->aTemplates['attribute_outer'] = $sOuterAttr;
		$this->aTemplates['attribute_row'] = $sRowAttr;
		$this->aTemplates['option_outer'] = $sOuterOpt;
		$this->aTemplates['simple_option_row'] = $sRowSimpleOpt;
		$this->aTemplates['multi_option_row'] = $sRowMultOpt;
		/**
		 * Вывод списка товаров
		 */
		$modx->setPlaceholder('sb.product',$this->outputProduct());
		
	}
	
	/**
	 * Вывод списка товаров для текущей категории
	 */
	public function outputProduct() {
		global $modx;
		/**
		 * Инициализируем переменную для вывода результата
		 */
		$sOutput = '';
		/**
		 * Получение плейсхолдеров товара
		 */
		$aRepl = $modx->sbshop->arrayToPlaceholders($modx->sbshop->oGeneralProduct->getAttributes());
		/**
		 * Добавляем изображения
		 */
		$aRepl = array_merge($aRepl,$modx->sbshop->multiarrayToPlaceholders($modx->sbshop->oGeneralProduct->getAllImages(),'num','sb.image.'));
		/**
		 * Подготавливает массив миниатюры
		 */
		$aImages = $modx->sbshop->oGeneralProduct->getImagesByKey('x104');
		/**
		 * Переменная для блока миниатюр
		 */
		$sImages = '';
		/**
		 * Обрабатываем каждую картинку
		 */
		foreach ($aImages as $sImage) {
			/**
			 * Вставляем линк
			 */
			$sImages .= str_replace('[+sb.image+]', $sImage, $this->aTemplates['thumbs_row']);
		}
		/**
		 * Вставляем картинки в контейнер
		 */
		$sImages = str_replace('[+sb.wrapper+]', $sImages, $this->aTemplates['thumbs_outer']);
		/**
		 * Добавляем в плейсхолдеры блок с миниатюрами
		 */
		$aRepl['[+sb.thumbs+]'] = $sImages;
		/**
		 * Переменная для опций
		 */
		$sOptions = '';
		/**
		 * Обрабатываем все опции
		 */
		foreach ($modx->sbshop->oGeneralProduct->getOptionNames() as $aOption) {
			/**
			 * Значения
			 */
			$sOptRaw = '';
			/**
			 * Массив значений
			 */
			$aValues = $modx->sbshop->oGeneralProduct->getValuesByOptionName($aOption['title']);
			/**
			 * Если есть только одно значение
			 */
			if(count($aValues) == 1) {
				$aReplVal = $modx->sbshop->arrayToPlaceholders(array_pop($aValues));
				$sOptRaw = str_replace(array_keys($aReplVal), array_values($aReplVal), $this->aTemplates['simple_option_row']);
			} else {
				/**
				 * Обрабатываем значения
				 */
				foreach ($aValues as $sValueKey => $sValueVal) {
					$aReplVal = $modx->sbshop->arrayToPlaceholders($sValueVal);
					$sOptRaw .= str_replace(array_keys($aReplVal), array_values($aReplVal), $this->aTemplates['multi_option_row']);
				}
			}
			/**
			 * Плейсхолдеры для опции
			 */
			$aReplOpt['[+sb.wrapper+]'] = $sOptRaw;
			$aReplOpt = array_merge($aReplOpt,$modx->sbshop->arrayToPlaceholders($aOption,'sb.option.'));
			
			/**
			 * Заменяем
			 */
			$sOptions .= str_replace(array_keys($aReplOpt), array_values($aReplOpt), $this->aTemplates['option_outer']);
		}
		/**
		 * Добавляем плейсхолдер
		 */
		$aRepl['[+sb.options+]'] = $sOptions;
		/**
		 * Получаем набор характеристик
		 */
		$aAttributes = $modx->sbshop->oGeneralProduct->getExtendAttributes();
		/**
		 * Ряды значений
		 */
		$sAttrRows = '';
		/**
		 * Обрабатываем каждый параметр
		 */
		foreach ($aAttributes as $sAttrKey => $sAttrVal) {
			$aAttrRepl = array(
				'[+sb.title+]' => $sAttrKey,
				'[+sb.value+]' => $sAttrVal,
			);
			/**
			 * Формируем ряд
			 */
			$sAttrRows .= str_replace(array_keys($aAttrRepl), array_values($aAttrRepl), $this->aTemplates['attribute_row']);
		}
		/**
		 * Вставляем параметры в контейнер
		 */
		$aRepl['[+sb.attributes+]'] = str_replace('[+sb.wrapper+]', $sAttrRows, $this->aTemplates['attribute_outer']);
		/**
		 * Делаем замену в основном шаблоне
		 */
		$sOutput .= str_replace(array_keys($aRepl),array_values($aRepl),$this->aTemplates['product']);
		/**
		 * Возвращаем результат
		 */
		return $sOutput;
	}
}

?>