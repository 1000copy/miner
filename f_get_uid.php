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