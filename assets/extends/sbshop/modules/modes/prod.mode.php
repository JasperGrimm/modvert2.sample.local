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
 * Экшен модуля электронного магазина: Управление товарами
 * 
 */

class prod_mode {
	
	protected $sModuleLink;
	protected $sMode;
	protected $sAct;
	protected $oProduct;
	protected $oOldProduct;
	protected $oCategory;
	protected $bIsNewProduct;
	protected $sTemplate;
	protected $sError;
	
	/**
	 * Конструктор
	 * @param $sModuleLink Ссылка на модуль
	 * @param $sMode Режим работы модуля
	 * @param $sAct Выполняемое действие
	 */
	public function __construct($sModuleLink, $sMode, $sAct = '') {
		global $modx;
		/**
		 * Записываем служебную информацию модуля, чтобы делать разные ссылки
		 */
		$this->sModuleLink = $sModuleLink;
		$this->sMode = $sMode;
		$this->sAct = $sAct;
		/**
		 * Создаем экземляр товара
		 */
		$this->oProduct = new SBProduct();
		/**
		 * Экземпляр старого товара
		 */
		$this->oOldProduct = new SBProduct();
		/**
		 * Устанавливаем шаблон
		 */
		$this->sTemplate = $modx->sbshop->getModuleTemplate($sMode);
		/**
		 * Обнуляем содержимое информации об ошибках
		 */
		$this->sError = '';
		/**
		 * Обрабатываем заданное действие
		 */
		switch ($this->sAct) {
			case 'new':
				/**
				 * Создание нового продукта
				 */
				/**
				 * Устанавливаем флаг нового продукта
				 */
				$this->bIsNewProduct = true;
				/**
				 * Проверка отправки данных
				 */
				if(isset($_POST['ok'])) {
					/**
					 * Сохраняем
					 */
					if($this->saveProduct()) {
						$modx->sbshop->alertWait($this->sModuleLink);
					}
				} else {
					/**
					 * Устанавливаем категорию куда будет помещен товар
					 */
					$iCatId = intval($_REQUEST['catid']);
					$this->oProduct->setAttribute('category',$iCatId);
					/**
					 * Выводим форму для создания категории
					 */
					$this->newProduct();
				}
				break;
			case 'edit':
				/**
				 * Редактирование товара
				 */
				/**
				 * Устанавливаем флаг нового товара
				 */
				$this->bIsNewProduct = false;
				/**
				 * Проверка отправки данных
				 */
				if(isset($_POST['ok'])) {
					/**
					 * Сохраняем
					 */
					if($this->saveProduct()) {
						$modx->sbshop->alertWait($this->sModuleLink);
					}
				} else {
					/**
					 * Делаем загрузку информации о товаре
					 */
					$iProdId = intval($_REQUEST['prodid']);
					$this->oProduct->load($iProdId, true);
					$this->editProduct();
				}
				break;
			case 'pub':
				/**
				 * Публикация товара
				 */
				$iProdId = intval($_REQUEST['prodid']);
				$this->publicProduct($iProdId);
				$modx->sbshop->alertWait($this->sModuleLink);
				break;
			case 'unpub':
				/**
				 * Снятие публикации товара
				 */
				$iProdId = intval($_REQUEST['prodid']);
				$this->unpublicProduct($iProdId);
				$modx->sbshop->alertWait($this->sModuleLink);
				break;
			case 'del':
				/**
				 * Удаление товара
				 */
				$iProdId = intval($_REQUEST['prodid']);
				$this->delProduct($iProdId);
				$modx->sbshop->alertWait($this->sModuleLink);
				break;
			case 'undel':
				/**
				 * Восстановление товара
				 */
				$iProdId = intval($_REQUEST['prodid']);
				$this->undelProduct($iProdId);
				$modx->sbshop->alertWait($this->sModuleLink);
				break;
		}
	}
	
	/**
	 * Создание продукта. Псевдоним для editProduct()
	 */
	public function newProduct() {
		$this->editProduct();
	}
	
