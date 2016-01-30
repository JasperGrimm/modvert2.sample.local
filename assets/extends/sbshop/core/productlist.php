<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 * 
 * @desription
 * 
 * Электронный магазин для MODx
 * 
 * Объект список товаров
 */

class SBProductList {
	
	protected $aProductList;
	
	/**
	 * Конструктор
	 * @param $iCatId Категория для списка
	 */
	public function __construct($iCatId = false,$aProductIds = false,$iLimit = false) {
		/**
		 * Инициализируем основной массив 
		 */
		$this->aProductList = array();
		/**
		 * Если задан список
		 */
		if(is_array($aProductIds) and (count($aProductIds) > 0)) {
			/**
			 * Делаем загрузку списка товаров по заданному массиву идентификаторов
			 */
			$this->loadListByIds($aProductIds,$iLimit);
		} else {
			/**
			 * Делаем загрузку списка товаров по категории
			 */
			$this->loadListByCategoryId($iCatId,$iLimit);
		}
	}
	
	/**
	 * Загрузка списка товаров в заданной категории
	 * @param unknown_type $iCatId
	 */
	public function loadListByCategoryId($iCatId,$iLimit = false) {
		global $modx;
		/**
		 * Если категория не определена, то просто выходим
		 */
		if(!$iCatId) {
			$iCatId = 0;
		}
		/**
		 * Количество товаров на страницу
		 * XXX разобраться с постраничной разбивкой
		 */
		$ProductPerPage = $modx->sbshop->config['product_per_page'];
		/**
		 * Получаем информацию из базы
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_products'),' product_deleted = 0 AND product_published = 1 AND product_category = ' . $iCatId,'',$iLimit);
		$aRaws = $modx->db->makeArray($rs);
		/**
		 * Устанавливаем список
		 */
		$this->setList($aRaws);
	}
	
	/**
	 * Загрузка списка товаров по заданному набору идентификаторов
	 */
	public function loadListByIds($aProductIds = false, $iLimit = false) {
		global $modx;
		/**
		 * Если список не передан, то заканчиваем
		 */
		if(!$aProductIds) {
			return false;
		}
		/**
		 * Если передано одно значение
		 */
		if(!is_array($aProductIds)) {
			/**
			 * Делаем массив с одним значением
			 */
			$aProductIds = array($aProductIds);
		}
		/**
		 * Объединяем список идентификаторов
		 */
		$sProductIds = implode(',',$aProductIds);
		/**
		 * Количество товаров на страницу
		 * XXX разобраться с постраничной разбивкой
		 */
		$ProductPerPage = $modx->sbshop->config['product_per_page'];
		/**
		 * Получаем информацию из базы
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_products'),' product_deleted = 0 AND product_published = 1 AND product_id in(' . $sProductIds . ')','',$iLimit);
		$aRaws = $modx->db->makeArray($rs);
		/**
		 * Устанавливаем список
		 */
		$this->setList($aRaws);
	}
	
	/**
	 * Установка списка по переданному массиву информации о товарах
	 * @param unknown_type $aProducts
	 */
	public function setList($aProducts) {
		/**
		 * Если найдены товары
		 */
		if(count($aProducts) > 0) {
			/**
			 * Обрабатываем каждую запись
			 */
			foreach($aProducts as $aProduct) {
				/**
				 * Добавляем в основной массив экземпляр товара
				 */
				$this->aProductList[$aProduct['product_id']] = new SBProduct($aProduct);
			}
		}
	}
	
	/**
	 * Получение списка товаров
	 */
	public function getProductList() {
		return $this->aProductList;
	}

	/**
	 * Получение  массива заданного параметра для всех товаров
	 * @param mixed $aParams
	 */
	public function getAttributes($aParams) {
		/**
		 * Если параметры не заданы, возвращаем весь массив параметров
		 */
		if($aParams == false) {
			return $this->aProductList;
		}
		/**
		 * Если передана строка, то делаем массив
		 */
		if(!is_array($aParams)) {
			$aParams = array($aParams);
		}
		/**
		 * Выбираем заданные параметры из массива
		 */
		$aResult = array();

		/**
		 * Обрабатываем каждый товар
		 * @var SBProduct
		 */
		foreach ($this->aProductList as $sKey => $oProduct) {
			$aResult[$sKey] = $oProduct->getAttributes($aParams);
		}
		return $aResult;
	}

	/**
	 * Получить данные товара из списка по идентификатору
	 * @param <type> $iId
	 */
	public function getProductAttributesById($iProductId) {
		if(isset ($this->aProductList[$iProductId])) {
			return $this->aProductList[$iProductId]->getAttributes();
		}
	}

	/**
	 * Получить товар из списка по идентификатору
	 * @param <type> $iProductId
	 * @return <type>
	 */
	public function getProductById($iProductId) {
		if(isset ($this->aProductList[$iProductId])) {
			return $this->aProductList[$iProductId];
		}
	}

	/**
	 * Удаление указанного товара по идентификатору
	 * @param <type> $iProductId
	 */
	public function deleteProduct($iProductId) {
		$this->deleteProducts(array($iProductId));
	}

	/**
	 * Удаление указанных товаров из списка
	 * @param <type> $aProductIds
	 */
	public function deleteProducts($aProductIds) {
		/**
		 * Для каждого идентификатора в массиве
		 */
		foreach ($aProductIds as $iProductId) {
			/**
			 * Если товар есть в списке
			 */
			if(isset($this->aProductList[$iProductId])) {
				/**
				 * Удаляем
				 */
				unset($this->aProductList[$iProductId]);
			}
		}
	}
}


?>