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
 * Экшен сниппета электронного магазина: Вывод краткой информации корзины
 * 
 */

class cart_mode {
	/**
	 * Объект заказа
	 * @var SBOrder
	 */
	protected $aTemplates; // Массив с набором шаблонов
	
	/**
	 * Конструктор
	 */
	public function __construct() {
		global $modx;
		/**
		 * Если передан запрос на переход к оформлению
		 */
		if(isset($_POST['sb_order_next'])) {
			/**
			 * Редиректим
			 */
			$this->redirectNext();
		}
		/**
		 * Если передана информация на удаление товара
		 */
		if(isset($_POST['sb_order_remove'])) {
			/**
			 * Удаляем
			 */
			$this->deleteCart();
		}
		/**
		 * Если пришла информация через POST для добавления в корзину
		 */
		if(isset($_POST['sb_order_add'])) {
			/**
			 * Передаем на сохранение нового заказа
			 */
			$this->saveCart();
		} elseif(isset($_POST['sb_order_clear'])) {
			/**
			 * Очищаем заказ
			 */
			$this->clearCart();
		}
		/**
		 * Получаем шаблоны
		 */
		$this->setTemplates();
		/**
		 * Вывод корзины товаров
		 */
		$modx->setPlaceholder('sb.cart',$this->outputCart());
	}
	
	/**
	 * Обработка полученной ифнормации для заказа
	 */
	public function saveCart() {
		global $modx;
		/**
		 * Проверяем все данные на безопасность
		 */
		if($this->checkData()) {
			/**
			 * Сохраняем результат
			 */
			$modx->sbshop->oOrder->save();
		}
		
	}

	/**
	 * Очищаем корзину
	 */
	public function clearCart() {
		global $modx;
		$modx->sbshop->oOrder->clear();
	}

	/**
	 * Проверка полученных данных.
	 */
	public function checkData() {
		global $modx;
		/**
		 * Флаг указывающий на ошибку
		 */
		$bError = false;
		/**
		 * Проверяем идентификаторо заказываемого товара
		 */
		if(isset($_POST['sb_order_add'])) {
			/**
			 * Если идентификатор соответствует определенному формату
			 */
			if(preg_match('/^\d+(:[\d\.]+\d+)*$/', $_POST['sb_order_add'])) {
				/**
				 * Используем его
				 */
				$iProductId = $_POST['sb_order_add'];
			} else {
				/**
				 * Выделяем идентификатор как целое число
				 */
				$iProductId = intval($_POST['sb_order_add']);
			}
			/**
			 * Обрабатываем полученные параметры выбора товара. Это должен быть массив.
			 */
			if(isset($_POST['sbprod']) and is_array($_POST['sbprod'])) {
				/**
				 * Массив значений для добавления
				 */
				$aSets = array();
				/**
				 * Обрабатываем каждый элемент
				 */
				foreach ($_POST['sbprod'] as $sKey => $sVal) {
					/**
					 * Если значение касается количества
					 */
					if($sKey == 'quantity') {
						/**
						 * Прибавляем количество
						 */
						$aSets['quantity'] = intval($sVal);
					/**
					 * Для дополнительных опций
					 */
					} elseif($sKey == 'sboptions') {
						/**
						 * Проверяем каждое значение
						 */
						foreach ($sVal as $sOptKey => $sOptVal) {
							/**
							 * Заносим в общий массив значений переводя все значения в числа
							 */
							$aSets['sboptions'][intval($sOptKey)] = intval($sOptVal);
						}
					}
				}
				/**
				 * Заносим значения в заказ
				 */
				$modx->sbshop->oOrder->addProduct($iProductId,$aSets);
			} else {
				$bError = true;
			}
		} else {
			$bError = true;
		}
		return !$bError;
	}

	/**
	 * Получение набора шаблонов
	 */
	public function setTemplates() {
		global $modx;
		/**
		 * Загружаем стандартный файл с шаблонами
		 */
		$sTemplate = $modx->sbshop->getSnippetTemplate('cart');
		/**
		 * Разбиваем стандартный набор шаблонов
		 */
		list($sEmpty,$sFilled,$sCartOuter,$sCartRow,$sOptOuter,$sOptRow,$sOptSeparator) = explode('<!-- ### -->',$sTemplate);
		/**
		 * Устанавливаем шаблоны
		 */
		$this->aTemplates['cart_empty'] = $sEmpty;
		$this->aTemplates['cart_filled'] = $sFilled;
		$this->aTemplates['cart_outer'] = $sCartOuter;
		$this->aTemplates['cart_row'] = $sCartRow;
		$this->aTemplates['option_outer'] = $sOptOuter;
		$this->aTemplates['option_row'] = $sOptRow;
		$this->aTemplates['option_separator'] = $sOptSeparator;
	}

