<?php
//======================================================================
//  ■： マイ情報の登録画面 friendinfo.php
//======================================================================
//----------------------------------------	
// ■　共通　require_once
//----------------------------------------	
require_once("com_require.php");
//----------------------------------------	
// ■　変数初期化
//----------------------------------------	
$usr_pw = "";
$usr_msg = "";
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//--------------------------------
	// □ 登録ボタンが押されたとき
	//--------------------------------
	if (isset($_POST["submit_toroku"])){
		//--------------------------------
		// □ POSTされたデータを取得
		//--------------------------------
		$usr_id = htmlspecialchars($_POST["usr_id"], ENT_QUOTES);	//ID
		$usr_pw = htmlspecialchars($_POST["usr_pw"], ENT_QUOTES);	//パスワード
		$usr_msg = htmlspecialchars($_POST["usr_msg"], ENT_QUOTES);	//メッセージ
		//--------------------------------
		// □ 入力内容チェック
		//--------------------------------
		//パスワード
		if (!preg_match("/^[A-Za-z0-9]{1,10}$/", $usr_pw)){
			$error = "パスワードに誤りがあります<br>";
		}
		if (strlen($usr_pw)==0){$error = "パスワードが未入力です";}
		//ユーザID
		if (strlen($usr_id)>30){$error = "ユーザIDは30桁までです";}
		$stmt = $pdo->query("SELECT * FROM friendinfo WHERE usrid='$usr_id' AND no<>'$usr_no'");
		$row = $stmt->fetch();
		if ($row){$error = "このユーザIDは既に使われています";}
		if (strlen($usr_id)==0){$error = "ユーザIDが未入力です";}
		if (strlen($error)==0){
			//--------------------------------------------
			// □ 友達情報テーブル(friendinfo)に登録
			//--------------------------------------------
			if (strlen($_SESSION["my_id"]) == 0){	//新規
				$sql = "INSERT INTO friendinfo VALUES('$usr_no','$usr_id','$usr_pw','$usr_msg',now())";
			}else{	//更新
				$sql = "UPDATE friendinfo SET usrid='$usr_id',usrpw='$usr_pw',";
				$sql.= "msg='$usr_msg',upddate=now() WHERE no='$usr_no'";
			}
			$stmt = $pdo->query($sql);
			$error = "登録が完了しました。";
			if (strlen($_SESSION["my_id"]) == 0){
				$error.= "<br>初めのログインに成功しました。<br>";
				$error.= "<a href=\"./mypage.php\">マイページを見るにはここをクリック！！</a>";
			}
			$_SESSION["my_id"] = $usr_id;
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
<title>マイ情報の登録</title>
</head>
<body>
<?php
//-----------------------------------------------------
// □：登録中ではないときにテーブルを読んでデータ表示
//-----------------------------------------------------
if (!isset($_POST["submit_toroku"])){
	//-----------------------------------------------------
	// □：友達情報テーブル(friendinfo)からデータを読む
	//-----------------------------------------------------
	$stmt = $pdo->query("SELECT * FROM friendinfo WHERE no='$usr_no'");
	$row = $stmt->fetch();
	if ($row){
		$usr_id = $row["usrid"];
		$usr_pw = $row["usrpw"];
		$usr_msg = $row["msg"];
		$_SESSION["my_id"] =$usr_id;	//ユーザID
	}
}
//----------------------------------------	
// ■　ヘッダーの取り込み
//----------------------------------------	
require_once("header.php");
?>
<h3>マイ情報の登録</h3>
<?php
//----------------------------------------	
// ■　エラーメッセージがあったら表示
//----------------------------------------	
if (strlen($error)>0){
	echo "<font size=\"2\" color=\"#da0b00\">{$error}</font><p>";
}
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table border="0" cellspacing="3" cellpadding="3"  width="600">
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">マイ番号</font></td>
<td><?=$usr_no ?></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">ユーザID<br>[ニックネーム]</font></td>
<td><input type="text" name="usr_id" value="<?=$usr_id ?>" size="30"><br>
<font size="2" color="#556b2f">30桁以内の任意の文字で入力してください</font></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">パスワード</font></td>
<td><input type="password" name="usr_pw" value="<?=$usr_pw ?>"><br>
<font size="2" color="#556b2f">10桁以内の英数字で入力してください</font></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">メッセージ</font></td>
<td><textarea name="usr_msg" cols="40" rows="10"><?=$usr_msg?></textarea></td></tr>
<tr><td align="right" colspan="2">
<input type="submit" name="submit_reset" value="リセット">
<input type="submit" name="submit_toroku" value="登録する"></td></tr>
</table>
</form>
</body>
</html>