	/**
	 * Подготовка информации для редактирования
	 */
	public function editProduct() {
		global $modx, $_style, $_lang;
		/**
		 * Получаем шаблон
		 */
		$sTemplate = $this->sTemplate;
		/**
		 * Объединяем системный и модульный языковой массив
		 */
		$aLang = array_merge($_lang, $modx->sbshop->lang);
		/**
		 * Подготавливаем языковые плейсхолдеры
		 */
		$phLang = $modx->sbshop->arrayToPlaceholders($aLang,'lang.');
		/**
		 * Подготавливаем стилевые плейсхолдеры
		 */
		$phStyle = $modx->sbshop->arrayToPlaceholders($_style,'style.');
		/**
		 * Подготавливаем плейсхолдеры данных продукта
		 */
		$aModule = $this->oProduct->getGeneralAttributes();
		$phModule = $modx->sbshop->arrayToPlaceholders($aModule,'product.');
		/**
		 * Специально устанавливаем плейсхолдер для галочки опубликованности
		 */
		if($this->oProduct->getAttribute('published') == 1) {
			$phModule['[+product.published+]'] = 'checked="checked"';
		} else {
			$phModule['[+product.published+]'] = '';
		}
		/**
		 * Если есть информация об ошибках, то выводим через плейсхолдер [+product.error+]
		 */
		if($this->sError) {
			$phModule['[+product.error+]'] = '<div class="error">' . $this->sError . '</div>';
		} else {
			$phModule['[+product.error+]'] = '';
		}
		/**
		 * Служебные плейсхолдеры для модуля 
		 */
		$phModule['[+module.link+]'] = $this->sModuleLink;
		$phModule['[+module.act+]'] = $this->sAct;
		/**
		 * Подготавливаем плейсхолдеры вспомогательного список параметров
		 * XXX: Предстоит переделать
		 */
		$aAttrTip = SBAttributeCollection::getAttributeCategoryTip($this->oProduct->getAttribute('category'));
		$phModule['[+category.attribute_tips+]'] = 'Предлагаемые параметры: <ul><li>' . implode('</li><li>',$aAttrTip) . '</li></ul>';
		/**
		 * Объединяем все плейсхолдеры
		 */
		$phData = array_merge($phLang,$phStyle,$phModule);
		/**
		 * Делаем замену плейсхолдеров
		 */
		$sTemplate = str_replace(array_keys($phData),array_values($phData),$sTemplate);
		/**
		 * Выводим информацию
		 */
		echo $sTemplate;
	}
	
	/**
	 * Публикация товара
	 * @param unknown_type $iProdId
	 */
	public function publicProduct($iProdId = 0) {
		/**
		 * Если идентификатор неверный, то выходим
		 */
		if($iProdId == 0) {
			return false;
		}
		// Загружаем информацию о товаре
		$this->oProduct->load($iProdId,true);
		/**
		 * Если товар не опубликован
		 */
		if($this->oProduct->getAttribute('published') == 0) {
			/**
			 * Устанавливаем значение опубликованности
			 */
			$this->oProduct->setAttribute('published',1);
			/**
			 * Задаем дату модификации
			 */
			$this->oProduct->setAttribute('date_edit',date('Y-m-d G:i:s'));
			/**
			 * Сохраняем результат
			 */
			$this->oProduct->save();
		}
	}
	
	/**
	 * Отмена публикация товара
	 * @param unknown_type $iProdId
	 */
	public function unpublicProduct($iProdId = 0) {
		/**
		 * Если идентификатор неверный, то выходим
		 */
		if($iProdId == 0) {
			return false;
		}
		// Загружаем информацию о товаре
		$this->oProduct->load($iProdId,true);
		/**
		 * Если товар опубликован
		 */
		if($this->oProduct->getAttribute('published') == 1) {
			/**
			 * Снимаем значение опубликованности
			 */
			$this->oProduct->setAttribute('published',0);
			/**
			 * Задаем дату модификации
			 */
			$this->oProduct->setAttribute('date_edit',date('Y-m-d G:i:s'));
			/**
			 * Сохраняем результат
			 */
			$this->oProduct->save();
		}
	}
	
	/**
	 * Удаление товара в корзину
	 * @param $iProdId
	 */
	public function delProduct($iProdId = 0) {
		/**
		 * Если идентификатор неверный, то выходим
		 */
		if($iProdId == 0) {
			return false;
		}
		// Загружаем информацию о товаре
		$this->oProduct->load($iProdId,true);
		/**
		 * Если товар не удален
		 */
		if($this->oProduct->getAttribute('deleted') == 0) {
			/**
			 * Помечаем на удаление
			 */
			$this->oProduct->setAttribute('deleted',1);
			/**
			 * Задаем дату модификации
			 */
			$this->oProduct->setAttribute('date_edit',date('Y-m-d G:i:s'));
			/**
			 * Сохраняем результат
			 */
			$this->oProduct->save();
		}
	}
	
