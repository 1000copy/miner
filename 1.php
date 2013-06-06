<?
// wget 可以做到只是提取文件，而不去执行他。
// wget  --post-data "LoginName=#8826&Password=****&LockNum=err*UserRank=0" http://192.168.99.1/zhang/VerifyUser.asp
// 比如 post得到的文件
// <SCRIPT LANGUAGE=javascript>	
// 	window.open( 'Window.asp?UID={B78E9829-D2D7-4E94-A98F-F4BC5CAAAF25}...
// </SCRIPT>
// 如果是brower，会执行js，转向到另一个文件，你就看不到UID的值了。

function packURL($title,$url){
	return "<a href='{$url}'>{$title}</a>";
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
function get_bbs(){
	// echo "d";
	$column_id = '001001033017';
	$uid = "{B78E9829-D2D7-4E94-A98F-F4BC5CAAAF25}";
	$ip ="125.69.76.113";
	$ip  = "192.168.99.1";
	$url = "http://192.168.99.1/zhang/Bbs/List.asp?UID={$uid}&ColumnTypeID={$column_id}&PageNo=1";
	// $url = "http://{$ip}/zhang/Bbs/List.asp?UID={$uid}&ColumnTypeID={$column_id}&PageNo=1";	
	echo $url ;
	$homepage = file_get_contents($url);
	echo $homepage;
	die();
	// select by class
	// why it works in chrome find box ,but not works in php?
	$xpath_row = "/html/body/table[3]/tbody/tr[@class='ListTableRow']";
	// so does it work !
	$xpath_row = "//tr[@class='ListTableRow']";
	// $xpath_row = "/html/head/title";
	$dom = new DOMDocument;
  	try{
		// suppress warning by "@" operator .yeah 
		// $dom->loadHTMLFile($url);
		@$dom->loadHTML($homepage);
		$path = new DOMXPath($dom);
		$tags = $path -> query($xpath_row);
		$c = $tags-> length;
		// $title = $tags->item(0) ->nodeValue;	  
		// for($i = 0;$i<$c;$i++){
		// 	echo $i;			
		// }

		for($i = 0;$i < $c; $i++){			
			$element = $tags->item($i);
			// 获取属性
			// echo $element->getAttribute("title");
			// 获取child by name
			$author = $element->childNodes->item(6)->nodeValue ;
			echo $author;
			$date = explode(" ",$element->childNodes->item(8)->nodeValue)[2] ;
			echo $date;
			$id = $element->childNodes->item(10)->nodeValue ;
			$id = paddingZero($id);
			// echo $id;
			$title = trim(StrDelete($element->childNodes->item(4)->nodeValue ,0,10));
			echo packURL($title,"http://{$ip}/zhang/Bbs/Detail.asp?UID={$uid}&ColumnTypeID={$column_id}&TypeID={$id}");
			
			echo "<br/>";
		}		      
	}catch(Exception $e){
		echo "$e";
	}
}
get_bbs();
?>