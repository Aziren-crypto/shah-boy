<?

class Unit
{
	public $y;
	public $x;
	public $img;
	public $color;
	public $dirMain;
	
	public $id;
	
	function __construct($color, $y, $x)
	{
		
		$this -> id = $GLOBALS['idNext'];
		
		$this -> color = $color;
		$this -> y = $y;
		$this -> x = $x;
		
		GameField::$matrix[$y][$x] = $this;
		GameField::$arrObject[$this -> id] = $this;
		if(isset($GLOBALS['idNext']))
			$GLOBALS['idNext'] ++;
		
		$this -> dirMain = Party :: $direction[$this -> color];
		//$this -> img = 'img/'.$this -> color.'/'.$this -> $img;
	}
	
	public function MoveWithoutTest(){
		GameField::$matrix[$this -> y][$this -> x] = false;
	}
}

class Tank extends Unit
{
	
	/*
	https://gest.livejournal.com/889387.html
	Танк неуязвим для пехоты, кавалерии и пулемётов. 
	Танк. С танком всё просто. Танк ходит на одну или на две клетки по диагонали, вертикали или горизонтали. Обратите внимание, что танк способен уничтожить выдвинутую в поле артиллерию, зайдя ей в тыл - двигается он быстрее, ход на две клетки позволяет ему проскочить сквозь зону поражения, и в случае атаки с тыла артиллерия беззащитна. Ну а пулемёты и пехоту танк просто давит.

	*/
	public $text = 'С танком всё просто. Танк ходит на одну или на две клетки по диагонали, вертикали или горизонтали. Танк неуязвим для пехоты, кавалерии и пулемётов. Обратите внимание, что танк способен уничтожить выдвинутую в поле артиллерию, зайдя ей в тыл - двигается он быстрее, ход на две клетки позволяет ему проскочить сквозь зону поражения, и в случае атаки с тыла артиллерия беззащитна. Ну а пулемёты и пехоту танк просто давит.';
	public $name = 'tank';
	//public $img = 'tank.png';
	public $controllable = true;
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);	
		$this -> img = 'img/'.$this -> color.'/tank.png';
	}
	
	function getVariants(){
		$result = [];
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> AddVariantField($result, $dir);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}
	
	function AddVariantField(&$result, $dir){
		$dirProps = Direction :: $properties[$dir];
		$field = GameField :: $matrix[$this -> y + $dirProps['y']][$this -> x + $dirProps['x']];

		/*echo '<pre>GameField::$matrix: ';
		print_r(GameField::$matrix);
		echo '</pre>';*/
		
		if(is_object($field)){
			if($field -> color != $this -> color && get_class($field) != 'Plane'){
				$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'crush'];
			}else{
				
			}
		}else{
			$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];

			if(!is_object(GameField :: $matrix[$this -> y + $dirProps['y'] + $dirProps['y']][$this -> x + $dirProps['x'] + $dirProps['x']])){
				$result[] = ['y' => $this -> y + $dirProps['y'] + $dirProps['y'], 'x' => $this -> x + $dirProps['x'] + $dirProps['x'], 'type' => 'run'];
			}elseif(GameField :: $matrix[$this -> y + $dirProps['y'] + $dirProps['y']][$this -> x + $dirProps['x'] + $dirProps['x']] -> color != $this -> color){
				$result[] = ['y' => $this -> y + $dirProps['y'] + $dirProps['y'], 'x' => $this -> x + $dirProps['x'] + $dirProps['x'], 'type' => 'crush'];
			}
		}
	}
}

