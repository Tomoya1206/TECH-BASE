<?php
//======================================================================
//  ■： メッセージ表示画面 friendmsgread.php
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
	if (strlen($msg_no)==0 || $my_no != $msg_user_no){
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
	$tomo_no =$_POST["tomo_no"];
	$msg_no =$_POST["msg_no"];
	$title= htmlspecialchars($_POST["title"], ENT_QUOTES);		//件名
	$content = htmlspecialchars($_POST["content"], ENT_QUOTES);	//返信メッセージ
	//----------------------------------------
	// □ 返信メッセージボタンが押されたとき
	//----------------------------------------
	if (isset($_POST["submit_res"])){
		//--------------------------------
		// □ 入力内容チェック
		//--------------------------------
		//件名
		if (strlen($title)==0){$error ="件名が未入力です";}
		//メッセージ
		if (strlen($content)==0){$error ="返信メッセージが未入力です";}
		if (strlen($error)==0){
			//--------------------------------------------------
			// □ メッセージテーブル(friendmsg)を読む
			//--------------------------------------------------
			//レス番号の最大値を取得
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
			$sql.= "$tomo_no,$msg_no,$my_no,'$title','$content',0,now())";
			$stmt = $pdo->query($sql);
			$error = "返信メッセージを送信しました";
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
<title><?=$my_id ?> マイページ[メッセージを読む]</title>
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
	if ($error == "返信メッセージを送信しました"){
		echo "<br><center><a href=\"./mypage.php\">マイページへ</a></center>\n";
		echo "</body>\n";
		echo "</html>";
		exit;
	}
}
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table border="0" cellspacing="3" cellpadding="3" width="100%" height="100%"><tr>
<?php
//----------------------------------------	
// ■　左バーの取り込み
//----------------------------------------	
require_once("left.php");
//----------------------------------------	
// ■　右表示エリア
//----------------------------------------	
?>
<td align="left" valign="top">
<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr>
<td align="left" valign="top">
<font size="2">
<?php
//--------------------------------------------------------
// □：メッセージテーブル(friendmsg)からデータを読む
//--------------------------------------------------------
$sql = "SELECT friendmsg.*,friendinfo.usrid as tomoid FROM friendmsg";
$sql.= " LEFT JOIN friendinfo ON friendmsg.tomono = friendinfo.no";
$sql.= " WHERE friendmsg.no=$my_no AND friendmsg.msgno=$msg_no";
$stmt = $pdo->query($sql);
if ($stmt->fetchColumn()>0){
	$sql = "SELECT friendmsg.*,friendinfo.usrid as tomoid FROM friendmsg";
	$sql.= " LEFT JOIN friendinfo ON friendmsg.tomono = friendinfo.no";
	$sql.= " WHERE friendmsg.no=$my_no AND friendmsg.msgno=$msg_no";
	$stmt = $pdo->query($sql);
	$row = $stmt->fetch();
	$tomo_no = $row["tomono"];
	echo "差出人：<b>{$row["tomoid"]}さん</b><br>";
	echo "件名：<b>{$row["title"]}</b><p>";
	echo "受信日時：{$row["upddate"]}<p>\n";
	echo "【メッセージ】<br>\n";
	get_url($row["content"]);
	echo "<font color=\"#2b8e57\">{$row["content"]}</font><br><br>\n";
}
if (strlen($error)==0){$title = "Re:{$row["title"]}";}
//-----------------------------------------------------
// □：コメントテーブル(friendmsg)を既読に更新
//-----------------------------------------------------
$sql = "UPDATE friendmsg SET readflg=1 WHERE no=$my_no AND msgno=$msg_no";
$stmt = $pdo->query($sql);
?>
</font>
</td>
</tr>
</table><br><br>
<table border="1" cellspacing="0" cellpadding="3" width="500" bordercolor="#666666"><tr>
<td align="left" valign="top"><font size="2">
件名:<input type="text" name="title" value="<?=$title ?>" size="60"><br>
<textarea name="content" cols="50" rows="10"><?=$content ?></textarea><br>
<input type="submit" name="submit_res" value="このメッセージに返信する">
</font></td>
</tr></table>
</td>
</tr></table>
<input type="hidden" name="tomo_no" value="<?=$tomo_no ?>">
<input type="hidden" name="msg_no" value="<?=$msg_no ?>">
</form>
</body>
</html>