	/**
	 * Восстановление товара из корзины
	 * @param $iProdId
	 */
	public function undelProduct($iProdId) {
		/**
		 * Если идентификатор неверный, то выходим
		 */
		if($iProdId == 0) {
			return false;
		}
		// Загружаем информацию о товаре
		$this->oProduct->load($iProdId,true);
		/**
		 * Если товар удален
		 */
		if($this->oProduct->getAttribute('deleted') == 1) {
			/**
			 * Убираем пометку на удаление
			 */
			$this->oProduct->setAttribute('deleted',0);
			/**
			 * Задаем дату модификации
			 */
			$this->oProduct->setAttribute('date_edit',date('Y-m-d G:i:s'));
			/**
			 * Сохраняем результат
			 */
			$this->oProduct->save();
		}
	}
	
	/**
	 * Обработка полученной информации и сохранение
	 */
	public function saveProduct() {
		global $modx;
		/**
		 * Делаем проверку значений и устанавливаем для текущего товара
		 */
		if($this->checkData()) {
			/**
			 * Загружаем старые данные товара
			 */
			if(!$this->bIsNewProduct) {
				$this->oOldProduct->load($this->oProduct->getAttribute('id'));
			}
			/**
			 * Проверка прошла успешно и объект содержит все нужные данные. Просто сохраняем их.
			 */
			$this->oProduct->save();
			/**
			 * Если товар новый, то нужно еще установить URL
			 * Делается это после сохранения, так как нам нужен идентификатор на случай, если псевдоним не установлен
			 */
			if($this->bIsNewProduct) {
				$sAlias = $this->oProduct->getAttribute('alias');
				if(!$sAlias) {
					$sAlias = $this->oProduct->getAttribute('id');
				}
				/**
				 * Формируем URL с учетов части из категории
				 */
				$sUrl = $this->oCategory->getAttribute('url') . '/' . $sAlias;
				$this->oProduct->setAttribute('url',$sUrl);
			} else {
				/**
				 * А если старая, то необходимо добавить дату редактирования
				 */
				$this->oProduct->setAttribute('date_edit',date('Y-m-d G:i:s'));
			}
			/**
			 * Обрабатываем изображения здесь, так как нам важно знать идентификатор товара
			 */
			if(isset($_FILES['img'])) {
				/**
				 * Массив изображений
				 */
				$aImgs = array();
				/**
				 * Обрабатываем каждый полученный файл
				 */
				foreach ($_FILES['img']['tmp_name'] as $sSrc) {
					/**
					 * Если файл нормально загружен
					 */
					if(is_uploaded_file($sSrc)) {
						/**
						 * Определяем базовую директорию для изображений
						 */
						$sBasePath = $modx->sbshop->config['image_base_dir'] . $this->oProduct->getAttribute('id') . '/';
						/**
						 * Делаем ресайз
						 */
						$aImgs[] = SBImage::imageResize($sSrc, $sBasePath, $modx->sbshop->config['image_resizes']);
					}
				}
				/**
				 * Устанавливаем информацию об изображениях
				 */
				$this->oProduct->setAttribute('images',implode('||', $aImgs));
			}
			/**
			 * Снова сохраняем.
			 */
			$this->oProduct->save();
			/**
			 * Делаем обобщение параметров
			 */
			SBAttributeCollection::attributeProductGeneralization($this->oProduct->getAttribute('id'),$this->oProduct->getAttribute('category'),$this->oProduct->getExtendAttributes(),$this->oOldProduct->getExtendAttributes());
			return true;
		} else {
			/**
			 * Что-то при проверке пошло не так, поэтому снова выводим форму
			 */
			$this->editProduct();
			return false;
		}
	}
	