class Head extends Unit
{
	/*
	https://gest.livejournal.com/889387.html
	Что касается штаба... Про него сказано, что он ходит, как король, и играет роль короля, то есть цель игры - поставить мат вражескому штабу.
	В связи с этим в сети возник спор, означает ли это, что штаб и фигуры берёт, как король? Я лично считаю, что штаб вообще не может есть вражеские фигуры, это противоречило бы духу правил. Ну представьте себе - пехотинец не может есть танк, танк не может есть самолёт, а штаб может и то, и другое? Глупо. Кто у нас армией командует, Рэмбо с зениткой? Мне кажется, логичней было бы считать, что штаб в полевом сражении слабее всех - и авиации, и танков, и пехоты, и кавалерии, поэтому он не может никого атаковать. 
	*/
	public $text = 'Что касается штаба... Про него сказано, что он ходит, как король, и играет роль короля, то есть цель игры - поставить мат вражескому штабу.
	В связи с этим в сети возник спор, означает ли это, что штаб и фигуры берёт, как король? Я лично считаю, что штаб вообще не может есть вражеские фигуры, это противоречило бы духу правил. Ну представьте себе - пехотинец не может есть танк, танк не может есть самолёт, а штаб может и то, и другое? Глупо. Кто у нас армией командует, Рэмбо с зениткой? Мне кажется, логичней было бы считать, что штаб в полевом сражении слабее всех - и авиации, и танков, и пехоты, и кавалерии, поэтому он не может никого атаковать.';
	public $img;
	public $name = 'head';
	public $controllable = true;
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/head.png';
	}
	
	function getVariants(){
		$result = [];
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> AddVariantField($result, $dir);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}
	
	function AddVariantField(&$result, $dir){
		$dirProps = Direction :: $properties[$dir];
		$field = GameField :: $matrix[$this -> y + $dirProps['y']][$this -> x + $dirProps['x']];
		
		if(is_object($field)){

		}else{
			$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];
		}
	}
}

class Plane extends Unit
{
	public $img;
	public $name = 'Plane';
	public $text = 'Наконец, самолёт. Это, скорее, штурмовик, а не дальний бомбардировщик. Он стоит рядом со штабом, занимая место ферзя, и ходит как ферзь, в любую сторону и на любое расстояние. При этом, во время хода, самолёт имеет право перепрыгнуть через дружественную фигуру, но только через одну за раз, и, повторяю, только через фигуру своего цвета. Это даёт самолёту возможность совершать боевые вылеты из глубины своих рядов, подобно фигуре пао-вао. Кстати, штурмовиком я назвал его потому, что его может сбить пехота; также самолёт уязвим для артиллерии и пулемётов, но не для танка и кавалерии. Они против него беззащитны.';
	public $controllable = true;
	
