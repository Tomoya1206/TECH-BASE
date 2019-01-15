<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php
//===========================================================================
// ■ ヘッダーの表示内容記述　header.php
//===========================================================================
if ($usr_no==$my_no){
	$color = MY_COLOR;
}else{
	$color = TOMO_COLOR;
}
?>
<table border="0" cellspacing="3" cellpadding="3" width="100%">
<tr><td align="left" bgcolor="<?=$color?>">
<font size="2">
<?php
//-----------------------------------------------------
// □：ログインしていたらマイページへのリンクを出力
//-----------------------------------------------------
if (strlen($_SESSION["my_id"])>0){
	echo "<a href=\"./mypage.php\">マイページ</a>";
	if ($usr_no<>$my_no){
		echo "&nbsp;&nbsp;<a href=\"./friendmsg.php?usr_no=$usr_no\">メッセージを送る</a>";
	}else{
		echo "&nbsp;&nbsp;<a href=\"./friendmsglist.php\">メッセージを見る</a>";
		echo "&nbsp;&nbsp;<a href=\"programlog.php\">日記を書く</a>";
		echo "&nbsp;&nbsp;<a href=\"friendinfo.php\">マイ情報</a>";
	}
}
echo "&nbsp;&nbsp;<a href=\"./logout.php\">ログアウト</a>";
?>
</font>
</td></tr>
</table>

</body>
</html>
