<?php

/**
* 这个可以更好处理encoding的 Dom类，我从这里看到:
* http://stackoverflow.com/questions/12988379/converting-xml-domdocument-to-string
*
*
* This class overcomes a few common annoyances with the DOMDocument class,
* such as saving partial HTML without automatically adding extra tags
* and properly recognizing various encodings, specifically UTF-8.
*
* @author Artem Russakovskii
* @version 0.4
* @link http://beerpla.net
* @link http://www.php.net/manual/en/class.domdocument.php
*/
if(!class_exists("SmartDOMDocument")) {
  class SmartDOMDocument extends DOMDocument {

	  /**
	  * Adds an ability to use the SmartDOMDocument object as a string in a string context.
	  * For example, echo "Here is the HTML: $dom";
	  */
	  public function __toString() {
		  return $this->saveHTMLExact();
	  }

	  /**
	  * Load HTML with a proper encoding fix/hack.
	  * Borrowed from the link below.
	  *
	  * @link http://www.php.net/manual/en/domdocument.loadhtml.php
	  *
	  * @param string $html
	  * @param string $encoding
	  */
	  public function loadHTML($html, $encoding = "UTF-8") {
	  	  // 把原来编码为gb2312的html 字符串，转换为 HTML-ENTITIES 编码。这样，不懂得编码的DomDocument 也可以出来编码文件。
		  $html = mb_convert_encoding($html, 'HTML-ENTITIES', $encoding);
		  @parent::loadHTML($html); // suppress warnings
	  }

	  /**
	  * Return HTML while stripping the annoying auto-added <html>, <body>, and doctype.
	  *
	  * @link http://php.net/manual/en/migration52.methods.php
	  *
	  * @return string
	  */
	  public function saveHTMLExact() {
      $content = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
                                    "!</body></html>$!si"),
                              "",
                              $this->saveHTML());

		  return $content;
	  }

    /**
    * This test functions shows an example of SmartDOMDocument in action.
    * A sample HTML fragment is loaded.
    * Then, the first image in the document is cut out and saved separately.
    * It also shows that Russian characters are parsed correctly.
    *
    */
    public static function testHTML() {
      $content = <<<CONTENT
<div class='class1'>
  <img src='http://www.google.com/favicon.ico' />
  Some Text
  <p>русский</p>
</div>
CONTENT;

      print "Before removing the image, the content is: " . htmlspecialchars($content) . "<br>";

      $content_doc = new SmartDOMDocument();
      $content_doc->loadHTML($content);

      try {
        $first_image = $content_doc->getElementsByTagName("img")->item(0);

        if ($first_image) {
          $first_image->parentNode->removeChild($first_image);

          $content = $content_doc->saveHTMLExact();

          $image_doc = new SmartDOMDocument();
          $image_doc->appendChild($image_doc->importNode($first_image, true));
          $image = $image_doc->saveHTMLExact();
        }
      } catch(Exception $e) { }

      print "After removing the image, the content is: " . htmlspecialchars($content) . "<br>";
      print "The image is: " . htmlspecialchars($image);
    }

  }
}
?>
<?
    // extract $1 Window.asp?UID={$1} 
	function extract_guid($str){
		$pattern = "/\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?/";
		// preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);
		preg_match($pattern, $str, $matches);
		if (count($matches) > 0 )
			return $matches[0];
		else
			return "not guid";
	}
	// $str = "Window.asp?UID={D70126C0-AE8F-477B-AACE-5CE76623FA9F}";						 
	// echo $str."<br/>";
	// echo "Window.asp?UID=".extract_guid($str);


	// wget  --post-data "LoginName=#8826&Password=****&LockNum=err*UserRank=0" 
	function Post($url, $post = null)  
	{  
	    $context = array();  
	  
	    if (is_array($post))  
	    {  
	        ksort($post);  
	  
	        $context['http'] = array  
	        (  
	            'method' => 'POST',  
	            'content' => http_build_query($post, '', '&'),  
	        );  
	    }  
	  
	    return file_get_contents($url, false, stream_context_create($context));  
	}  
	//"LoginName=#8826&Password=****&LockNum=err*UserRank=0"   
	function verify_string(){
	
	}
	function get_uid($login,$password,$url){
			$data = array  
		(  
		    'LoginName' => "$login",  
		    'Password' => "$password",  
		    'LockNum' => 'err',
		    'UserRank' => 0,  
		);  	  
		return extract_guid(Post($url, $data));
	}
	// echo get_uid("#8826","sunqin",'http://192.168.99.1/zhang/VerifyUser.asp');
	
?>
<?
// include "f_get_uid.php";
// wget 可以做到只是提取文件，而不去执行他。
// wget  --post-data "LoginName=#8826&Password=****&LockNum=err*UserRank=0" http://192.168.99.1/zhang/VerifyUser.asp
// 比如 post得到的文件
// <SCRIPT LANGUAGE=javascript>	
// 	window.open( 'Window.asp?UID={B78E9829-D2D7-4E94-A98F-F4BC5CAAAF25}...
// </SCRIPT>
// 如果是brower，会执行js，转向到另一个文件，你就看不到UID的值了。

