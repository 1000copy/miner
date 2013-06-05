	<?	
	function time_stub()
	{
		list($usec, $sec) = explode(' ', microtime());
		return round((float) $sec + (float) $usec,4);
	}
	function element_2_htmlstr($element)
	{
		$content = "";
		$newdoc = new DOMDocument('1.0', 'UTF-8');
		if($element)
		{
			$cloned = $element->cloneNode(TRUE);
			$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
			// saveHtml 必然转换non-ascii 为实体
			$content =  $newdoc->saveHTML();
			// 把实体转换为字符
			$content = html_entity_decode($content,ENT_QUOTES,"UTF-8");	  
		}
		return $content ;
	}
	// function save_html_file($newfile,$title,$content)
	// {
	// 	$html = "
	// 		  <html>
	// 			<head><title>{$title}</title>
	// 			 <meta http-equiv='content-type' content='text/html; charset=utf-8'>
	// 			</head>
	// 			<body>
	// 			  <h1>{$title}</h1>
	// 			  <p>{$content}</p>
	// 			</body>
	// 		  </html>
	// 		  ";		
	// 	$file = fopen ($newfile, "w"); 
	// 	fwrite($file, $html); 
	// 	fclose ($file);  
	// }
	// function get_content($i){
	// 	  $target_url = "http://yixieshu.com/bookchapter/{$i}";
	//       $xpath_content = '//*[@id="pageBody"]/div/div[3]';
	// 	  $xpath_title = "/html/head/title";
	//       $dom = new DOMDocument;
	//       try{
	// 	      // suppress warning by "@" operator .yeah 
	// 	      @$dom->loadHTMLFile($target_url);
	// 	      $path = new DOMXPath($dom);
	// 	      $tags = $path -> query($xpath_title);
	// 	      $title = $tags->item(0) ->nodeValue;	      	
	// 	      // print_r($title);
	// 	      $tags = $path -> query($xpath_content);
	// 	      $element = $tags->item(0);
	// 		  // element to html string
	// 		  $content = element_2_htmlstr($element);
	// 	      save_html_file("yixieshu.com\{$i}.HTML",$title,$content);
	// 		}catch(Exception $e){
	// 			echo "$e";
	// 		}
	// 	}  		   		
	function loop_get($book_config){
	  //41705,41738		
		$booktitle = $book_config['booktitle'];
		$from = $book_config['from'];
		$to = $book_config['to'];
		$xpath_content = $book_config['xpath_content'];
		$xpath_title = $book_config['xpath_title'];
		$len = $to - $from + 1;
		echo "total:{$len} ";
		$newfile = "{$booktitle}.html";
		$file = fopen ($newfile, "w"); 		
		$html_header = "
			  <html>
				<head><title>{$booktitle}</title>
				 <meta http-equiv='content-type' content='text/html; charset=utf-8'>
				</head>
				<body>				
			  ";	
		$html_footer ="
				</body>
			  </html>";
		fwrite($file,$html_header);
		for($i=$from;$i<=$to;$i++){
			echo "$i ";
			$url = $book_config['url'].$i;
			list($title,$content) = get_article($url,$xpath_title,$xpath_content);			
			fwrite($file,"<h1>{$title}</h1>");
			fwrite($file,"<p>{$content}<p>");
		}
		fwrite($file,$html_footer);
		fclose ($file); 
	}
	function get_article($url,$xpath_title,$xpath_content)
	{
		$dom = new DOMDocument;
      	try{
			// suppress warning by "@" operator .yeah 
			@$dom->loadHTMLFile($url);
			$path = new DOMXPath($dom);
			$tags = $path -> query($xpath_title);
			$title = $tags->item(0) ->nodeValue;	      	
			// print_r($title);
			$tags = $path -> query($xpath_content);
			$element = $tags->item(0);
			// element to html string
			$content = element_2_htmlstr($element);
			return array($title,$content);		      
		}catch(Exception $e){
			echo "$e";
		}
		return array("","");
	}
	$start = time_stub();
	// $book_config = array (
	// 		'booktitle'=>"Hitchhiker's Guide to the Galaxy",
	// 		'url'=>'http://yixieshu.com/bookchapter/',
	// 		'from'=>41705,
	// 		// 'to'=>41738,
	// 		'to'=>41705,
	// 		'xpath_title'=>'/html/head/title',
	// 		'xpath_content'=>'//*[@id="pageBody"]/div/div[3]');
	// loop_get($book_config);
	$book_config = array (
			'booktitle'=>"The Terminal Man",
			'url'=>'http://yixieshu.com/bookchapter/',
			'from'=>45571,
			// 'to'=>45571,
			'to'=>45589,
			'xpath_title'=>'/html/head/title',
			'xpath_content'=>'//*[@id="pageBody"]/div/div[3]'
			);
	loop_get($book_config);

	

	echo time_stub() - $start;
	count_elapse();
	function _array_ass()
	{
		$arr1 = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
		$arr2 = array ('a'=>5,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
		$aa = array();
		array_push($aa, $arr1);
		array_push($aa, $arr2);
		echo $aa[0]['a'];
	}
	function _array_2()
	{
		$arr1 = array (
			'booktitle'=>'银河系漫游指南',
			'from'=>41705,
			'to'=>41738,
			'xpath_title'=>'/html/head/title',
			'xpath_content'=>'//*[@id="pageBody"]/div/div[3]');
		$aa = array();
		array_push($aa, $arr1);
		array_push($aa, $arr2);
		echo $aa[0]['booktitle'];
		$file = fopen ("123.txt", "w"); 
		fwrite($file, $aa[0]['booktitle']); 
		fclose ($file);  
		echo $aa[0]['to'];		
	}
	// _array_2();
	
	function count_elapse(){		
		$script_start = time_stub();
	    sleep(1);	    
		
		$elapsed_time = time_stub () - $script_start;
		print_r($elapsed_time);
	}
	// test test test
	function continue_write()
	{
		$newfile = "1.html";
		$file = fopen ($newfile, "w"); 
		fwrite($file, "abc"); 
		fwrite($file, "abc\n"); 
		fwrite($file, "abc\n"); 
		fclose ($file);  
	}
	function getXYZ()
	{
	    return array(4,5,6);
	}
	function return_multi(){
		list($x,$y,$z) = getXYZ();
		echo "$x $y $z" ;		
	}
	// return_multi();
?>