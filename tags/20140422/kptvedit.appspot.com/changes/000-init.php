<?php
$init_changes = array();
class regChanger {
	const COMMON_MODIFIERS="mu";
	var $title;	/* заголовок */
	var $parten;	/* шаблон */
	var $change;	/* замена */
	var $modifiers;	/* модификаторы */
	var $count;	/* количество замен */
	var $enabled;
	
	function execute (&$data) {
		if (!$this->enabled) return;
		$hint = htmlspecialchars($this->title, ENT_QUOTES);
		$text = "поиск — ".$this->parten."   замена — ~".$this->change."~";
		$text = strtr($text, array("\n"=>"\\n"));
		$text = htmlspecialchars ($text, ENT_QUOTES);
		
		$retval = "<ABBR title='$text'>$hint</ABBR>"; 
	
		$i = $this->count; 
		while ($i-- > 0) {
			$data = preg_replace ($this->parten, $this->change, $data);
		}
		return $retval;
	}
		
	function disable() {
		$this->enabled = false;
	}
	function enable() {
		$this->enabled = true;
	}
	function __construct($title, $parten, $change,  $modifiers="", $Count=1, $enabled = true) {
		$this->title = $title;
		$this->parten = '~'.$parten.'~'.self::COMMON_MODIFIERS.$modifiers;
		$this->change = $change;
		$this->modifiers=$modifiers;
		($enabled == true) ? $this->enable() : $this->disable;
		$this->count=$Count;
		return $this;
	}
}
$init_changes[] = new regChanger (
		'Нормализация конца строк',
		'[\r\n]+',
		"\n"
		);

$init_changes[] = new regChanger (
		'Добавить ведущий ноль',
		'^(\d\.\d{2})',
		'0\1'
		);

$init_changes[] = new regChanger (
		'Разделитель времени - точка',
		'(?<=\d\d):(?=\d\d)',
		'.'
		);
		
$init_changes[] = new regChanger (
		'Убрать Ё',
		'Ё',
		'Е'
		);
$init_changes[] = new regChanger (
		'Убрать ё',
		'ё',
		'е'
		);
		
$init_changes[] = new regChanger (
		'Добавить ведущий ноль',
		'^(\d\.\d{2})',
		'0\1'
		);

$init_changes[] = new regChanger (
		'Убрать пробелы перед точками, запятыми и т.п.',
		'^\s(?=[,.:;)!?])',
		''
		);

$doubleSpacesFix = new regChanger (
		'Убрать двойные пробелы',
		' {2,}',
		' '
		);
$init_changes[] = $doubleSpacesFix;
$init_changes[] = new regChanger (
		'Рейтинг (возраст+) в конец строки',
		'\.?\s?\(?(\d+\+)\)?(.*)$',
		'. \2 (\1)'
		);
		
$init_changes[] = new regChanger (
		'Поправка после Рейтинг (возраст+) в конец строки №1',
		'"Мама на[ .]*\(5\+\)$',
		'"Мама на 5+"', 'i'
		); 
						
$init_changes[] = new regChanger (
		'г после года',
		'(\d{4})(?! ?г)',
		'\1 г. '
		);
		
$init_changes[] = new regChanger (
		'точка после г в году',
		'\sг\b(?!\.)\s?',
		'  г. '
		);

$init_changes[] = new regChanger (
		'Убрать кавычки вокруг мульфильмов',
		'(Мультфильмы?)',
		'\1', 'i'
		);

$init_changes[] = new regChanger (
		'Объединить идущие подряд мульфильмы',
		'(?<=\d\d\.\d\d )Мультфильмы?(.*\n\d\d\.\d\d Мультфильмы?)+',
		'Мультфильмы', 'i'
		);
		/** (типликационный|Документальный) - мультипликационный */
$init_changes[] = new regChanger (
		'Мультсериал одним словом',
		'Мультипликационный сериал[^(]+',
		'Мультсериал ', 'i'
		);		
$init_changes[] = new regChanger (
		'Сериал по слову сериал с рейтингом',
		'(?<=\d\d\.\d\d )"(.*?)"[^\n]*?(?<!(Документальный)\s)\b(сериал|телесериал)[^\n]*?(\d+\+).*?$',
		'"\1". Сериал (\4)', 'i'
		);
/*$init_changes[] = new regChanger (
		'Сериал по слову сериал без рейтинга',
		'(?<=\d\d\.\d\d )"(.*?)"[^\n]*?(?<!(Документальный)\s)\b(сериал|телесериал)[^\n]*?$',
		'"\1". Сериал', 'i'
		); */
$init_changes[] = new regChanger (
		'Сериал по множеству серий с рейтингом',
		'(?<=\d\d\.\d\d )"(.*?)"[^\n]*?((?:\d{2,}|[4-9])(?:-я)?\s*сери[яйи]\b)[^\n]*?(\d+\+).*?$',
		'"\1". Сериал (\3)', 'i'
		);
/*
$init_changes[] = new regChanger (
		'Сериал по множеству серий без рейтинга',
		'(?<=\d\d\.\d\d )"(.*?)"[^\n]*?((?:\d{2,}|[4-9])(?:-я)?\s*сери[яйи]\b).*?$',
		'"\1". Сериал', 'i'
		);	
*/		
$init_changes[] = new regChanger (
		'Заменить киностудии на СССР',
		'"?(Мосфильм|Ленфильм|Одесская к/ст|Мосфильм|К/ст\. им\. М\. Горького)"?\s*[,.]',
		'СССР,', 'i'
		);

$init_changes[] = new regChanger (
		'Убрать повторы',
		'^(\d\d\.\d\d) (.*)(\n(\d\d\.\d\d) \2)+',
		"\\1 \\2", 'i', 1
		);		
$init_changes[] = new regChanger (
		'Исправление двойных точек',
		'(\. ){2,}',
		"\\1", 'i', 1
		);		
$init_changes[] = $doubleSpacesFix;

$init_changes[] = new regChanger (
		'Исправление убрать точку перед рейтингом',
		'(?<!\sг)\.(?= \()',
		"", 'i', 1
		);
$init_changes[] = new regChanger (
		'Исправление убрать точку после времени',
		'(?<=\d\d\.\d\d)\.',
		"", 'i', 1
		);

 
$init_hint = "";

foreach ($init_changes as $expr) {
	$retval = $expr->execute($input);
	if ($retval) {
		$init_hint .=  "<li>".$retval."</li>";
	}
}	


