<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 *
 * @desription
 *
 * Электронный магазин для MODx
 *
 * Конфигурация
 */

$config = array(

	/**
	 * Режим дебагинга
	 */
	'debug' => false,
	/**
	 * Настройка списка системных режимов работы магазина
	 */
	'route_pages' => array(
		'checkout', // корзина
	),
	/**
	 * Сопоставление шаблонов для режимов
	 */
	'template_modes' => array(
		'main' => 8,
		'categories' => 8,
		'product' => 6,
		'breadcrumbs' => 5,
		'cart' => 5,
		'checkout' => 7,
	),
	/**
	 * Настройка списка возможных действий
	 */
	'snippet_params' => array(
		'page' => '/^page(\d+)$/i', // это выделит экшен для номера страницы
		'pagelist' => '/^page(\d+)-(\d+)$/i', // это выделит экшен для номера страницы
	),
	/**
	 * Список стандартных режимов работы
	 * XXX Удалить
	 */
	'default_modes' => array(
		'categories',
		'products',
	),
	/**
	 * Станартный вложенности для дерева категорий
	 */
	'cattree_level' => 3,
	/**
	 * Суфикс для URL
	 */
	'url_suffix' => '.html',
	/**
	 * Название домешней страницы для "хлебных крошек"
	 */
	'breadcrumbs_home_title' => 'Каталог',
	/**
	 * Количество товаров на страницу
	 */
	'product_per_page' => 5,
	/**
	 * Количество выводимых товаров во вложенной категории
	 */
	'innercat_products' => 3,
	/**
	 * Количество заказов на страницу
	 */
	'order_per_page' => 5,
	/**
	 * Параметры ресайза изображений
	 */
	'image_resizes' => array(
		array(
			'mode' => 'x',
			'w' => 480,
			'h' => 'N',
			'quality' => 100,
			'key' => 'x480'
		),
		array(
			'mode' => 'x',
			'w' => 228,
			'h' => 'N',
			'quality' => 100,
			'key' => 'x228'
		),
		array(
			'mode' => 'x',
			'w' => 104,
			'h' => 'N',
			'quality' => 100,
			'key' => 'x104'
		),
	),
	/**
	 * Базовая директория размещения изображений
	 */
	'image_base_dir' => MODX_BASE_PATH . 'assets/images/sbshop/',
	/**
	 * Базовый URL
	 */
	'image_base_url' => MODX_BASE_URL . 'assets/images/sbshop/',
	/**
	 * Настройки генерации псевдонима
	 */
	'transalias_table_name' => 'russian',
	'transalias_remove_periods' => 'No',
	'transalias_char_restrict' => 'legal characters',
	'transalias_word_separator' => 'dash',
	/**
	 * Управления статусами в заказе. Список доступных статусов на странице заказа
	 */
	'status_manage' => array(
		10,
		20,
		30,
		-30
	),
	/**
	 * Время после которого корзина считается брошенной
	 */
	'order_timeout' => 60 * 60 * 24,
	/**
	 * Адрес почты, куда будут приходить оповещения о новых заказах
	 */
	'notice_email' => 'mail@example.com',
);


?>