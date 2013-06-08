	<?
		// 要在console (windows)输出中文（utf8）不乱码，需要如下配置：
		// The problem is Windows cmd line by default does not support UTF8. From this link, if you follow these

		// Open a command prompt window
		// Change the properties of the window to use something besides the default raster font. he Lucida Console True Type font seems to work well.
		// Run "chcp 65001" from the command prompt
		// You should be able to output utf8.
	 function group_sum(){
	  $a = array('civil' => 0, 'criminal'=>0,'other'=>0);
	  $from = 2603;
	  $to = 3179;
	  // $to  = 2610;
	  $len = $to - $from + 1;
	  echo "total:{$len}";
	  for($i=$from;$i<=$to;$i++){
	      $douban_search_url = 
	        "http://www.msfy.gov.cn/Showarticles.asp?ID={$i}";
	      // $xpath_item = "//ul[@class='subject-list']/li[@class='subject-item']";
	      // $xpath_image = "div/a/img";
	      // $xpath_a = "div/h2/a";
	      $xpath_item = "/html/body/div[2]/div/div/div[1]/div[1]/h3";      
	      
	      $dom = new DOMDocument;
	      // suppress warning by "@" operator .yeah 
	      @$dom->loadHTMLFile($douban_search_url);
	      $path = new DOMXPath($dom);
	      $tags = $path -> query($xpath_item);
	      foreach($tags as $v){
	      	$wordword = $v ->nodeValue;
	        if (strpos($wordword, "眉民"))
	        	$a["civil"]	= $a["civil"]	+1;
			else if (strpos($wordword, "眉刑"))
	        	$a["criminal"]	= $a["criminal"]	+1;
	        else 
	        	$a["other"]	= $a["other"]	+1;
	      }     
	      echo $i." "; 	
			}
			print_r($a);
	}
	function get_content($i){
		  $douban_search_url = "http://www.msfy.gov.cn/Showarticles.asp?ID={$i}";	      
	      $xpath_title = "/html/body/div[2]/div/div/div[1]/div[1]/h3";      
	      $xpath_content = "/html/body/div[2]/div/div/div[2]";
	      $dom = new DOMDocument;
	      try{
		      // suppress warning by "@" operator .yeah 
		      @$dom->loadHTMLFile($douban_search_url);
		      $path = new DOMXPath($dom);
		      $tags = $path -> query($xpath_title);
		      $title = $tags->item(0) ->nodeValue;	      	
		      // print_r($title);
		      $tags = $path -> query($xpath_content);
		      $element = $tags->item(0);
		      $newdoc = new DOMDocument('1.0', 'UTF-8');
		      if($element){
			    $cloned = $element->cloneNode(TRUE);
			    $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
			    // saveHtml 必然转换non-ascii 为实体
			    $content =  $newdoc->saveHTML();
			    // 把实体转换为字符
				    $content = html_entity_decode($content,ENT_QUOTES,"UTF-8");	  
			      $html = "
			      <html>
			      	<head><title>{$title}</title>
			      	 <meta http-equiv='content-type' content='text/html; charset=utf-8'>
			      	</head>
			      	<body>{$content}</body>
			      </html>
			      ";
			       if (strpos($title, "眉民") || strpos($title, "眉刑")  ){			        	
						$newfile="data\{$i}.HTML"; 
						$file = fopen ($newfile, "w"); 
						fwrite($file, $html); 
						fclose ($file);  
					}
			  }
			}catch(Exception $e){
				echo "$e";
			}
		}  		   		
	function loop_get(){
	  $from = 2603;
	  // $from = 2634;
	  $to = 3179;
	  // $to  = 2610;
	  $len = $to - $from + 1;
	  echo "total:{$len}";
	  // mkdir("data");
	  for($i=$from;$i<=$to;$i++){
	  	echo "$i ";
	  	get_content($i);
	  }
	}
	// foo();
	// get_content(3179);
	loop_get();
	?>