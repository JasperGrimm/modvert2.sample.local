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

class categories_mode {
	protected $sMode; // Рабочий режим
	protected $oCatTree; // Дерево вложенных категорий
	protected $oProductList; // Список товаров
	protected $aTemplates; // Массив с набором шаблонов

	/**
	 * Конструктор
	 * @param <type> $sMode
	 */
	public function __construct($sMode = false, $toPlaceholder = true, $iCatId = false) {
		global $modx;
		/**
		 * Если передана категория
		 */
		if($iCatId) {
			/**
			 * Экземпляр
			 */
			$oCategory = new SBCategory();
			/**
			 * Загружаем по идентификатору
			 */
			$oCategory->load($iCatId);
		} else {
			/**
			 * Берем базовую категорию
			 */
			$oCategory = $modx->sbshop->oGeneralCategory;
		}

		/**
		 * Получаем дерево категорий
		 */
		$this->oCatTree = new SBCatTree($oCategory);
		/**
		 * Получаем список товаров
		 */
		$this->oProductList = new SBProductList($oCategory->getAttribute('id'));
		/**
		 * Устанавливаем шаблоны
		 */
		$this->setTemplates();
		if($toPlaceholder) {
			/**
			 * Вывод информации о вложенных категория в плейсхолдер [+sb.innercat+]
			 */
			$modx->setPlaceholder('sb.innercat',$this->outputInnerCat());
			/**
			 * Вывод списка товаров
			 */
			$modx->setPlaceholder('sb.productlist',$this->outputProducts());
		}
	}
	
	/**
	 * Формирование набора стандартных шаблонов
	 * @param unknown_type $sTemplate
	 */
	public function setTemplates($sTemplate = false) {
		global $modx;
		/**
		 * Загружаем стандартный файл с шаблонами
		 */
		if(!$sTemplate) {
			$sTemplate = $modx->sbshop->getSnippetTemplate('categories');
			$sTemplateProducts = $modx->sbshop->getSnippetTemplate('productlist');
		}
		/**
		 * Разбиваем стандартный набор шаблонов
		 */
		list($sOuter,$sRow,$sInner,$sInnerRow) = explode('<!-- ### -->',$sTemplate);
		list($sOuterProduct,$sRowProduct,$sOuterAttr,$sRowAttr) = explode('<!-- ### -->', $sTemplateProducts);
		/**
		 * Устанавливаем шаблоны
		 */
		$this->aTemplates['category_outer'] = $sOuter;
		$this->aTemplates['category_row'] = $sRow;
		$this->aTemplates['category_inner'] = $sInner;
		$this->aTemplates['category_innerrow'] = $sInnerRow;
		$this->aTemplates['products_outer'] = $sOuterProduct;
		$this->aTemplates['products_row'] = $sRowProduct;
		$this->aTemplates['attribute_outer'] = $sOuterAttr;
		$this->aTemplates['attribute_row'] = $sRowAttr;
	}
	
	/**
	 * Вывод информации для вложенных категорий
	 */
	public function outputInnerCat() {
		global $modx;
		/**
		 * Получаем набор уровней
		 */
		$aLevels = $this->oCatTree->getCatTreeLevels();
		/**
		 * Если нет данных, то на выход
		 */
		if(count($aLevels) == 0) {
			return;
		}
		/**
		 * Записываем в содержимое основной контейнер
		 */
		$sOutput = $this->aTemplates['category_outer'];
		/**
		 * Счетчик уровня вложенности
		 */
		$iLevel = 0;
		foreach ($aLevels as $aLevel) {
			/**
			 * Увеличиваем счетчик
			 */
			$iLevel++;
			/**
			 * Данные с пунктами
			 */
			$sRows = '';
			/**
			 * Идентификатор враппера
			 */
			$iWrapperId = 0;
			/**
			 * Обрабатываем каждый вложенный пункт
			 */
			foreach ($aLevel as $iCatId) {
				/**
				 * Получаем массив параметров категории
				 */
				$aAttributes = $this->oCatTree->getAttributesById($iCatId);
				/**
				 * Получаем информацию о вложенных товарах и добавляем в массив
				 */
				$aAttributes['products'] = $this->outputInnerProducts($iCatId);
				/**
				 * Получаем список плейсхолдеров
				 */
				$aPlaceholders = $modx->sbshop->arrayToPlaceholders($aAttributes);
				/**
				 * Заголовок маленькими буквами
				 */
				$aPlaceholders['[+sb.title.l+]'] = mb_strtolower($aAttributes['title'],'UTF-8');
				/**
				 * Плейсхолдер для вложенных пунктов по идентификатору
				 */
				$aPlaceholders['[+sb.wrapper+]'] = '[+sb.wrapper.' . $aAttributes['id'] . '+]';
				/**
				 * Устанавливаем идентификатор враппера
				 */
				if(!$iWrapperId) {
					$iWrapperId = $aAttributes['parent'];
				}
				/**
				 * Первый уровень обрабатывается отдельно
				 */
				if($iLevel == 1) {
					/**
					 * Делаем вставку данных в шаблон row
					 */
					$sRows .= str_replace(array_keys($aPlaceholders),array_values($aPlaceholders),$this->aTemplates['category_row']);
				} else {
					/**
					 * Делаем вставку в шаблон innerrow
					 */
					$sRows .= str_replace(array_keys($aPlaceholders),array_values($aPlaceholders),$this->aTemplates['category_innerrow']);
				}
				
			}
			$sWrapper = '[+sb.wrapper+]';
			/**
			 * Делаем вставку в контейнер если это не первый уровень
			 */
			if($iLevel != 1) {
				$sRows = str_replace('[+sb.wrapper+]',$sRows,$this->aTemplates['category_inner']);
				$sWrapper = '[+sb.wrapper.' . $iWrapperId . '+]';
			}
			/**
			 * Вставляем подготовленную информацию в основное содержимое
			 */
			$sOutput = str_replace($sWrapper,$sRows,$sOutput);
		}
		/**
		 * Отдаем результат
		 */
		return $sOutput;
	}
	
