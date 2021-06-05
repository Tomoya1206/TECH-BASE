<?php
//======================================================================
//  ■： メッセージ作成画面 friendmsg.php
//======================================================================
//----------------------------------------	
// ■　共通　require_once
//----------------------------------------	
require_once("com_require.php");
//----------------------------------------	
// ■　GETされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="GET"){
	//----------------------------------------	
	// ■　メッセージを送るユーザチェック
	//----------------------------------------	
	if (strlen($usr_no)==0 || strlen($usr_id)==0 || $my_no==$usr_no){
		header("Location: http://tt-635.99sv-coco.com/mission6/login.php");	//ログインページにジャンプ
		exit;
	}
}
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//--------------------------------
	// □ POSTされたデータを取得
	//--------------------------------
	$usr_no =$_POST["usr_no"];
	$usr_id =$_POST["usr_id"];
	$title = htmlspecialchars($_POST["title"], ENT_QUOTES);		//件名
	$content = htmlspecialchars($_POST["content"], ENT_QUOTES);	//メッセージ
	//--------------------------------
	// □ 送信ボタンが押されたとき
	//--------------------------------
	if (isset($_POST["submit"])){
		//--------------------------------
		// □ 入力内容チェック
		//--------------------------------
		//タイトル
		if (strlen($title)==0){$error ="タイトルが未入力です";}
		//メッセージ
		if (strlen($content)==0){$error ="メッセージが未入力です";}
		if (strlen($error)==0){
			//--------------------------------------------------
			// □ メッセージテーブル(friendmsg)を読む
			//--------------------------------------------------
			//メッセージ番号の最大値を取得
			$msg_no = 0;
			$stmt = $pdo->query("SELECT MAX(msgno) AS maxno FROM friendmsg");
			if ($stmt->fetchColumn()>0){
				$sql = "SELECT MAX(msgno) AS maxno FROM friendmsg";
				$stmt = $pdo->query($sql);
				$row = $stmt->fetch();
				$msg_no = $row["maxno"];
			}
			$msg_no++;
			//--------------------------------------------------
			// □ メッセージテーブル(friendmsg)に新規追加
			//--------------------------------------------------
			$sql = "INSERT INTO friendmsg VALUES(";
			$sql.= "$usr_no,$msg_no,$my_no,'$title','$content',0,now())";
			$stmt = $pdo->query($sql);
			$error = "メッセージを送信しました";
		}
	}
}
//=====================================================================
// ■　H T M L
//=====================================================================
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title><?=$my_id ?>から<?=$usr_id ?>マイページ[メッセージを送る]</title>
</head>
<body>
<?php
//----------------------------------------	
// ■　ヘッダーの取り込み
//----------------------------------------	
require_once("header.php");
//----------------------------------------	
// ■　エラーメッセージがあったら表示
//----------------------------------------	
if (strlen($error)>0){
	echo "<font size=\"2\" color=\"#da0b00\">{$error}</font><p>";
	if ($error == "メッセージを送信しました"){
		echo "<br><center><a href=\"./mypage.php\">マイページへ</a></center>\n";
		echo "</body>\n";
		echo "</html>";
		exit;
	}

}
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table border="0" cellspacing="3" cellpadding="3" width="100%" height="100%">
<tr>
<?php
//----------------------------------------	
// ■　左バーの取り込み
//----------------------------------------	
require_once("left.php");
//----------------------------------------	
// ■　右表示エリア
//----------------------------------------	
?>
<td align="left" valign="top"><font size="2">
<b><?=$usr_id?>さん</b>へメッセージを送ります<p>
件名：<input type="text" name="title" value="<?=$title ?>" size="60"><p>
メッセージ：<br>
<textarea name="content" cols="60" rows="10"><?=$content ?></textarea><br>
<input type="submit" name="submit" value="メッセージを送る">
</font></td>
</tr>
</table>
<input type="hidden" name="usr_no" value="<?=$usr_no ?>">
<input type="hidden" name="usr_id" value="<?=$usr_id ?>">
</form>
</body>
</html>