	/**
	 * Проверка полученных из формы данных
	 */
	protected function checkData() {
		global $modx;
		
		$bError = false;
		/**
		 * Установка идентификатора
		 */
		if(intval($_POST['prodid']) > 0) {
			$this->oProduct->setAttribute('id',intval($_POST['prodid']));
			/**
			 * Указываем флаг, что товар не новый, а редактируется
			 */
			$this->bIsNewProduct = false;
		} else {
			/**
			 * Товар новый, нужно установить флаг
			 */
			$this->bIsNewProduct = true;
		}
		/**
		 * Установка идентификатора категории
		 */
		$iCategoryId = intval($_POST['catid']);
		if($iCategoryId > 0) {
			/**
			 * Устанавливаем идентификатор категории
			 */
			$this->oProduct->setAttribute('category',$iCategoryId);
			/**
			 * Загружаем информацию о категории
			 */
			$oCategory = new SBCategory();
			$oCategory->load($iCategoryId);
			/**
			 * Добавляем информацию о категории для текущего товара
			 */
			$this->oCategory = $oCategory;
		} else {
			$this->sError = $modx->sbshop->lang['product_error_category'];
			$bError = true;
		}
		/**
		 * Проверяем псевдоним. Он должен быть стандартным.
		 */
		if($_POST['alias'] == '' || preg_match('/^[\w\-\_]+$/i',$_POST['alias'])) {
			$this->oProduct->setAttribute('alias',$_POST['alias']);
			/**
			 * Для дальнейшей установки URL выделим переменную
			 */
			if($_POST['alias'] == '') {
				/**
				 * Подключаем класс плагина TransAlias
				 */
				require_once MODX_BASE_PATH . 'assets/plugins/transalias/transalias.class.php';
				$oTrans = new TransAlias();
				$oTrans->loadTable($modx->sbshop->config['transalias_table_name'], $modx->sbshop->config['transalias_remove_periods']);
				/**
				 * Получаем алиас на основе заголовка
				 */
				$sAlias = $oTrans->stripAlias($_POST['title'],$modx->sbshop->config['transalias_char_restrict'],$modx->sbshop->config['transalias_word_separator']);
			} else {
				/**
				 * Псевдоним задан, его и берем
				 */
				$this->oProduct->setAttribute('alias',$_POST['alias']);
				$sAlias = $_POST['alias'];
			}
			$this->oProduct->setAttribute('alias',$sAlias);
		} else {
			$this->sError = $modx->sbshop->lang['product_error_alias'];
			$bError = true;
		}
		/**
		 * Устанавливаем URL товара с учетом URL категории.
		 * Для нового товара здесь не получится установить URL, так как идентификатор не известен
		 */
		if(!$this->bIsNewProduct) {
			/**
			 * Это не новый товар, можно смело формировать URL
			 */
			$sUrl = $oCategory->getAttribute('url') . '/' . $sAlias;
			/**
			 * Устанавливаем параметр URL
			 */
			$this->oProduct->setAttribute('url',$sUrl);
		}
		/**
		 * Проверяем заголовок. Он должен быть.
		 */
		if(strlen($modx->db->escape($_POST['title'])) > 0) {
			//$sTitle = htmlentities($modx->db->escape($_POST['title']),'UTF-8');
			$sTitle = $modx->db->escape($_POST['title']);
			$this->oProduct->setAttribute('title',$sTitle);
		} else {
			$this->sError = $modx->sbshop->lang['product_error_title'];
			$bError = true;
		}
		/**
		 * Проверяем артикул
		 */
		if(strlen($modx->db->escape($_POST['sku'])) > 0) {
			$this->oProduct->setAttribute('sku',$modx->db->escape($_POST['sku']));
		}
		/**
		 * Устанавливаем цену, заменяя предварительно запятую на точку
		 */
		$sPrice = $_POST['price'];
		$sPrice = str_replace(',','.',$sPrice);
		$this->oProduct->setAttribute('price',floatval($sPrice));
		/**
		 * Категория опубликована?
		 */
		if($_POST['published'] == 1) {
			$this->oProduct->setAttribute('published',1);
		} else {
			$this->oProduct->setAttribute('published',0);
		}
		/**
		 * Устанавливаем содержимое
		 */
		$this->oProduct->setAttribute('description',$_POST['description']);
		/**
		 * Разбираем параметры
		 */
		if($_POST['attributes'] != '') {
			/**
			 * Делаем десериализацию
			 */
			$this->oProduct->unserializeAttributes($_POST['attributes']);
			/**
			 * Актуализируем коллекцию параметров.
			 * Передаем только названия
			 */
			SBAttributeCollection::setAttributeCollection(array_keys($this->oProduct->getExtendAttributes()));
		}
		/**
		 * Установка параметров
		 */
		$this->oProduct->setAttribute('attributes',$_POST['attributes']);
		/**
		 * Если установлены опции
		 */
		if($_POST['options'] != '') {
			/**
			 * Объект управления опциями
			 */
			$oOptions = new SBOptionList();
			/**
			 * Разбираем все опции и формируем рабочий массив
			 */
			$oOptions->unserializeOptions($_POST['options']);
			/**
			 * Делаем обобщение названий опций
			 */
			$oOptions->optionNamesGeneralization($aOptions['options']);
			/**
			 * Делаем обобщение значений опций
			 */
			$oOptions->optionValuesGeneralization($aOptions['values']);
			/**
			 * Сериализуем
			 */
			$sOptions = $oOptions->serializeOptions();
			/**
			 * Устанавливаем строку для товара
			 */
			$this->oProduct->setAttribute('options',$sOptions);
		}

		return !$bError;
	}
}

?>