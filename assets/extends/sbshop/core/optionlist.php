<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 *
 * @desription
 *
 * Электронный магазин для MODx
 *
 * Объект списка опций
 */

class SBOptionList {

	/**
	 * Массив товарных опций
	 * Пример:
	 *   цвет[красный#24==2500;зеленый#25==3600;розовый==-500]||защитный экран[не устанавливать;маленький#27==2100;большой#28==2800]
	 */
	protected $aOptionList; // Список опций для товара
	protected $aOptionNames; // Список названий опций
	protected $aOptionValues; // Список значений опций

	/**
	 * Конструктор
	 */
	public function  __construct() {
		/**
		 * Инициализируем массив
		 */
		$this->aOptionList = array();
	}

	public function getOptionNames() {
		$aNames = array();
		if(count($this->aOptionNames) > 0) {
			foreach ($this->aOptionNames as $sKey => $sVal) {
				if($sKey !== '') {
					$aNames[] = array(
						'title' => $sKey,
						'id' => $sVal,
					);
				}
			}
		}
		return $aNames;
	}

	public function getValuesByOptionName($sName) {
		$aValues = array();
		/**
		 * Для каждого значения в опции
		 */
		foreach ($this->aOptionList[$sName] as $sKey => $sVal) {
			$aValues[$sKey] = array(
				'title' => $sKey,
				'id' => $this->aOptionValues[$sKey],
				'value' => $sVal
			);
		}
		return $aValues;
	}

	/**
	 * Получение значения по набору идентификаторов опции и значения
	 * @param <type> $iNameId
	 * @param <type> $iValueId
	 * @return <type>
	 */
	public function getValueByNameIdAndValId($iNameId,$iValueId) {
		//var_dump($this->aOptionNames,$this->aOptionValues, $this->aOptionList);
		/**
		 * Ищем название опции
		 */
		$sName = array_search($iNameId,$this->aOptionNames);
		/**
		 * Название значения опции
		 */
		$sValue = array_search($iValueId,$this->aOptionValues);
		/**
		 * Если значение существует
		 */
		return $this->aOptionList[$sName][$sValue];
	}

	/**
	 * 
	 * @param <type> $iNameId
	 * @param <type> $iValueId
	 */
	public function getNamesByNameIdAndValId($iNameId,$iValueId) {
		/**
		 * Ищем название опции
		 */
		$sName = array_search($iNameId,$this->aOptionNames);
		/**
		 * Название значения опции
		 */
		$sValue = array_search($iValueId,$this->aOptionValues);
		/**
		 * Результат
		 */
		$aResult = array (
			'title' => $sName,
			'value' => $sValue,
		);
		/**
		 * Возвращаем результат
		 */
		return $aResult;
	}

	/**
	 * Сериализация имеющихся опций
	 * @param <type> $sOptions
	 */
	public function serializeOptions() {
		/**
		 * Результирующая строка
		 */
		$sOptions = '';
		/**
		 * Массив для опций
		 */
		$aNameRows = array();
		/**
		 * Обрабатываем каждую опцию
		 */
		foreach ($this->aOptionList as $sNameKey => $aValues) {
			/**
			 * Переменная для одной опции
			 */
			$sNameRow = '';
			/**
			 * Название опции с идентификатором
			 */
			$sNameRow .= $sNameKey . '#' . $this->aOptionNames[$sNameKey];
			/**
			 * Массив значений опций
			 */
			$aValueRows = array();
			/**
			 * Обрабатываем каждое значение опции
			 */
			foreach ($aValues as $sValueKey => $sValueVal) {
				/**
				 * Все параметры значения опции
				 */
				$aValueRows[] = $sValueKey . '#' . $this->aOptionValues[$sValueKey] . '==' . $sValueVal;
			}
			/**
			 * Полная строка опции
			 */
			$aNameRows[] = $sNameRow . '[' . implode(';', $aValueRows) . ']';
		}
		/**
		 * Объединяем значения
		 */
		$sOptions = implode('||', $aNameRows);
		/**
		 * Возвращаем результат
		 */
		return $sOptions;
	}

