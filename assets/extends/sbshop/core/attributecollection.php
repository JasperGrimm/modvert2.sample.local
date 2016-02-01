<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 * 
 * @desription
 * 
 * Электронный магазин для MODx
 * 
 * Класс для управления общей коллекцией параметров
 */


class SBAttributeCollection {
	
	
	/**
	 * Сохранение параметров в общей коллекции 
	 * @param unknown_type $aAttribute
	 * @return Массив добавленных в коллекцию параметров
	 */
	public function setAttributeCollection($aAttributes) {
		global $modx;
		
		/**
		 * Если параметров нет, то просто выходим
		 */
		if(!$aAttributes) {
			return array();
		}
		/**
		 * Подготавливаем параметры к запросу в базу
		 */
		$aAttr = array();
		foreach ($aAttributes as $sAttribute) {
			if($sAttribute != '') {
				$aAttr[] = '\'' . $modx->db->escape($sAttribute) . '\''; 
			}
		}
		/**
		 * Объединяем параметры
		 */
		$sAttr = implode(',',$aAttr);
		/**
		 * Запрашиваем информацию из базы
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_attributes'),'attribute_name in (' . $sAttr . ')');
		$rsAttrs = $modx->db->makeArray($rs);
		/**
		 * Формируем массив имеющихся в базе параметров
		 */
		$aAttributesSetted = array();
		foreach ($rsAttrs as $rsAttr) {
			$aAttributesSetted[] = $rsAttr['attribute_name'];
		}
		/**
		 * Вычисляем параметры, которых нет в коллекции
		 */
		$aAddAttr = array_diff($aAttributes,$aAttributesSetted);
		/**
		 * если есть новые параметры
		 */
		if(count($aAddAttr) > 0) {
			/**
			 * Добавляем новые параметры в коллекцию
			 */ 
			$sql = 'INSERT INTO ' . $modx->getFullTableName('sbshop_attributes') . ' (`attribute_name`) VALUES ("' . implode('"),("',$aAddAttr) . '")';
			$rs = $modx->db->query($sql);
		}
	}
	
	/**
	 * Получение идентификаторов параметров по массиву имен
	 * @param unknown_type $aAttributes
	 */
	public function getAttributeIdsByNames($aAttributes = false) {
		global $modx;
		/**
		 * Если нет названий, то возвращаем пустой массив
		 */
		if($aAttributes == false) {
			return array();
		}
		/**
		 * Подготавливаем параметры к запросу
		 */
		$aAttr = array();
		foreach ($aAttributes as $sAttribute) {
			if($sAttribute != '') {
				$aAttr[] = '\'' . $modx->db->escape($sAttribute) . '\'';
			}
		}
		/**
		 * Объединяем параметры
		 */
		$sAttr = implode(',',$aAttr);
		/**
		 * Запрашиваем информацию из базы
		 */
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_attributes'),'attribute_name in (' . $sAttr . ')');
		$rsAttrs = $modx->db->makeArray($rs);
		/**
		 * Формируем массив идентификаторов
		 */
		$aIds = array();
		foreach ($rsAttrs as $rsAttr) {
			$aIds[$rsAttr['attribute_name']] = $rsAttr['attribute_id'];
		}
		
		return $aIds;
	}
	
