<?php

/**
 * Функция преобразования числового представления месяца в текстовый
 *
 * @param $num - номер месяца
 * @param bool $decline - склонять или нет
 * @param int $firstCharUpper
 * @return string
 */
function xTextMonth($num, $decline = false, $firstCharUpper = 1) {
	$num = (int)$num;

	if (!$decline) {
		$months = Array(
		    1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель', 5 => 'май', 6 => 'июнь',
            7 => 'июль', 8 => 'август', 9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь',
		);

	} else {
		$months = Array(
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня',
            7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
		);
	}

	if (!isset($months[$num])) return '';
	if (!$firstCharUpper) return $months[$num];

	return xTextUpperFirstChar($months[$num]);
}

/**
 * @param $num
 * @param bool $short
 * @return string
 */
function xDayOfWeek($num, $short = true)
{
    $days = array('', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс');
    return $days[$num];
}

/**
 * Функция преобразования первого символо строки в заглавный
 *
 * @param string $string
 */
function xTextUpperFirstChar($string, $lower = false) {
	if ($lower) $string = mb_strtolower($string, 'UTF-8');
	return mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8") . mb_substr($string, 1, mb_strlen($string), "UTF-8" );
}

/**
 * Функция приведения к нижнему регистру и выделениии заглавных букв в имени пользователя
 * @param <type> $name
 * @return <type>
 */
function xTextUserName($name) {
	$explode = explode(' ', mb_strtolower($name, 'UTF-8'));
	foreach ($explode as $k => $v) $explode[$k] = xTextUpperFirstChar($v);
	return implode(' ', $explode);
}

/**
 * Окончания существительных и прилагательных, стоящих после числительного
 * 
 * Для генерации окончания существительного в функцию 
 * должен быть передан одноуровневый массив с тремя окончаниями слова
 * Например для слова "Новость": 1 новость, 2 новости, 10 новостей
 * массив должен иметь вид: array('ь', 'и', 'ей')
 * 
 * Для генерации окончания прилагательного в функцию 
 * должен быть передан одноуровневый массив с двумя окончаниями слова
 * Например для слова "Новое": 1 новое, 2 новых
 * массив должен иметь вид: array('ое', 'ых')
 * 
 * @param int $num - число
 * @param array $ends - массив с окончаниями слов
 */
function xTextNumeralEnd($num, $ends = array()) {

	$last_digit = (int)$num%10;

	// Окончание существительного

	if (count($ends) == 3) {
		if ($num > 9 AND $num < 21) return $ends[2];
		if ($last_digit == 1) return $ends[0];
		if ($last_digit > 0 AND $last_digit < 5) return $ends[1];
		return $ends[2];

	// Окончание прилагательного

	} else {
		if ($last_digit == 1) return $ends[0];
		return $ends[1];
	}
}

/**
 * Функция конвертирования массива в UTF-8
 * @param $data
 * @param $fromCodepage
 */
function xConvertToUtf8($data = array(), $fromCodepage = false) {

	if (!$fromCodepage) return $data;
	$lambda = create_function('&$val,$key,$fromCodepage','$val = iconv($fromCodepage, "UTF-8", $val);');
	array_walk_recursive($data, $lambda, $fromCodepage);
	return $data;
}

/**
 * Преобразование числа в денежный формат
 * @param type $money
 * @return type 
 */
function xMoneyFormatSimple($money) {
	return sprintf("%01.2f", $money / 100);
}

/**
 * Преобразование стандартного отображения даты MySQL в текстовый вид
 * @param $date
 */
function xTextMysqlDate($date = '') {
	preg_match('/^(\d{4})[^\d](\d{2})[^\d](\d{2})(.*)?$/', $date, $match);
	return (int)$match[3] . ' ' . xTextMonth($match[2], 1, 0) . ' ' . $match[1] . $match[4];	
}

/**
 * Merges arrays of any dimensions, the later overwriting
 * previous keys, unless the key is numeric, in whitch case, duplicated
 * values will not be added.
 *
 * The arrays to be merged are passed as arguments to the function.
 *
 * @access public
 * @return array Resulting array, once all have been merged
 */
function mergeArrays($Arr1, $Arr2) {
	foreach($Arr2 as $key => $Value) {
		if(array_key_exists($key, $Arr1) && is_array($Value)) {
			$Arr1[$key] = MergeArrays($Arr1[$key], $Arr2[$key]);
		} else {
			$Arr1[$key] = $Value;
		}
	}
	return $Arr1;
}

/**
 * Преобразование имен массива к формату имен SQL
 * Производит замену заглавнх букв на строчные с символом подчеркивания
 * @param array $array
 * @return array 
 */
function xArrayKeysToSqlType($array = array()) {
	foreach ($array as $key => $val) {
		unset($array[$key]);
		$key = preg_replace('/([a-z]{1})([A-Z]{1})/', '\\1_\\2', $key);
		$array[strtolower($key)] = $val;
	}
	return $array;
}

//Получение даты вида dd.mm.yy из yyyy-mm-dd
function sqlDate($sqldate){
	if($sqldate!=NULL)
		return date('d.m.y', strtotime($sqldate));

}

function xMoneyFormat($money){
	$money .= '';
	if (mb_strpos($money, '.')){
		$mA = explode('.', $money);
	} else {
		$mA = array(0 => $money, 1 => '00');
	}
	return $mA[0] . ' рубл' . xTextNumeralEnd($mA[0], array('ь', 'я', 'ей')) . ' ' . $mA[1] . ' копе' . xTextNumeralEnd($mA[1], array('йка', 'йки', 'ек'));
}

function xRemoveUnderscore($string) {
	return preg_replace('/_{1,}([^_]{1})/e', 'mb_strtoupper("$1")', $string);
}

// рекурсивно зазиповываем папку
function Zip($source, $destination) {
	if (extension_loaded('zip') === true && file_exists($source) === true) {
		$zip = new ZipArchive();
		if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
			$source = realpath($source);
			if (is_dir($source) === true) {
				// проходим итератором по всем подпапкам и их файлам, добавляя их в архив
				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
				foreach ($files as $file) {
					$file = realpath($file);
					if (is_dir($file) === true) {
						$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
					} else if (is_file($file) === true) {
						$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
					}
				}
				// добавляем все файлы в корневой директории в архив
			} else if (is_file($source) === true) {
				$zip->addFromString(basename($source), file_get_contents($source));
			}
		}
		return $zip->close();
	} else {
		throw new Exception('no zip extension installed or directory not exist');
	}

	return false;
}

/**
 * возвращает те ключи из $array для которых соответствующие значения присутствуют в массиве $filter
 * 
 * @param array $array
 * @param mixed $filter
 * @author Franky Calypso franky.calypso@gmail.com
 */
function x_array_keys_filter($array, $filter) {
	if (is_scalar($filter)) {
		$return = array_keys($array, $filter);
	} else {
		$return = array();
		foreach ($array as $key => $value) {
			if (in_array($value, $filter)) $return[] = $key;
		}
	}
	return $return;
}

function subentity($entityName) {
    $entities = array(
        'category' => 'brand',
        'brand' => 'product',
        'page' => 'page',
    );

    return empty($entities[$entityName]) ? null : $entities[$entityName];
}

function pathdepth($path) {
    return count(explode('.', $path));
}