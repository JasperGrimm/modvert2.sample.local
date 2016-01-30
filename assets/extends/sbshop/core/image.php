<?php

/**
 * @author Mukharev Maxim
 * @version 0.1a
 *
 * @desription
 *
 * Электронный магазин для MODx
 *
 * Класс для работы с изображениями электронного магазина
 */

class SBImage {

	/**
	 * Множественный ресайз изображения
	 *
	 * @param <type> $sImg Путь исходного изображения, путь
	 * @param <type> $sBaseDir Директория для сохранения изображений
	 * @param <type> $aParams Массив параметров ресайза. ['mode'],['w'],['h'],['quality']
	 * @param <type> $sMode
	 * @return <type>
	 */
	public function imageResize($sSrc,$sBaseDir,$aParams) {
		/**
		 * Если картинку не передали
		 */
		if(!$sSrc or !getimagesize($sSrc) or count($aParams) <= 0) {
			/**
			 * Убиваем файл
			 */
			unlink($sSrc);
			/**
			 * Уходим
			 */
			return false;
		}
		/**
		 * Размер оригинального изображения
		 */
		$aOriginalSizes = getimagesize($sSrc);
		/**
		 * Задаем ширину оригинального изображения
		 */
		$iOriginalWidth = $aOriginalSizes[0];
		/**
		 * Задаем высоту
		 */
		$iOriginalHeight = $aOriginalSizes[1];
		/**
		 * Тип изображения
		 */
		$sTypeImg = $aOriginalSizes['mime'];
		/**
		 * Если это JPG
		 */
		if($sTypeImg == 'image/jpeg') {
			/**
			 * Загружаем изображение
			 */
			$dOriginalImg = imagecreatefromjpeg($sSrc);
			/**
			 * Если базовой директории нет
			 */
			if(!is_dir($sBaseDir)) {
				/**
				 * Создаем директорию
				 */
				mkdir($sBaseDir, 0777, true);
			}
			/**
			 * Генерим название картинки
			 */
			$sFileName = substr(md5(uniqid(rand(),true)),0,5);
			/**
			 * Если файл существует
			 */
			while (file_exists($sBaseDir . $sFileName . $aParams[0]['key'] . '.jpg')) {
				/**
				 * Генерим новое название
				 */
				$sFileName = substr(md5(uniqid(rand(),true)),0,5);
			}
			/**
			 * Обрабатываем все размеры
			 */
			foreach ($aParams as $aParam) {
				/**
				 * Определяем режим изменения размера
				 */
				switch ($aParam['mode']) {
					case 'x':
						/**
						 * До заданной ширины
						 */
						$iCrop = $aParam['w'] / $iOriginalWidth;
						/**
						 * Задаем новую ширину и высоту
						 */
						$iNewWidth = $aParam['w'];
						$iNewHeight = $iOriginalHeight * $iCrop;
					break;
				}
				/**
				 * Создаем новое изображение
				 */
				$dNewImg = imagecreatetruecolor($iNewWidth,$iNewHeight);
				/**
				 * Помещаем в него данные
				 */
				imagecopyresampled($dNewImg, $dOriginalImg, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOriginalWidth, $iOriginalHeight);
				/**
				 * Полный путь к названию файла
				 */
				$sFullFileName = $sBaseDir . $sFileName . $aParam['key'] . '.jpg';
				/**
				 * Сохраняем изображение
				 */
				imagejpeg($dNewImg, $sFullFileName, $aParam['quality']);
				/**
				 * Очищаем память от изображения
				 */
				imagedestroy($dNewImg);
			}
			/**
			 * Очищаем память от оригинального изображения
			 */
			imagedestroy($dOriginalImg);
			/**
			 * Возвращаем название изображения
			 */
			return $sFileName;
		}
		return false;
	}

}

?>