	/**
	 * Установка обобщения параметров категории
	 * @param unknown_type $aNewAttributes
	 * @param unknown_type $aOldAttributes
	 */
	public function setAttributeCategoryGeneralization($iCatId,$aNewAttributes,$aOldAttributes,$aNewAttributesIds = false,$aOldAttributesIds = false) {
		global $modx;
		/**
		 * Получаем идентификаторы новых параметров
		 */
		if(!$aNewAttributeIds) {
			$aNewAttributeIds = SBAttributeCollection::getAttributeIdsByNames(array_keys($aNewAttributes));
		}
		/**
		 * Если категория новая
		 */
		if(count($aOldAttributes) == 0) {
			/**
			 * Это новая категория, значит нужны просто новые связи
			 */
			if(count($aNewAttributeIds) > 0) {
				$aAttrIds = array();
				foreach ($aNewAttributeIds as $iId) {
					$aAttrIds[] = '(' . $iCatId . ',' . $iId . ',1)';
				}
				$sAttrIds = implode(',',$aAttrIds);
				$sql = 'INSERT INTO ' . $modx->getFullTableName('sbshop_category_attributes') . ' (`category_id`,`attribute_id`,`attribute_count`) VALUES ' . $sAttrIds;
				$modx->db->query($sql);
			}
		} else {
			/**
			 * Получаем идентификаторы старых параметров
			 */
			if(!$aOldAttributeIds) {
				$aOldAttributeIds = SBAttributeCollection::getAttributeIdsByNames(array_keys($aOldAttributes));
			}
			/**
			 * Массив добавленных параметров
			 */
			$aAttrAdded = array_diff($aNewAttributeIds,$aOldAttributeIds);
			/**
			 * Массив убранных параметров
			 */
			$aAttrRemoved = array_diff($aOldAttributeIds,$aNewAttributeIds);
			/**
			 * Получение списка связей
			 */
			$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_category_attributes'),'category_id = ' . $iCatId);
			$aBinds = $modx->db->makeArray($rs);
			/**
			 * Обрабатываем все связи и получаем список имеющихся параметров
			 */
			$aAttrExisted = array();
			foreach ($aBinds as $aBind) {
				$aAttrExisted[] = $aBind['attribute_id'];
			}
			/**
			 * Выделяем из массива добавленных параметров идентификаторы, которые числятся в связях
			 * Это даст нам массив, для которого нужно просто увеличить счетчик
			 */
			$aAttrUpd = array_intersect($aAttrAdded,$aAttrExisted);
			/**
			 * Делаем обновление
			 */
			if(count($aAttrUpd) > 0) {
				$sql = 'UPDATE ' . $modx->getFullTableName('sbshop_category_attributes') . ' SET attribute_count = attribute_count + 1 WHERE category_id = ' . $iCatId . ' and attribute_id in (' . implode(',',$aAttrUpd) . ')';
				$modx->db->query($sql);
			}
			/**
			 * Выделяем из добавленных параметров абсолютно новые, которых нет в связях
			 * Это даст нам массив для, которого нужно создать новые связи
			 */
			$aAttrIns = array_diff($aAttrAdded,$aAttrExisted);
			/**
			 * Добавляем связи
			 */
			if(count($aAttrIns) > 0) {
				$aAttrIds = array();
				foreach ($aAttrIns as $iId) {
					$aAttrIds[] = '(' . $iCatId . ',' . $iId . ',1)';
				}
				$sAttrIds = implode(',',$aAttrIds);
				$sql = 'INSERT INTO ' . $modx->getFullTableName('sbshop_category_attributes') . ' (`category_id`,`attribute_id`,`attribute_count`) VALUES ' . $sAttrIds;
				$modx->db->query($sql);
			}
			/**
			 * Уменьшаем значения счетчика для убранных параметров
			 */
			if(count($aAttrRemoved) > 0) {
				$sql = 'UPDATE ' . $modx->getFullTableName('sbshop_category_attributes') . ' SET attribute_count = attribute_count - 1 WHERE category_id = ' . $iCatId . ' and attribute_id in (' . implode(',',$aAttrRemoved) . ')';
				$modx->db->query($sql);
			}
			/**
			 * Удаляем параметры с обнуленным счетчиком
			 */
			$modx->db->delete($modx->getFullTableName('sbshop_category_attributes'),'attribute_count = 0');
		}
	}
	
