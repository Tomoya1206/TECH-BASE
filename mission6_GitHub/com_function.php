<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php
//======================================================================
//  ■： 共通関数
//======================================================================
//------------------------------------
// □ URLをリンクに変換
//------------------------------------
function get_url(&$str){

	//URLをリンクタグに変更
	$check = "{(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)}";	
	$str = preg_replace($check,"<a href=\"$1$2\" target=\"_blank\">$1$2</a>",$str);

	//メールアドレスをリンクタグに変更
	$check  = "/([a-zA-Z0-9_\.-]+\@)([a-zA-Z0-9_\.-]+)([a-zA-Z]+)/";
	$str = preg_replace($check,"<a href=\"mailto:$1$2$3\">$1$2$3</a>",$str);

	//ブランクを
	$str = nl2br($str);
}
//------------------------------------
// □ ホストアドレスを取得
//------------------------------------
function get_host(){
	$str = $_SERVER["HTTP_HOST"];
	$str .= rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
	return $str;
}
//------------------------------------
// □ 現在のパスを取得
//------------------------------------
function get_path(){
	$path = getcwd();
	if (isset($_ENV["OS"]) && preg_match("/window/i", $_ENV["OS"])){
		$path .= "\\";
	}else{
		$path .= "/";
	}
	return $path;
}
?>

</body>
</html>