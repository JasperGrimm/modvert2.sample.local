<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 *
 * @desription
 *
 * Электронный магазин для MODx
 *
 * Класс для дерева категорий электронного магазина
 */

class SBCatTree {
	/**
	 * @var SBCategory
	 */
	protected $oParentCategory; // Идентификатор родительской категории
	protected $iLevel; // Уровень вложенности дерева
	protected $aCatTree; // Массив дерева категории
	protected $aCatTreeChildren; // Массив дерева категории
	protected $aCatTreeLevels; // Массив дерева категории

	public function  __construct($oParentCategory = false,$iLevel = false) {
		global $modx;
		/**
		 * Инициализируем основной массив
		 */
		$this->aCatTree = array();
		/**
		 * Устанавливаем уровень
		 */
		if(!$iLevel) {
			$iLevel = $modx->sbshop->config['cattree_level'];
		}
		/**
		 * если родительская категория не передана, то создаем экземпляр новой
		 */
		if(!$oParentCategory) {
			$oParentCategory = new SBCategory();
		}
		/**
		 * Получаем путь
		 */
		$sPath = $oParentCategory->getAttribute('path');
		/**
		 * Если путь не установлен
		 */
		if(!$sPath) {
			/**
			 * То устанавливаем корневую категорию
			 */
			$sPath = '0';
		}
		/**
		 * Записываем идентификатор родительской категории
		 */
		$this->oParentCategory = $oParentCategory;
		/**
		 * Делаем загрузку дерева категорий
		 */
		$this->load($sPath,$iLevel);
	}

	/**
	 * Загрузка дерева категорий
	 */
	public function load($sPath,$iLevel) {
		global $modx;
		/**
		 * Текущий уровень родительской категории
		 */
		$iStartLevel = $this->oParentCategory->getAttribute('level');
		/**
		 * Вычисляем конечный уровень
		 * XXX Здесь надо разобраться с конечным уровнем окончательно
		 */
		$iEndLevel = $iLevel + $iStartLevel;
		/**
		 * Добавляем к пути необходимую маску для поиска дочерних элементов
		 */
		$sPath .= '.%';
		$rs = $modx->db->select('*',$modx->getFullTableName('sbshop_categories'),' category_deleted = 0 AND category_published = 1 AND category_path like "' . $sPath . '" AND category_level < ' . $iEndLevel);
		$aRaws = $modx->db->makeArray($rs);
		/**
		 * Обрабатываем все записи
		 */
		foreach ($aRaws as $aRaw) {
			/**
			 * Создаем новый объект категории с идентификатором в качестве ключа
			 */
			$this->aCatTree[$aRaw['category_id']] = new SBCategory($aRaw);
			/**
			 * Отдельно выделяем информацию о дочерних категориях 
			 */
			$this->aCatTreeChildren[$aRaw['category_parent']][] = $aRaw['category_id'];
			/**
			 * Устанавливаем уровни вложенности
			 */
			$this->aCatTreeLevels[$aRaw['category_level']][] = $aRaw['category_id'];
		}
	}
	
	/**
	 * Получение информации об уровнях вложенности
	 */
	public function getCatTreeLevels() {
		return $this->aCatTreeLevels;
	}
	
	/**
	 * Получение списка параметров категории по идентификатору
	 * @param unknown_type $iId
	 */
	public function getAttributesById($iId) {
		if(isset($this->aCatTree[$iId])) {
			return $this->aCatTree[$iId]->getAttributes();
		}
	}
}


?>
