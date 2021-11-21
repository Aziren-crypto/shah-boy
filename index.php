<html>
	<head>
		<title>Советские шахматы</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head> 
	<body>
		<?
		echo '<div class="ajax"></div>
		<div class="message">
			<div class="titr">
			<p><h3 style="padding-left:40px">Источники:</h3> <ul>
				<li><a target="_blank" href="https://www.marpravda.ru/news/zhizn-v-mariy-el/v-mezhdunarodnyy-den-shakhmat-predlagaem-vspomnit-o-zamechatelnoy-raznovidnosti-shakhmat-shakh-boy/">В Международный день шахмат предлагаем вспомнить о замечательной разновидности шахмат "Шах-бой"</a></li>
				<li><a target="_blank" href="https://zen.yandex.ru/media/aprelev/tankom-hodi-zabytye-sovetskie-shahmaty-60bb7c6fbbd5974178138fb8">Танком ходи! Забытые "советские шахматы"</a></li>
				<li><a target="_blank" href="https://gest.livejournal.com/889387.html">Советские шахматы</a></li>
			</ul></p>
			</div>
			<div class="mes"></div>
			<div class="text"></div>
		</div>';?>
		<script src="jquery-3.3.1.min.js"></script>
		<script>
			stepcolor = 'red';
			stepnum = 1;
			$('.mes').text('№ хода:'+stepnum+'. Ход красных.');
			ajax_script = 'ajax.php';

			function toglePhase(){
				if(phase == 'collision')
					phase = 'action';
				else
					phase = 'collision';
			}

			phase = 'collision';
			
			function alertObj(obj) { 
				var str = ""; 
				for(k in obj) { 
					str += k+": "+ obj[k]+"\r\n"; 
				} 
				alert(str); 
			} 
							
			$('body').on('click', 'div.crush_, div.run_', function(){
				id = $('.unit.selected').find('.id').text();
				arr = $(this).attr('id').split('_');
				y_ = arr[1];
				x_ = arr[2];
				$.ajax({
					type: 'POST',
					url: 'ajax.php',
					data: {id: id, x : x_, y : y_},//action: 'crush', 
					success: function(data) {
						$('.ajax').html(data);
						if(stepcolor == 'red'){
							stepcolor = 'black';
							$('.mes').text('№ хода:'+stepnum+'. Ход чёрных.');
						}else{
							stepnum ++;
							stepcolor = 'red';
							$('.mes').text('№ хода:'+stepnum+'. Ход красных.');
						}
					},
					error:  function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			});
			
			$('body').on('click', 'div.shot_', function(){
				arr = $(this).attr('id').split('_');
				y_ = arr[1];
				x_ = arr[2];
				$.ajax({
					type: 'POST',
					url: 'ajax.php',
					data: {x : x_, y : y_},
					success: function(data) {
						$('.ajax').html(data);
						if(stepcolor == 'red'){
							stepcolor = 'black';
							$('.mes').text('№ хода:'+stepnum+'. Ход чёрных.');
						}else{
							stepnum ++;
							stepcolor = 'red';
							$('.mes').text('№ хода:'+stepnum+'. Ход красных.');
						}
					},
					error:  function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			});
							
			$('body').on('click', 'div.unit.controllable', function(){
				if(phase == 'collision'){
					if($(this).attr('data-color') == stepcolor){
						$('div.unit.controllable').removeClass('selected');
						$('.run_').removeClass('run_');
						$('.crush_').removeClass('crush_');
						$('.shot').removeClass('shot');
						$('.shot_').removeClass('shot_');		
						$(this).addClass('selected');
						
						
						//that = this;
						$.ajax({
							type: 'POST',
							url: 'ajax_variants.php',
							data: {x : $(this).attr('data-x'), y : $(this).attr('data-y')},
							//type : $(this).attr('data-type'), color : $(this).attr('data-color'), 
							success: function(data) {
								//$('.ajax').html(data);
								//toglePhase();
								//alert('ok');
								var arr = JSON.parse(data);

								
								arr.forEach(function(item, i, arr) {
									if(arr[i].type == 'run')
										$('#td_'+arr[i].y+'_'+arr[i].x).addClass('run_');
									else if(arr[i].type == 'crush')
										$('#td_'+arr[i].y+'_'+arr[i].x).addClass('crush_');
									else if(arr[i].type == 'shot')
										$('#td_'+arr[i].y+'_'+arr[i].x).addClass('shot');
									else if(arr[i].type == 'shot+')
										$('#td_'+arr[i].y+'_'+arr[i].x).addClass('shot_');
								});
							},
							error:  function(xhr, str){
								alert('Возникла ошибка: ' + xhr.responseCode);
							}
						});
						$.ajax({
							type: 'POST',
							url: 'ajax_text.php',
							data: {x : $(this).attr('data-x'), y : $(this).attr('data-y')},
							success: function(data) {
								$('.text').html(data);
							},
							error:  function(xhr, str){
								alert('Возникла ошибка: ' + xhr.responseCode);
							}
						});
					}
				}
			});

			// < Подгрузка сразу же при открытии страницы
			$.ajax({
				url: ajax_script+'?restart=Y',
				type: 'post',
				success: function(data){
					$('.ajax').html(data);
				}	
			});
			// > Подгрузка сразу же при открытии страницы
			
		</script>
		<style>
			.gol td{
				//border: 1px solid black;
				width: 20px;
				height: 20px;
			}

			.td.black{
				background-color: black;
				//border: 1px solid black;
			}
			
			.td.white{
				background-color: white;
			}
			
			.td.gray{
				background-color: d6d6d6;
			}
			
			span.unit{
				z-index: 99999;
				display: block;
			}
			span.unit:hover::after {
				content:
				<?$arAttr = ['Hlt', 'HltMax', 'Dmg', 'Am', 'As', 'Dm', 'Ds'];
				foreach($arAttr as $attr){
					echo '"'.$attr.':" attr('.$attr.') " | "';
				}?>;
				background-color: #c2c2c2;
				z-index: 99999;

				position: absolute;
			}

			.damp div{
				display: inline-block;
			}

			table td .colordiv{
				height: 100%;
				width: 100%;
				
			}

			.console button{
				width: 50px;
			}
			
			.box {
			  position: relative;
			  width: 60px;
			  height: 60px;
			  border: 1px solid red;
			}
			.raz{
			  border: 1px solid red;
			  position: absolute;
			}
			.raz.rt {
			  right: 0;
			}
			.raz.rd {
			  right: 0;
			  bottom: 0;
			}
			.raz.lt {
			  left: 0;
			}
			.raz.ld {
			  left: 0;
			  bottom: 0;
			}

			.td{
				display: inline-block;
				height: 50px;
				width: 50px;
				//border: 1px solid #a8a8a8;
				vertical-align: middle;
				border-collapse: collapse;
			}

			.td span{
				display: inline-block;
			}

			.tr .td img.iconMain{
				width: 100%;
				height: 100%;
				max-height: 100%;
				max-width: 100%;
			}

			.tr .td .unit.selected img.iconMain{
				animation: 0.5s blinker linear infinite;
				border: 2px solid red;
				box-sizing:border-box;
			}

			.tr .td .unit .id{
				display: none;
			}

			img.arrowTry, img.arrowDir{
				position: absolute;
			}

			img.arrowDir{
				z-index: 100;
				width: 100%;
				left: 0;
			}

			img.arrowTry{
				z-index: 200;
				pointer-events: none;
				width: 100%;
			}

			div.unit, div.relative{
				position: relative;
			}

			@keyframes blinker {
				0% { opacity: 1.0; }
				50% { opacity: 0.00; }
				100% { opacity: 1.0; }
			}
			
			.unvisible{
				display: none;
			}
			
			.sign{
				right: 0;
				border: 1px solid red;
				position: absolute;
				background-color: ebf2bb;
				z-index: 200;
				padding: 2px;
			}
			
			form#actions{
				margin: 20px;
			}
			
			form#actions fieldset{
				display: inline;
			}
			
			form#actions input{
				display: block;
			}
			
			.conflict{
				position: absolute;
				z-index: 3;
				animation: 2.5s blinker linear infinite;
				background-color: red;
				height: 50px;
				width: 50px;
				top: 0px;
				//box-sizing:border-box;
			}
			
			.container{
				border: 1px solid black;
				display: inline-block;
			}
			
			.run_{
				background-color: #5fe3f5 !important;
			}
			
			.crush_{
				background-color: #8ff55f !important;
			}
			
			.shot{
				background: url(img/gun.png) 100% 100% no-repeat; !important;
				background-size: cover;
			}
			
			.shot_{
				background-color: #e3d08d !important;
				background: url(img/gun.png) 100% 100% no-repeat; !important;
				background-size: cover;
			}
			
			.ajax, .message{
				display: inline-block;
				vertical-align: top;
				padding: 10px;
			}
			
			.message{
				width: 60%;
			}
			
			.mes{
				font-size: 40px;
			}
			
			.titr{
				
				border: 2px solid red;
			}
		</style>
	</body>
</html>