	/*
	самолёт уязвим для артиллерии и пулемётов, но не для танка и кавалерии
	
	https://gest.livejournal.com/889387.html
	Наконец, самолёт. Это, скорее, штурмовик, а не дальний бомбардировщик. Он стоит рядом со штабом, занимая место ферзя, и ходит как ферзь, в любую сторону и на любое расстояние. При этом, во время хода, самолёт имеет право перепрыгнуть через дружественную фигуру, но только через одну за раз, и, повторяю, только через фигуру своего цвета. Это даёт самолёту возможность совершать боевые вылеты из глубины своих рядов, подобно фигуре пао-вао. Кстати, штурмовиком я назвал его потому, что его может сбить пехота; также самолёт уязвим для артиллерии и пулемётов, но не для танка и кавалерии. Они против него беззащитны.
	*/
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/plane.png';
	}
	
	function getVariants(){
		
		$result = [];
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> GetVariantDir($result, $dir);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}
	
	function GetVariantDir(&$result, $dir){
		$dirProps = Direction :: $properties[$dir];

		/*echo '<pre>GameField::$matrix: ';
		print_r(GameField::$matrix);
		echo '</pre>';*/
		
		$this -> GetVariantDirField($result, $this -> y + $dirProps['y'], $this -> x + $dirProps['x'], $dirProps, 0);
		
		/*if(is_object($field) && $field -> color != $this -> color){
				$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'crush'];
		}else{
			if(is_object($field) && $field -> color == $this -> color){
				$jumping = 1;
				$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'jump'];
			}else{
				$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];

				if(!is_object(GameField :: $matrix[$this -> y + $dirProps['y'] + $dirProps['y']][$this -> x + $dirProps['x'] + $dirProps['x']])){
					$result[] = ['y' => $this -> y + $dirProps['y'] + $dirProps['y'], 'x' => $this -> x + $dirProps['x'] + $dirProps['x'], 'type' => 'run'];
				}elseif(GameField :: $matrix[$this -> y + $dirProps['y'] + $dirProps['y']][$this -> x + $dirProps['x'] + $dirProps['x']] -> color != $this -> color){
					$result[] = ['y' => $this -> y + $dirProps['y'] + $dirProps['y'], 'x' => $this -> x + $dirProps['x'] + $dirProps['x'], 'type' => 'crush'];
				}
			}
		}*/
	}
	
	function GetVariantDirField(&$result, $y, $x, $dirProps, $jumping){
		//echo '<br>GetVariantDirField';
		//global $jumping;
		//echo '<br> $jumping: '.$jumping;
		if($y >= 0 && $y <= GameField::$y && $x >= 0 && $x <= GameField::$x){
			$field = GameField :: $matrix[$y][$x];
			$abort = false;
			if(is_object($field) && $field -> color == $this -> color){	// если фигура своего цвета
				$jumping ++;
				if($jumping < 2){
					$result[] = ['y' => $y, 'x' => $x, 'type' => 'jump'];
					//$this -> GetVariantDirField($result, $y, $x, $dirProps);
				}else{
					$abort = true;
				}
			}else{
				if(!is_object($field)){
					$result[] = ['y' => $y, 'x' => $x, 'type' => 'run'];	
				}elseif($field -> color != $this -> color){// + $dirProps['y']  + $dirProps['x']
					//echo '<br>$y: '.$y.' $x: '.$x;
					//echo '<br>$field -> color: '.$field -> color.' $this -> color: '.$this -> color;
					$result[] = ['y' => $y, 'x' => $x, 'type' => 'crush'];
					$abort = true;
				}else{
					$abort = true;
				}
			}	
		}else{
			$abort = true;
		}
		if(!$abort){
			$this -> GetVariantDirField($result, $y + $dirProps['y'], $x + $dirProps['x'], $dirProps, $jumping);
		}
	}
}

class soldier extends Unit
{
	public $img;
	public $name = 'private';
	public $text = 'Пехота - царица полей. Пехотинец двигается, как король в обычных шахматах, на одну клетку в любую сторону. Это крутая, тренированная пехота. Но пехотинец не может брать вражескую фигуру во время отступления, когда он двигается назад по прямой или по диагонали, поэтому он угрожает только пяти ближайшим клеткам из восьми. Что всё равно гораздо круче, чем ходы традиционной пешки, которая не может отступать и которая угрожает только двум клеткам перед собой. Также у пехоты в "Шах-Бое" есть ещё один, особый ход - если пехотинец стоит на белой клетке, он может переместится на две клетки в любую сторону (соответственно, приземлившись на другую белую клетку), если между ним и этой клеткой нет других фигур. Не знаю, что хотел выразить этим автор. Неоднородность поля боя? Повышенную мобильность пехоты, в связи с возможностью перебрасывать её автотранспортом? В любом случае, пехотинец не может атаковать этим ходом; чтобы вступить в бой, надо "спешится".
	
