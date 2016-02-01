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
 * Экшен модуля электронного магазина: Стартовая страница модуля
 * 
 */


class home_mode {
	
	/**
	 * Конструктор
	 * @param unknown_type $sModuleLink
	 * @param unknown_type $sMode
	 * @param unknown_type $sAct
	 */
	public function __construct($sModuleLink, $sMode, $sAct = '') {
		echo '<h1>Электронный магазин</h1>
			<h2>В данный момент здесь ничего не выводится и все операции происходят непосредственно в дереве товаров. В будущем здесь будет то, что нужно.</h2>';
		echo '<p><a href="' . $sModuleLink . '&mode=order' . '">Текущие заказы</a></p>';
		echo '<p><a href="' . $sModuleLink . '&mode=order&act=arch' . '">Архив заказов</a></p>';
		echo '<p><a href="' . $sModuleLink . '&mode=order&act=trash' . '">Брошенные заказы</a></p>';
	}
	
}

?>