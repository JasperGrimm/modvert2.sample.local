<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 * 
 * @desription
 * 
 * Электронный магазин для MODx
 * 
 * Класс для категории электронного магазина
 */

class SBCategory {
	
	protected $aCategoryData;
	protected $aCategoryDataKeys;
	protected $oCategoryExtendData;
	
	/**
	 * Конструктор
	 * @return unknown_type
	 */
	public function __construct($aParam = false) {
		/**
		 * Стандартный массив параметров категории
		 */
		$this->aCategoryData = array(
			'id' => null,
			'date_add' => null,
			'date_edit' => null,
			'title' => null,
			'description' => null,
			'images' => null,
			'attributes' => null,
			'views' => null,
			'published' => null,
			'deleted' => null,
			'order' => null,
			'parent' => null,
			'alias' => null,
			'path' => null,
			'level' => null,
			'url' => null,
		);
		/**
		 * Устанавливаем ключи параметров категории
		 */
		$this->aCategoryDataKeys = array_keys($this->aCategoryData);
		/**
		 * Инициализация расширенных значений
		 */
		$this->oCategoryExtendData = new SBAttributeList();
		/**
		 * Устанавливаем параметры товара по переданному массиву
		 */
		$this->setAttributes($aParam);
	}
	
	/**
	 * Установка набора параметров категории
	 * @param $aParam Массив параметров для установки
	 * @return unknown_type
	 */
	public function setAttributes($aParam = false) {
		if(is_array($aParam)) {
			foreach ($aParam as $sKey => $sVal) {
				/**
				 * Удаляем префикс category_ у ключа
				 */
				$sKey = str_replace('category_','',$sKey);
				/**
				 * Отсекаем лишние параметры
				 */
				if(in_array($sKey,$this->aCategoryDataKeys)) {
					
					$this->aCategoryData[$sKey] = $sVal;
				}
				if($sKey == 'attributes') {
					$this->unserializeAttributes($sVal);
				}
			}
		}
	}
	
	/**
	 * Установка параметра категории
	 * @param $sParamName
	 * @param $sParamValue
	 * @return unknown_type
	 */
	public function setAttribute($sParamName, $sParamValue) {
		return $this->setAttributes(array($sParamName => $sParamValue));
	}
	
	/**
	 * Установка расширенных параметров
	 * @param unknown_type $aParam
	 */
	public function setExtendAttributes($aParam = false) {
		return $this->oCategoryExtendData->setAttributes($aParam);
	}
	
	/**
	 * Получение заданного параметра категории
	 * @param $sParamName
	 * @return unknown_type
	 */
	public function getAttribute($sParamName) {
		return array_pop($this->getAttributes($sParamName));
	}
	
	/**
	 * Получение параметров категории
	 * @param $aParams
	 * @return unknown_type
	 */
	public function getAttributes($aParams = false) {
		/**
		 * Если параметры не заданы, возвращаем весь массив
		 */
		if($aParams == false) {
			return $this->aCategoryData;
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
		foreach ($aParams as $sParam) {
			if(isset($this->aCategoryData[$sParam])) {
				$aResult[$sParam] = $this->aCategoryData[$sParam];
			}
		}
		return $aResult;
	}
	
	/**
	 * Получение списка расширенных параметров категории
	 * @param unknown_type $aParams
	 */
	public function getExtendAttributes($aParams = false) {
		return $this->oCategoryExtendData->getAttributes($aParams);
	}
	
	/**
	 * Получение ключей расширенных параметров 
	 */
	public function getExtendAttributeKeys() {
		return $this->oCategoryExtendData->getAttributeKeys();
	}
	
	/**
	 * Десериализация параметров категории
	 * @param unknown_type $sParams
	 */
	public function unserializeAttributes($sParams) {
		return $this->oCategoryExtendData->unserializeAttributes($sParams);
	}
	
	/**
	 * Загрузка информации по указанной категории
	 * @param $iCategoryId
	 * @return unknown_type
	 */
	public function load($iCategoryId = false,$bDeleted = false) {
		global $modx;
		/**
		 * Делаем проверку на передачу численного значения 
		 */
		if(!$iCategoryId || $iCategoryId == 0) {
			return false;
		}
		/**
		 * Включать удаленные категории
		 */
		if($bDeleted) {
			$sDeleted = '';
		} else {
			$sDeleted = 'category_deleted = 0 AND category_published = 1 AND ';
		}
		/**
		 * Запрос информации из базы
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_categories'),$sDeleted . 'category_id='.$iCategoryId);
		$aData = $modx->db->makeArray($rs);
		/**
		 * Если есть такая категория
		 */
		if(count($aData[0]) > 0) {
			/**
			 * Подготавливаем основные параметры и заносим в массив
			 */
			foreach ($aData[0] as $sKey => $sVal) {
				$sKey = str_replace('category_','',$sKey);
				$this->aCategoryData[$sKey] = $sVal;
			}
			/**
			 * Подготавливаем дополнительные параметры
			 */
			$this->unserializeAttributes($this->aCategoryData['attributes']);
		} else {
			return false;
		}
	}
	
	/**
	 * Сохранение данных категории
	 */
	public function save() {
		global $modx;
		/**
		 * Подготавливаем основные параметры товара для сохранения
		 * Добавляем префикс 
		 */
		$aKeys = $this->aCategoryDataKeys;
		$aData = array();
		foreach ($aKeys as $sKey) {
			if($this->aCategoryData[$sKey] !== null) {
				$aData['category_' . $sKey] = $this->aCategoryData[$sKey];
			}
		}
		/**
		 * Подготавливаем дополнительные параметры для сохранения
		 */
		$aData['category_attributes'] = $this->oCategoryExtendData->serializeAttributes();
		/**
		 * Если ID есть
		 */
		$iCategoryId = $this->getAttribute('id');
		if($iCategoryId) {
			/**
			 * Делаем обновление информации о категории
			 */
			$modx->db->update($aData,$modx->getFullTableName('sbshop_categories'),'category_id=' . $iCategoryId);
		} else {
			/**
			 * Чтобы не возникало всяких фокусов, полностью исключаем параметр category_id
			 */
			unset($aData['category_id']);
			/**
			 * Добавляем новую категорию
			 */
			$modx->db->insert($aData,$modx->getFullTableName('sbshop_categories'));
			$this->setAttribute('id',$modx->db->getInsertId());
		}
	}
	
	/**
	 * Поиск категории по заданному URL
	 * Если категория найдена, то возвращает true
	 * При неправильно результате возвращает false
	 * @param $sURL
	 */
	public function searchCategoryByURL($sUrl = '') {
		global $modx;
		/**
		 * Если адрес не передан или пустой
		 */
		if($sUrl == '') {
			/**
			 * Возвращает false
			 */
			return false;
		}
		/**
		 * Делаем запрос в базу
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_categories'),'category_deleted = 0 AND category_published = 1 AND category_url = "' . $sUrl . '"');
		$aData = $modx->db->makeArray($rs);
		/**
		 * Если запись найдена среди категорий
		 */
		if(count($aData) == 1) {
			/**
			 * Устанавливаем данные категории
			 */
			$this->setAttributes($aData[0]);
			/**
			 * Все отлично
			 */
			$bResult = true;
		} else {
			/**
			 * Такой категории нет
			 */
			$bResult = false;
		}
		return $bResult;
	}
}


?>