	/**
	 * Установка обобщения параметров товаров 
	 * @param unknown_type $iProdId
	 * @param unknown_type $iCatId
	 * @param unknown_type $aNewAttributes
	 * @param unknown_type $aOldAttributes
	 */
	public function attributeProductGeneralization($iProdId,$iCatId,$aNewAttributes,$aOldAttributes) {
		global $modx;
		/**
		 * Получаем идентификаторы новых параметров 
		 */
		$aNewAttributeIds = SBAttributeCollection::getAttributeIdsByNames(array_keys($aNewAttributes));
		/**
		 * Если товар новый
		 */
		if(count($aOldAttributes) == 0) {
			/**
			 * Это новый товар, нужно просто сохранить значения
			 */
			if(count($aNewAttributeIds) > 0) {
				$aAttrIds = array();
				foreach ($aNewAttributeIds as $sKey => $iId) {
					$aAttrIds[] = '(' . $iProdId . ',' . $iId . ',"' . $aNewAttributes[$sKey] . '")';
				}
				$sAttrIds = implode(',',$aAttrIds);
				$sql = 'INSERT INTO ' . $modx->getFullTableName('sbshop_product_attributes') . ' (`product_id`,`attribute_id`,`attribute_value`) VALUES ' . $sAttrIds;
				$modx->db->query($sql);
			}
		} else {
			/**
			 * Получаем идентификаторы старых параметров
			 */
			$aOldAttributeIds = SBAttributeCollection::getAttributeIdsByNames(array_keys($aOldAttributes));
			/**
			 * Получение списка связей
			 */
			$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_product_attributes'),'product_id = ' . $iProdId);
			$aBinds = $modx->db->makeArray($rs);
			/**
			 * Обрабатываем все связи и получаем список имеющихся параметров
			 */
			$aAttrExisted = array();
			foreach ($aBinds as $aBind) {
				$aAttrExisted[$aBind['attribute_id']] = $aBind['attribute_value'];
			}
			/**
			 * Выделяем из массива добавленных параметров идентификаторы, которые есть в базе
			 * Это даст нам массив для обновления
			 */
			$aAttrUpd = array_intersect($aNewAttributeIds,array_keys($aAttrExisted));
			/**
			 * Делаем обновление
			 */
			if(count($aAttrUpd) > 0) {
				/**
				 * Обрабатываем каждый элемент для обновления
				 */
				foreach ($aAttrUpd as $sKey => $sVal) {
					/**
					 * Нужно проверить не изменилось ли значение
					 */
					if($aAttrExisted[$sVal] != $aNewAttributes[$sKey]) {
						/**
						 * Значения не совпадают, поэтому их нужно обновить
						 */
						$modx->db->update(array('attribute_value'=>$aNewAttributes[$sKey]),$modx->getFullTableName('sbshop_product_attributes'),'product_id = ' . $iProdId . ' AND attribute_id = ' . $sVal);
					}
				}
			}
			/**
			 * Выделяем из добавленных параметров абсолютно новые, которых нет в связях
			 * Это даст нам массив для, которого нужно создать новые связи
			 */
			$aAttrIns = array_diff($aNewAttributeIds,array_keys($aAttrExisted));
			/**
			 * Добавляем связи
			 */
			if(count($aAttrIns) > 0) {
				$aAttrIds = array();
				foreach ($aAttrIns as $sKey => $sVal) {
					$aAttrIds[] = '(' . $iProdId . ',' . $sVal . ',"' . $aNewAttributes[$sKey] . '")';
				}
				$sAttrIds = implode(',',$aAttrIds);
				$sql = 'INSERT INTO ' . $modx->getFullTableName('sbshop_product_attributes') . ' (`product_id`,`attribute_id`,`attribute_value`) VALUES ' . $sAttrIds;
				$modx->db->query($sql);
			}
			/**
			 * Массив убранных параметров
			 */
			$aAttrRemoved = array_diff(array_keys($aAttrExisted),$aNewAttributeIds);
			/**
			 * подготавливаем на удаление
			 */
			if(count($aAttrRemoved) > 0) {
				$sAttrDel = implode(',',$aAttrRemoved);
				$modx->db->delete($modx->getFullTableName('sbshop_product_attributes'),'product_id = ' . $iProdId . ' AND attribute_id in (' . $sAttrDel . ')');
			}
		}
		/**
		 * Делаем обобщение для категории
		 */
		SBAttributeCollection::setAttributeCategoryGeneralization($iCatId,$aNewAttributes,$aOldAttributes,$aNewAttributeIds,$aOldAttributeIds);
	}
	
	/**
	 * Получить список типичных параметров для указанной категории, отсортированный по частоте использования
	 * @param unknown_type $iCatId
	 */
	public function getAttributeCategoryTip($iCatId) {
		global $modx;
		/**
		 * Получаем список параметров по связям
		 */
		$rs = $modx->db->select('b.attribute_name',$modx->getFullTableName('sbshop_category_attributes') . ' as a, ' . $modx->getFullTableName('sbshop_attributes') . 'as b','a.attribute_id = b.attribute_id AND a.category_id = ' . $iCatId,'a.attribute_count DESC');
		$aRaw = $modx->db->makeArray($rs);
		$aAttributes = array();
		foreach ($aRaw as $aItem) {
			$aAttributes[] = $aItem['attribute_name'];
		}
		return $aAttributes;
	}
}

?>