	/**
	 * Вывод корзины
	 */
	public function outputCart() {
		global $modx;
		/**
		 * Инициализируем переменную для вывода результата
		 */
		$sOutput = '';
		/**
		 * Если нет товаров в корзине
		 */
		$aIds = $modx->sbshop->oOrder->getProductSetIds();
		if(count($aIds) == 0) {
			/**
			 * Выводим шаблон пустой корзины
			 */
			$sOutput = $this->aTemplates['cart_empty'];
		} else {
			/**
			 * Инициализируем массив товаров в корзине
			 */
			$aRows = array();
			/**
			 * Обрабатываем товары
			 */
			foreach ($aIds as $iSetId) {
				/**
				 * Получаем товар из списка заказа
				 */
				$oProduct = $modx->sbshop->oOrder->getProduct($iSetId);
				/**
				 * Получаем параметры товара
				 */
				$aProduct = $oProduct->getAttributes();
				/**
				 * Плесхолдеры параметров товара
				 */
				$aRepl = $modx->sbshop->arrayToPlaceholders($aProduct);
				/**
				 * Получаем информацию о количестве и прочих условиях заказа товара
				 */
				$aOrderInfo = $modx->sbshop->oOrder->getOrderSetInfo($iSetId);
				/**
				 * Добавляем плейсхолдеры информации заказа
				 */
				$aRepl = array_merge($aRepl,$modx->sbshop->arrayToPlaceholders($aOrderInfo));
				/**
				 * Делаем рассчет цены товара
				 */
				$aRepl['[+sb.price+]'] = $aProduct['price'] + $aOrderInfo['options_price'];
				/**
				 * Идентификатор набора товара
				 */
				$aRepl['[+sb.set_id+]'] = $iSetId;
				/**
				 * Если установлены опции в товаре
				 */
				$aOptions = array();
				if(isset($aOrderInfo['sboptions']) and count($aOrderInfo['sboptions']) > 0) {
					foreach ($aOrderInfo['sboptions'] as $sOptKeyId => $sOptValId) {
						$aOptRepl = $modx->sbshop->arrayToPlaceholders($oProduct->getNamesByNameIdAndValId($sOptKeyId,$sOptValId));
						$aOptions[] = str_replace(array_keys($aOptRepl), array_values($aOptRepl), $this->aTemplates['option_row']);
					}
					/**
					 * Объединяем ряды и вставляем в контейнер
					 */
					$sOptions = str_replace('[+sb.wrapper+]', implode($this->aTemplates['option_separator'],$aOptions), $this->aTemplates['option_outer']);
					$aRepl['[+sb.options+]'] = $sOptions;
				} else {
					$aRepl['[+sb.options+]'] = '';
				}
				/**
				 * Вставляем данные в шаблон
				 */
				$aRows[] = str_replace(array_keys($aRepl), array_values($aRepl), $this->aTemplates['cart_row']);
			}
			/**
			 * Вставляем ряды в контейнер списка товаров
			 */
			$sOutput = str_replace('[+sb.wrapper+]', implode($aRows), $this->aTemplates['cart_outer']);
			/**
			 * Подготавливаем плейсхолдеры для общего контейнера корзины
			 */
			$aRepl = $modx->sbshop->arrayToPlaceholders($modx->sbshop->oOrder->getAttributes());
			/**
			 * Добавляем ссылку на оформление заказа
			 */
			$aRepl['[+sb.link_checkout+]'] = $modx->sbshop->sBaseUrl . 'checkout' . $modx->sbshop->config['url_suffix'];
			/**
			 * Добавляем сформированные данные
			 */
			$aRepl['[+sb.wrapper+]'] = $sOutput;
			/**
			 * Вставляем список товаров в контейнер корзины
			 */
			$sOutput = str_replace(array_keys($aRepl), array_values($aRepl), $this->aTemplates['cart_filled']);
		}
		return $sOutput;
	}

	/**
	 * Редирект на следующий шаг
	 */
	public function redirectNext() {
		global $modx;
		/**
		 * Адрес редиректа
		 */
		$sRedirect = $modx->sbshop->sBaseUrl . 'checkout' . $modx->sbshop->config['url_suffix'];
		/**
		 * Отправляем
		 */
		$modx->sendRedirect($sRedirect);
		exit;
	}

	/**
	 * Удаление товаров из заказа
	 */
	public function deleteCart() {
		global $modx;
		/**
		 * Удаляем выбранные товары
		 */
		$modx->sbshop->oOrder->deleteProducts($_POST['sb_order_remove']);
		/**
		 * Сохраняем результат
		 */
		$modx->sbshop->oOrder->save();
	}
}

?>