function packURL($title,$url){
	return "<a href='{$url}' target='_blank'>{$title}</a>";
}
function right($str, $length) {
     return substr($str, -$length);
}
function paddingZero($t){
	return right("0000000000".$t,10);
}
function StrDelete($aString, $BeginPos, $Length)
{
  $r = '';
  $l = strlen($aString);
  $EndPos = $BeginPos + $Length;
  for ($i = 0; $i < $l; $i++)
    if (($i < $BeginPos) || ($i >= ($EndPos)))
      $r .= $aString[$i];
  return $r;
}

function get_bbs($uid,$ip){	
	$column_id = '001001033017'; // 内部公告栏
	// $column_id = '001002001001'; //管理建议
	// $column_id = '001002001021'; // 讨论软件开发 ,不同栏目，格式不同。我++
	// $ip ="125.69.76.113";	
	$url = "http://{$ip}/zhang/Bbs/List.asp?UID={$uid}&ColumnTypeID={$column_id}&PageNo=1";		
	// echo $url ;
	$homepage = file_get_contents($url);
	// echo $homepage;
	// die();
	// select by class
	// why it works in chrome find box ,but not works in php?
	$xpath_row = "/html/body/table[3]/tbody/tr[@class='ListTableRow']";
	// so does it work !
	$xpath_row = "//tr[@class='ListTableRow']";
	// $xpath_row = "/html/head/title";
	// $dom = new DOMDocument;
	$dom = new SmartDOMDocument ;
  	try{
		// suppress warning by "@" operator .yeah 
		// $dom->loadHTMLFile($url);
		// $homepage = iconv("GB2312","UTF-8"."//IGNORE",$homepage);
		// @$dom->loadHTML('<?xml encoding="gb2312">'.$homepage);

		// until $homepage, encoding is correct !
		// echo $homepage;
		// exit;
		// $homepage = mb_convert_encoding($homepage, 'HTML-ENTITIES', 'GB2312');
		// @$dom->loadHTML($homepage,"GB2312");
		@$dom->loadHTML($homepage,"GB2312");
		// $content =$dom->saveHTML();
		// $content = html_entity_decode($content,ENT_QUOTES,"UTF-8");	
		// $content = html_entity_decode($content,ENT_QUOTES,"GB2312");	
		// echo $content;
		// echo "aha";
		// exit;
		$path = new DOMXPath($dom);
		//ttile
		// $title_path = "/html/body/title";
		// $tags = $path -> query($title_path);
		// $title = $tags->item(0)->nodeValue ;
		// echo "dd打开";
		// echo ($title);
		// echo "dd打开";
		// rows 
		$tags = $path -> query($xpath_row);
		$c = $tags-> length;		
		for($i = 0;$i < $c; $i++){			
			$element = $tags->item($i);
			// 获取属性
			// echo $element->getAttribute("title");
			// 获取child by name
			$author = $element->childNodes->item(6)->nodeValue ;
			echo $author;
			$fdate = explode(" ",$element->childNodes->item(8)->nodeValue);
			$date = $fdate[2] ;
			echo $date;
			$id = $element->childNodes->item(10)->nodeValue ;
			$id = paddingZero($id);
			// echo $id;
			// $title = trim(StrDelete($element->childNodes->item(4)->nodeValue ,0,10));
			$title = $element->childNodes->item(4)->nodeValue ;
			echo packURL($title,"http://{$ip}/zhang/Bbs/Detail.asp?UID={$uid}&ColumnTypeID={$column_id}&TypeID={$id}");
			
			echo "<br/>";
		}		
		echo "<a href=''>";
	}catch(Exception $e){
		echo "$e";
	}
}
$u = $_POST["usrname"];
$p = $_POST["psw"];
if ($u){
	// $ip  = "192.168.99.1";	
	$ip ="125.69.76.113";	
	$uid = get_uid($u,$p,"http://{$ip}/zhang/VerifyUser.asp");	
	// header('Content-Type: text/html; charset=utf-8');
	header('Content-Type: text/html; charset=UTF-8');
	// header('Cache-Control:max-age=110');
	if ($uid != 'not guid'){
		echo "<h3>任我行公司内部公告</h3>";
		get_bbs($uid,$ip);
	}
	else
		echo "login in error ,check your user name and password!";
	die("signed by 1000copy !");
}
// get_bbs();

?>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 <!-- <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />  -->
 <title>龟壳</title>
</head>
<body>
	<form method="post" action="<?echo $_SERVER["PHP_SELF"];?>">
		<div style="padding-left:50px;">
		    <br/>用户:<br/>
		    <input name="usrname" type="text" size="24" maxlength="60" value="#8826"/><br/><br/>
		    密码:<br/>
		    <input name="psw" type="password" size="10" maxlength="20" value=""/>　　
		    <!-- <a href="/user/resetpsw">忘记密码了</a><br/> -->
		    <br/><br/>
		    <!-- <input name="remember" type="checkbox"/>&nbsp;
		    <span class="pl">在这台电脑上记住我(一个月之内不用再登录)</span> -->
		    
		    <input name="user_login" class="butt" type="submit" value="进入"/>
		</div>
	</form>
</body>
</html>