	<p>А круче всего - последнее правило, связанное с пехотинцами. Пехотинец, дошедший до вражеского края доски, не превращается в другую фигуру (это разрушило бы игру). Нет, пехотинца просто снимают с доски, а вместе с ним - любую вражескую фигуру (кроме штаба, естественно) по выбору того игрока, кому он принадлежал. Именно это делает игру играбельной - всегда сохраняется возможность добиться прорыва и разменять своих пехотинцев на вражеские тяжёлые фигуры, которые невозможно было бы уничтожить иным образом. (Это, напр., артиллерия в углу для игрока без самолёта и кавалерии, или танк для игрока, оставшегося без танка, самолёта и артиллерии, и т.д.) Прорыв пехоты может опровергнуть любую оборону, выбив её важнейшие элементы. Не забывайте, что с любой белой клетки пехотинец может прыгнуть на две клетки сразу, то есть в идеале он пересекает доску всего за пять ходов.</p>';
	public $controllable = true;
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/private.png';
	}
	
	/*
	https://gest.livejournal.com/889387.html
	Пехота - царица полей. Пехотинец двигается, как король в обычных шахматах, на одну клетку в любую сторону. Это крутая, тренированная пехота. Но пехотинец не может брать вражескую фигуру во время отступления, когда он двигатется назад по прямой или по диагонали, поэтому он угрожает только пяти ближайшим клеткам из восьми. Что всё равно гораздо круче, чем ходы традиционной пешки, которая не может отступать и которая угрожает только двум клеткам перед собой. Также у пехоты в "Шах-Бое" есть ещё один, особый ход - если пехотинец стоит на белой клетке, он может переместится на две клетки в любую сторону (соответственно, приземлившись на другую белую клетку), если между ним и этой клеткой нет других фигур. Не знаю, что хотел выразить этим автор. Неоднородность поля боя? Повышенную мобильность пехоты, в связи с возможностью перебрасывать её автотранспортом? В любом случае, пехотинец не может атаковать этим ходом; чтобы вступить в бой, надо "спешится".
	
	! когда доходит до конца поля, убирает с доски любую вражескую фигуру кроме штаба
	
	*/
	
	function getVariants(){
		$result = [];
		
		$opp = Direction :: $properties[$this -> dirMain]['opposite'];
		$arrOpp = [$opp, Direction :: turnNext($opp), Direction :: turnPrev($opp)];

		GameField :: makeWhiteFields();
		if(array_search($this -> y.'_'.$this -> x, GameField :: $whiteFields) !== false){
			$ableToRun = true;
		}else{
			$ableToRun = false;
		}
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> AddVariantField($result, $dir, $arrOpp, $ableToRun);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}

	function AddVariantField(&$result, $dir, $arrOpp, $ableToRun){
		$dirProps = Direction :: $properties[$dir];
		$field = GameField :: $matrix[$this -> y + $dirProps['y']][$this -> x + $dirProps['x']];

		/*echo '<pre>GameField::$matrix: ';
		print_r(GameField::$matrix);
		echo '</pre>';*/
		
		if(is_object($field)){
			if($field -> color != $this -> color && array_search($dir, $arrOpp) === false && get_class($field) != 'Tank'){
				$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'crush'];
			}else{
				
			}
		}else{
			$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];
			if($ableToRun){
				if(!is_object(GameField :: $matrix[$this -> y + $dirProps['y'] + $dirProps['y']][$this -> x + $dirProps['x'] + $dirProps['x']])){
					$result[] = ['y' => $this -> y + $dirProps['y'] + $dirProps['y'], 'x' => $this -> x + $dirProps['x'] + $dirProps['x'], 'type' => 'run'];
				}
			}
		}
	}

}

class horse extends Unit
{
	/*
	 Не может нанести вред самолету и танку. 
	*/
	
	public $img;
	public $name = 'horse';
	public $text = '<p>Теперь, кавалерия. Она сохранила традиционный принцип ходьбы буквой L, но теперь может выбирать между ходом 1-2, 1-3 и 2-3, то есть, в терминах любителей шахматных вариантов, кавалерия объединяет в себе ходы коня, верблюда и зебры. Наша конница способна решать боевые задачи в Европе, Азии и Африке!</p>

