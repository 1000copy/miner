<?
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
	$column_id = '001001033017';
	$uid = "{96BCF9C4-E0F0-4A81-AAFF-A38C5A37294A}";
	$url = "http://192.168.99.1/zhang/Bbs/List.asp?UID={$uid}&ColumnTypeID={$column_id}&PageNo=1";
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
			echo packURL($title,"http://192.168.99.1/zhang/Bbs/Detail.asp?UID={$uid}&ColumnTypeID={$column_id}&TypeID={$id}");
			
			echo "<br/>";
		}		      
	}catch(Exception $e){
		echo "$e";
	}
}
get_bbs();
?>