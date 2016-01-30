<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 * 
 * @desription
 * 
 * Электронный магазин для MODx
 * 
 * Класс для управления списком параметров
 */

class SBAttributeList {
	
	protected $aAttributes;
	
	/**
	 * Конструктор
	 */
	public function __construct() {
		/**
		 * Инициализируем
		 */
		$this->aAttributes = array();
		
	}
	
	/**
	 * Установка параметров
	 * @param unknown_type $aParam
	 */
	public function setAttributes($aParams = false) {
		if(is_array($aParams)) {
			foreach ($aParams as $sKey => $sVal) {
				if($sKey != '') {
					$this->aAttributes[$sKey] = $sVal;
				}
			}
		}
	}
	
	/**
	 * Установка параметра
	 * @param unknown_type $sKey
	 * @param unknown_type $sVal
	 */
	public function setAttribute($sKey,$sVal) {
		if($sKey != '') {
			$this->aAttributes[$sKey] = $sVal;
		}
	}
	
	/**
	 * Полчение параметров
	 * @param unknown_type $aParams
	 */
	public function getAttributes($aParams = false) {
		/**
		 * Если параметры не заданы, возвращаем весь массив
		 */
		if($aParams == false) {
			return $this->aAttributes;
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
			if(isset($this->aAttributes[$sParam])) {
				$aResult[$sParam] = $this->aAttributes[$sParam];
			}
		}
		return $aResult;
	}
	
	/**
	 * Получение заданного параметра
	 * @param $sParamName
	 * @return unknown_type
	 */
	public function getAttribute($sParamName) {
		return array_pop($this->getAttributes($sParamName));
	}
	
	/**
	 * Получение ключей параметров
	 */
	public function getAttributeKeys() {
		return array_keys($this->aAttributes);
	}
	
	/**
	 * Сериализация параметров в текстовую строку
	 */
	public function serializeAttributes() {
		if(count($this->aAttributes) > 0) {
			$aResult = array();
			foreach ($this->aAttributes as $sKey => $sVal) {
				if($sVal != '') {
					$aResult[] = $sKey . '==' . $sVal;
				} else {
					$aResult[] = $sKey;
				}
			}
			return implode('||',$aResult);
		}
	}
	
	/**
	 * десериализация с параметров с заполнением массива параметров
	 * @param unknown_type $sParams
	 */
	public function unserializeAttributes($sParams = '') {
		/**
		 * Если ничего не передали, выходим
		 */
		if($sParams == '') {
			return;
		}
		/**
		 * Разбиваем строку на отдельные ключи/значения
		 * @var unknown_type
		 */
		$aParams = explode('||',$sParams);
		/**
		 * Обрабатываем каждую строку
		 */
		foreach ($aParams as $sParam) {
			list($sKey,$sVal) = explode('==',$sParam);
			$this->setAttribute($sKey,$sVal);
		}
		return $this->getAttributes();
	}

}

?>