	/**
	 * Десериализация данных по опциям
	 * @param <type> $sOptions
	 * @return <type>
	 */
	public function unserializeOptions($sOptions) {
		/**
		 * Результирующий массив опций
		 */
		$aOptions = array();
		/**
		 * Если пустое поле
		 */
		if($sOptions != '') {
			/**
			 * Разбиваем запись на отдельные параметры
			 */
			$aRows = explode('||', $sOptions);
			/**
			 * Обрабатываем каждую запись
			 */
			foreach ($aRows as $sRow) {
				/**
				 * Выделяем имя
				 */
				list($sName,$sParams) = explode('[', substr($sRow, 0, -1));
				/**
				 * Разбиваем название опции на ключ и идентификатор
				 */
				list($sNameKey,$sNameId) = explode('#', $sName);
				/**
				 * Разбиваем параметры
				 */
				$aParams = explode(';', $sParams);
				/**
				 * Обрабатываем каждый параметр
				 */
				foreach ($aParams as $sParam) {
					/**
					 * Разбиваем строку на параметр и значение
					 */
					list($sParamKey,$sParamVal) = explode('==', $sParam);
					/**
					 * Разбиваем название параметра для получения ID (если есть)
					 */
					list($sParamKey,$sParamId) = explode('#', $sParamKey);
					/**
					 * Добавляем информацию о значении опции к результату
					 */
					$aOptions['params'][$sNameKey][$sParamKey] = $sParamVal;
					/**
					 * Добавляем название опции в список
					 */
					$aOptions['options'][$sNameKey] = $sNameId;
					/**
					 * Добавляем значения опции в список
					 */
					$aOptions['values'][$sParamKey] = $sParamId;
				}
			}
			/**
			 * Запоминаем информацию о настройках опций
			 */
			$this->aOptionList = $aOptions['params'];
			/**
			 * Запоминаем названия опций
			 */
			$this->aOptionNames = $aOptions['options'];
			/**
			 * Значения опций
			 */
			$this->aOptionValues = $aOptions['values'];
		} else {
			/**
			 * Запоминаем информацию о настройках опций
			 */
			$this->aOptionList = array();
			/**
			 * Запоминаем названия опций
			 */
			$this->aOptionNames = array();
			/**
			 * Значения опций
			 */
			$this->aOptionValues = array();
		}
		/**
		 * Возвращаем результат
		 */
		return $aOptions;
	}

	/**
	 * Обобщение информации о названиях опций
	 * @param <type> $aOptions
	 */
	public function optionNamesGeneralization() {
		global $modx;
		/**
		 * Названия опций
		 */
		$aOptionNames = $this->aOptionNames;
		/**
		 * Если массив значений пуст
		 */
		if(!$aOptionNames) {
			return;
		}
		/**
		 * Запрос в базу с коллекцией опций
		 */
		$sOptionNames = '"' . implode('","', array_keys($aOptionNames)) . '"';
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_options'),'option_name in (' . $sOptionNames . ')');
		$aRows = $modx->db->makeArray($rs);
		/**
		 * Обрабатываем записи
		 */
		$aOldOptions = array();
		foreach ($aRows as $aRow) {
			$aOldOptions[$aRow['option_name']] = $aRow['option_id'];
		}
		/**
		 * Вычисляем новые опции
		 */
		$aNewOptions = array_diff_key($aOptionNames, $aOldOptions);
		/**
		 * Если есть новые опции
		 */
		if(count($aNewOptions) > 0) {
			/**
			 * Обрабатываем каждую запись
			 */
			foreach ($aNewOptions as $sKey => $aVal) {
				/**
				 * Заносим в базу
				 */
				$rs = $modx->db->insert(array('option_name' => $sKey),$modx->getFullTableName('sbshop_options'));
				/**
				 * Добавляем информацию в массив старых опций
				 */
				$aOldOptions[$sKey] = $modx->db->getInsertId();
			}
		}
		/**
		 * Запоминаем значения
		 */
		$this->aOptionNames = $aOldOptions;
		/**
		 * Возвращаем общий массив
		 */
		return $aOldOptions;
	}

	/**
	 * Обобщение информации о названиях опций
	 * @param <type> $aOptions
	 */
	public function optionValuesGeneralization() {
		global $modx;
		/**
		 * Названия значений
		 */
		$aOptionValues = $this->aOptionValues;
		/**
		 * Если массив значений пуст
		 */
		if(!$aOptionValues) {
			return;
		}
		/**
		 * Запрос в базу с коллекцией опций
		 */
		$sOptionValues = '"' . implode('","', array_keys($aOptionValues)) . '"';
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_option_values'),'value_name in (' . $sOptionValues . ')');
		$aRows = $modx->db->makeArray($rs);
		/**
		 * Обрабатываем записи
		 */
		$aOldValues = array();
		foreach ($aRows as $aRow) {
			$aOldValues[$aRow['value_name']] = $aRow['value_id'];
		}
		/**
		 * Вычисляем новые опции
		 */
		$aNewValues = array_diff_key($aOptionValues, $aOldValues);
		/**
		 * Если есть новые опции
		 */
		if(count($aNewValues) > 0) {
			/**
			 * Обрабатываем каждую запись
			 */
			foreach ($aNewValues as $sKey => $aVal) {
				/**
				 * Заносим в базу
				 */
				$rs = $modx->db->insert(array('value_name' => $sKey),$modx->getFullTableName('sbshop_option_values'));
				/**
				 * Добавляем информацию в массив старых опций
				 */
				$aOldValues[$sKey] = $modx->db->getInsertId();
			}
		}
		/**
		 * Запоминаем значения
		 */
		$this->aOptionValues = $aOldValues;
		/**
		 * Возвращаем общий массив
		 */
		return $aOldValues;
	}


}

?>