	<p>Да, это крутая, индустриальная кавалерия. Я не зря сравнил ход кавалерии с буквой L, а не с буквой Г. По правилам "Шах-Боя", делая ход, кавалерия должна сначала пройти короткую часть своей траектории, а уже затем длинную. Она как бы совершает фланговый охват - это важно, так как кавалерия не может проходить сквозь вражеские фигуры. Это же война нового типа, оборонительные линии стали более плотными, сквозь них уже так просто не просочишься. А вот через свои войска можно по-прежнему перепрыгивать.</p>';
	public $controllable = true;
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/horse.png';
	}
	
	function getVariants(){
		$result = [];
		
		$arrD = ['u', 'd', 'r', 'l'];
		foreach($arrD as $k => $v){
			$dirProps0 = Direction :: $properties[$v];
			
			$dir1 = Direction :: turnNext(Direction :: turnNext($v));
			$dir2 = Direction :: turnPrev(Direction :: turnPrev($v));
			
			$dirProps1 = Direction :: $properties[$dir1];
			$dirProps2 = Direction :: $properties[$dir2];
					
			for($i=1; $i<=3; $i++){  // каждый позвонок
				$field = GameField :: $matrix[$this -> y  + $dirProps0['y']][$this -> x  + $dirProps0['x']];
				
				$this_y = $this -> y + $dirProps0['y'] * $i;
				$this_x = $this -> x + $dirProps0['x'] * $i;
				
				if(!(is_object($field) && $field -> color != $this -> color)){ // позвонок
					
					$abort1 = false;
					$abort2 = false;
					
					for($i2=0; $i2<=3; $i2++){	// каждая пара перьев
							
						if($i != $i2){
						//if(1){
							if(!$abort1){
								$yF = $this_y + $dirProps1['y'] * $i2;
								$xF = $this_x + $dirProps1['x'] * $i2;
								$field = GameField :: $matrix[$yF][$xF];
								
								if(is_object($field)){
									if($field -> color != $this -> color){
										if((get_class($field) != 'Tank') && (get_class($field) != 'Plane')){
											if($i2 != 0){
												$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'crush', 'text' => $v.' '.$i.' '.$i2.' right'];
											}
										}
										$abort1 = true;
									}
								}else{
									if($i2 != 0){
										$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'run'];
									}
								}
							}
							
							if(!$abort2){
								$yF = $this_y + $dirProps2['y'] * $i2;
								$xF = $this_x + $dirProps2['x'] * $i2;
								$field = GameField :: $matrix[$yF][$xF];
								
								if(is_object($field)){
									if($field -> color != $this -> color){
										if((get_class($field) != 'Tank') && (get_class($field) != 'Plane')){
											if($i2 != 0){
												$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'crush', 'text' => $v.' '.$i.' '.$i2.' left'];
											}
										}
										$abort2 = true;
									}
								}else{
									if($i2 != 0){
										$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'run'];
									}
								}
							}
						}else{	// нельзя ходить на это поле, но можно перепрыгивать через свою фигуру
							$yF = $this_y + $dirProps1['y'] * $i2;
							$xF = $this_x + $dirProps1['x'] * $i2;
							$field = GameField :: $matrix[$yF][$xF];
							if(is_object($field) && $field -> color != $this -> color){
								$abort1 = true;
								$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'abort'];
							}
							
							$yF = $this_y + $dirProps2['y'] * $i2;
							$xF = $this_x + $dirProps2['x'] * $i2;
							$field = GameField :: $matrix[$yF][$xF];
							if(is_object($field) && $field -> color != $this -> color){
								$abort2 = true;
								$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'abort'];
							}else{
								$result[] = ['y' => $yF, 'x' => $xF, 'type' => 'n/abort'];
							}
						}
					}
				}
						

			}
		}
		return $result;
	}

	function GetVariantDir(&$result, $dir){
		
	}

	function AddVariantField(&$result, $dir){
		
	}
}