	/**
	 * Вывод списка товаров для текущей категории
	 */
	public function outputProducts() {
		global $modx;
		/**
		 * Инициализируем переменную для вывода результата
		 */
		$sOutput = '';
		/**
		 * Информация о текущей категории
		 */
		$aCategory = $modx->sbshop->oGeneralCategory->getAttributes();
		/**
		 * Список текущих режимов
		 */
		$aModes = $modx->sbshop->getModes();
		/**
		 * Если первый режим - main
		 */
		if($aModes[0] == 'main') {
			/**
			 * Заговловок главной страницы каталога
			 */
			$modx->setPlaceholder('sb.longtitle',$modx->sbshop->lang['shop_title']);
		} else {
			/**
			 * Устанавливаем глобальный плейсхолдер для заголовка
			 */
			$modx->setPlaceholder('sb.longtitle',$aCategory['title']);
		}
		/**
		 * Готовим плейсхолдеры
		 */
		$aRepl = $modx->sbshop->arrayToPlaceholders($aCategory);
		/**
		 * Делаем замену плейсхолдеров в контейнере
		 */
		$sOutput = str_replace(array_keys($aRepl), array_values($aRepl), $this->aTemplates['products_outer']);
		/**
		 * Получение списка товаров
		 */
		$aProducts = $this->oProductList->getProductList();
		/**
		 * Переменная для сбора информации о рядах
		 */
		$sRows = '';
		/**
		 * Если есть записи
		 */
		if(count($aProducts) > 0) {
			/**
			 * Обрабатываем каждую запись для вывода
			 */
			foreach ($aProducts as $oProduct) {
				/**
				 * Подготавливаем информацию для вставки в шаблон
				 */
				$aRepl = $modx->sbshop->arrayToPlaceholders($oProduct->getAttributes());
				/**
				 * Получаем набор характеристик
				 */
				$aAttributes = $oProduct->getExtendAttributes();
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
				 * Добавляем изображения
				 */
				$aRepl = array_merge($aRepl,$modx->sbshop->multiarrayToPlaceholders($oProduct->getAllImages(),'num','sb.image.'));
				$sRows .= str_replace(array_keys($aRepl),array_values($aRepl),$this->aTemplates['products_row']);
			}
		}
		/**
		 * Вставляем информацию о рядах в контейнер
		 */
		$sOutput = str_replace('[+sb.wrapper+]', $sRows, $sOutput);
		/**
		 * Отдаем результат
		 */
		return $sOutput;
	}

	/**
	 * Вывод вложенных в категорию товаров
	 * @param <type> $iProductId
	 */
	public function outputInnerProducts($iCatId) {
		global $modx;
		/**
		 * Получаем лимит количества товаров на категорию
		 */
		$iLimit = $modx->sbshop->config['innercat_products'];
		/**
		 * Если лимит равен 0
		 */
		if($iLimit == 0) {
			/**
			 * Возвращаем пустоту
			 */
			return '';
		}
		/**
		 * Переменная для вывода. Забрасываем туда шаблон
		 */
		$sOutput = $this->aTemplates['products_outer'];
		/**
		 * Получаем список товаров
		 */
		$oProducts = new SBProductList($iCatId,false,$iLimit);
		/**
		 * Получение списка товаров
		 */
		$aProducts = $oProducts->getProductList();
		/**
		 * Переменная для сбора информации о рядах
		 */
		$sRows = '';
		/**
		 * Если есть записи
		 */
		if(count($aProducts) > 0) {
			/**
			 * Обрабатываем каждую запись для вывода
			 */
			foreach ($aProducts as $oProduct) {
				/**
				 * Подготавливаем информацию для вставки в шаблон
				 */
				$aRepl = $modx->sbshop->arrayToPlaceholders($oProduct->getAttributes());
				/**
				 * Получаем набор характеристик
				 */
				$aAttributes = $oProduct->getExtendAttributes();
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
				 * Добавляем изображения
				 */
				$aRepl = array_merge($aRepl,$modx->sbshop->multiarrayToPlaceholders($oProduct->getAllImages(),'num','sb.image.'));
				/**
				 * Делаем подстановку
				 */
				$sRows .= str_replace(array_keys($aRepl),array_values($aRepl),$this->aTemplates['products_row']);
			}
		}
		/**
		 * Вставляем информацию о рядах в контейнер
		 */
		$sOutput = str_replace('[+sb.wrapper+]', $sRows, $sOutput);
		/**
		 * Отдаем результат
		 */
		return $sOutput;
	}
}

?>