class mashinegun extends Unit
{
	public $img;
	public $name = 'mashinegun';
	public $text = 'Помимо артиллерии, есть ещё и пулемёты. Они также двигаются на одну клетку, тоже "стреляют", на три клетки в любую сторону, но не могут бить сквозь фигуры. Тем не менее, в чистом поле пулемёт с успехом косит вражеских солдат.';
	public $controllable = true;
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/machinegun.png';
	}
	
	function getVariants(){
		$result = [];
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> AddVariantField($result, $dir);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}

	function AddVariantField(&$result, $dir){
		$dirProps = Direction :: $properties[$dir];
		$field = GameField :: $matrix[$this -> y + $dirProps['y']][$this -> x + $dirProps['x']];
		
		if(!is_object($field)){
			$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];
		}
		
		$i = 1;
		$abort = false;
		while ($i <= 3){
			if($abort == false){
				$field = GameField :: $matrix[$this -> y + $dirProps['y']*$i][$this -> x + $dirProps['x']*$i];
				if(!is_object($field)){
					$result[] = ['y' => $this -> y + $dirProps['y']*$i, 'x' => $this -> x + $dirProps['x']*$i, 'type' => 'shot'];
				}else{
					if(($field -> color != $this -> color) && (get_class($field) != 'Tank')){
						$result[] = ['y' => $this -> y + $dirProps['y']*$i, 'x' => $this -> x + $dirProps['x']*$i, 'type' => 'shot+'];
					}
					$abort = true;
				}
			}
			$i ++;
		}
	}
}

class cannon extends Unit
{
	public $img;
	public $name = 'cannon';
	public $text = '<p>Артиллерия - бог войны. Тут всё просто и сногшибательно. Артиллерия ходит на одну клетку в любую сторону, но ест фигуры "выстрелом". То есть, ей не надо двигаться, чтобы осуществить взятие, вражеская фигура должна просто находится в пределах досягаемости орудий. Дальность стрельбы артиллерии - пять клеток в обе стороны по горизонтали, вперёд и вперёд по диагонали.</p>
	<p>Теперь имейте в виду, что у каждого игрока по два орудия, и что артиллерия может спокойно стрелять "сквозь" свои фигуры (снаряды как бы пролетают над ними). Представляете, какая зона поражения образуется в итоге?</p>
	<p>Если вражеская фигура встанет на любую из клеток, находящихся под прицелом, следующим ходом её просто уничтожат. Это артиллерия, и именно она является основой обороны любой из сторон. А наступление - это перенесение зоны действия артиллерии вперёд.</p>
	<p>(Возникает вопрос, может ли артиллерия бить по диагонали через угол, образованный формой доски. Наверное, этот и другие спорные вопросы рассматривались в полной версии правил, которой у меня нет.)</p>';
	public $controllable = true;
	
	function __construct($color, $y, $x) {
		parent::__construct($color, $y, $x);
		$this -> img = 'img/'.$this -> color.'/cannon.png';
	}
	
	function getVariants(){
		$result = [];
		
		$opp = Direction :: $properties[$this -> dirMain]['opposite'];
		$arrOpp = [$opp, Direction :: turnNext($opp), Direction :: turnPrev($opp)];
		
		$i = 1;
		
		$dir = $this -> dirMain;
		while ($i < 9)
		{			
			$this -> AddVariantField($result, $dir, $arrOpp);
			$dir = Direction :: turnNext($dir);
			$i ++;
		}
		
		return $result;
	}

	function AddVariantField(&$result, $dir, $arrOpp){
		$dirProps = Direction :: $properties[$dir];
		$field = GameField :: $matrix[$this -> y + $dirProps['y']][$this -> x + $dirProps['x']];
		
		if(!is_object($field)){
			$result[] = ['y' => $this -> y + $dirProps['y'], 'x' => $this -> x + $dirProps['x'], 'type' => 'run'];
		}
		
		$i = 1;
		$abort = false;
		while ($i <= 5){
			if($abort == false){
				if(array_search($dir, $arrOpp) === false){
					$field = GameField :: $matrix[$this -> y + $dirProps['y']*$i][$this -> x + $dirProps['x']*$i];
					if(!is_object($field)){
						$result[] = ['y' => $this -> y + $dirProps['y']*$i, 'x' => $this -> x + $dirProps['x']*$i, 'type' => 'shot'];
					}else{
						if($field -> color != $this -> color){
							$result[] = ['y' => $this -> y + $dirProps['y']*$i, 'x' => $this -> x + $dirProps['x']*$i, 'type' => 'shot+'];
							$abort = true;
						}
						//$abort = true;
					}
				}
			}
			$i ++;
		}
	}
}
?>