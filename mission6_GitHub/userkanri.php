<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php

//======================================================================
//  ■： 友達会員限定プログラム日記公開システム　ユーザ管理 userkanri.php
//======================================================================
//------------------------------------
// ■　BASIC認証
//------------------------------------
if (!isset($_SERVER["PHP_AUTH_USER"]) ||
 !($_SERVER["PHP_AUTH_USER"] == "tom" && $_SERVER["PHP_AUTH_PW"] == "arakawa")){
	header("WWW-Authenticate: Basic realm=\"Programming community\"");
	header("HTTP/1.0 401 Unauthorized");
	echo "BASIC認証のIDまたはパスワードが正しくありません";
	echo "BASIC認証のIDまたはパスワードが正しくありません<br><br>";
	echo "<a href=\"userkanri.php\">ユーザ管理ページへ</a>";
	exit();
}

//データベースへの接続
$dsn='mysql:dbname=tt_635_99sv_coco_com;host=localhost';
$user='tt-635.99sv-coco';
$password='Kg6njAMp';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//----------------------------------------	
// ■外部ファイルの読み込み
//----------------------------------------	
require_once("com_define.php");
//----------------------------------------	
// ■　変数初期化
//----------------------------------------	
$sql = "";
$error = "";
$new_no = "";
$new_name = "";
$new_birth = "";
$new_email = "";
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//--------------------------------
	// □ 新規追加
	//--------------------------------
	if (isset($_POST["submit_add"])){
		//--------------------------------
		// □ POSTされたデータを取得
		//--------------------------------
		//新規追加
		$new_no = htmlspecialchars($_POST["new_no"], ENT_QUOTES);	//追加番号
		$new_name = htmlspecialchars($_POST["new_name"], ENT_QUOTES);	//追加名前
		$new_birth = htmlspecialchars($_POST["new_birth"], ENT_QUOTES);	//追加誕生日
		$new_email = htmlspecialchars($_POST["new_email"], ENT_QUOTES);	//追加メールアドレス
		//--------------------------------
		// □ 全角文字を半角に変換
		//--------------------------------
		$new_no = mb_convert_kana($new_no,"as");
		$new_birth = mb_convert_kana($new_birth,"as");
		$new_email = mb_convert_kana($new_email,"as");
		//--------------------------------
		// □ チェック
		//--------------------------------
		//番号
		if (strlen($new_no)>0){
			if (!preg_match("/^[0-9]*$/",$new_no)){$error = "新規番号[$new_no]に誤りがあります";}
			//最大番号を取得
			$stmt = $pdo->query("SELECT MAX(no) AS maxno FROM friends");
			$row = $stmt->fetch();
			if ($new_no<=$row["maxno"]){
				$error = "新規番号[$new_no]は最大番号よりも大きくしてください";
			}
		}else{
			$error = "新規番号が未入力です";
		}
		//名前
		if (strlen($new_name)==0){
			$error = "新規名前が未入力です";
		}
		//誕生日
		check_birth(0,$new_birth,$error);
		//メールアドレス
		check_email(0,$new_email,$pdo,$error);
		//--------------------------------
		// □ SQL文作成
		//--------------------------------
		if ($error==""){
			$sql = "INSERT INTO friends VALUES($new_no,'$new_name','$new_birth','$new_email')";
			$stmt = $pdo->query($sql);

			//------------------------------------
			// □ 新規ユーザ登録の際メールを送る
			//------------------------------------
			
			//文字化け防止
			mb_language("ja");
			mb_internal_encoding("UTF-8");
			
			$subject = "プログラミングコミュニティへのお誘い";

			$content = str_repeat("*==",20) ."\n";
			$content.= "◎プログラミングコミュニティへのお誘い\n";
			$content.= str_repeat("*==",20)."\n\n";
			$content.= "{$new_name}　さま\n\n";
			$content.= "プログラミングコミュニティに参加しませんか？\n\n";
			$content.= "http://tt-635.99sv-coco.com/mission6/login.php\n\n";
			$content.= "ユーザID:{$new_email}\n";
			$content.= "パスワード:" .FIRST_PASS ."\n\n";
			$content.= "ご参加を心からお待ちしております!\n";
			$mailfrom="From:t.arakawa.1206@gmail.com";

			if (mb_send_mail($new_email,$subject,$content,$mailfrom)){
				$error = "新規登録が完了しました";
			}else{
				$error = "メールが送信できませんでした";
			}
			$new_no = "";
			$new_name = "";
			$new_birth = "";
			$new_email = "";
			
		}

	}
	//--------------------------------
	// □ 変更
	//--------------------------------
	if (isset($_POST["submit_upd"])){
		$no = key($_POST[submit_upd]);	//押下したボタン番号を取得
		//--------------------------------
		// □ POSTされたデータを取得
		//--------------------------------
	 	$name = htmlspecialchars($_POST["name"][$no], ENT_QUOTES);	//名前
	 	$birth = htmlspecialchars($_POST["birth"][$no], ENT_QUOTES);	//誕生日
	 	$email = htmlspecialchars($_POST["email"][$no], ENT_QUOTES);	//メールアドレス
		//--------------------------------
		// □ 全角文字を半角に変換
		//--------------------------------
	 	$email = mb_convert_kana($email ,"as");
	 	$birth= mb_convert_kana($birth ,"as");
		//--------------------------------
		// □ チェック
		//--------------------------------
		//名前
		if (strlen($name)==0){
			$error = "{$no}番の名前が未入力です";
		}
		//誕生日
		check_birth($no,$birth,$error);
		//メールアドレス
		check_email($no,$email,$pdo,$error);
		//--------------------------------
		// □ SQL文作成
		//--------------------------------
		if ($error==""){
			$sql = "UPDATE friends SET name='$name',birth='$birth',email='$email' WHERE no=$no";
			$stmt = $pdo->query($sql);
			$error = "{$no}番のデータを変更しました";
		}
	}
	//--------------------------------
	// □ 削除
	//--------------------------------
	if (isset($_POST["submit_del"])){
		$no = key($_POST[submit_del]);		//押下したボタン番号を取得
		//友達テーブル(friends)から削除
		$sql = "DELETE FROM friends WHERE no=$no";
		$stmt = $pdo->query($sql);
		//友達情報テーブル(friendinfo)から削除
		$sql = "DELETE FROM friendinfo WHERE no=$no";
		$stmt = $pdo->query($sql);
		//メッセージテーブル(friendmsg)から削除
		$sql = "DELETE FROM friendmsg WHERE no=$no";
		$stmt = $pdo->query($sql);
		//クッキングログテーブル(cookinglog)から削除
		$sql = "DELETE FROM programlog WHERE no=$no";
		$stmt = $pdo->query($sql);
		//クッキングコメントテーブル(cookingres)から削除
		$sql = "DELETE FROM programres WHERE no=$no";
		$stmt = $pdo->query($sql);
		$error = "{$no}番のデータを削除しました";

	}
}
//=====================================================================
// ■　H T M L
//=====================================================================
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title>会員限定プログラム日記ページ　会員管理画面</title>
</head>
<body>
<?php
//--------------------------------------------------------------------
// ■　エラーメッセージがあったら表示
//--------------------------------------------------------------------
if (strlen($error)>0){
	echo "<font size=\"2\" color=\"#da0b00\">{$error}</font><p>";
}
?>
<h3>会員限定プログラム日記ページ　会員管理</h3>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table border="1" cellspacing="0" cellpadding="3" width="100%" bordercolor="#666666">
<tr bgcolor="#eee8aa">
<td align="center"><font size="2">番号</font></td>
<td align="center"><font size="2">名前</font></td>
<td align="center"><font size="2">誕生日</font></td>
<td align="center"><font size="2">メールアドレス</font></td>
<td><font size="2">　</font></td>
</tr>
<?php
//----------------------------------------	
// □：テーブルからデータを読む
//----------------------------------------
$stmt = $pdo->query("SELECT * FROM friends ORDER BY no");
while($row = $stmt->fetch()){
	$no = $row["no"];
	$name = $row["name"];
	$birth = $row["birth"];
	$email = $row["email"];
	echo <<<EOT
<td align="center">$no</td>
<td><input type="text" name="name[$no]" value="$name" size="10"></td>
<td><input type="text" name="birth[$no]" value="$birth"></td>
<td><input type="text" name="email[$no]" value="$email" size="30"></td>
<td><input type="submit" name="submit_upd[$no]" value="変更">
<input type="submit" name="submit_del[$no]" value="削除"></td>
</tr>
EOT;
}
//ここまでwhileループ[終了の閉じカッコ]
?>
<tr>
<td align="center"><input type="text" name="new_no" value="<?=$new_no ?>" size="5"></td>
<td><input type="text" name="new_name" value="<?=$new_name ?>" size="10"></td>
<td><input type="text" name="new_birth" value="<?=$new_birth ?>"></td>
<td><input type="text" name="new_email" value="<?=$new_email ?>" size="30"></td>
<td><input type="submit" name="submit_add" value="追加"></td>
</tr>
</table>
</form>
<font size="2" color="#556b2f">
※追加したユーザにログインパスワードのメールを自動的に送ります。<br>
</font>
</body>
</html>
<?php
//======================================================================
//  ■： ユーザ定義関数
//======================================================================
//------------------------------------
// □ 年月日のチェック
//------------------------------------
function check_birth($no,$ymd,&$error){
	if ($no == 0){
		$strno = "新規";
	}else{
		$strno = "{$no}番"; 
	}
	if (strlen($ymd)>0){
		if (!preg_match("/^[0-9-]*$/",$ymd)){
			$error= "{$strno}の誕生日[$ymd]に誤りがあります";
		}else{	
			list($y,$m,$d) = explode("-", $ymd);
			if (!checkdate($m,$d,$y)){
				$error = "{$strno}の誕生日[$ymd]に誤りがあります";
			}
		}
	}else{
		$error = "{$strno}の誕生日が未入力です";
	}
}


//------------------------------------
// □ メールアドレスのチェック
//------------------------------------
function check_email($no,$mail,&$pdo,&$error){
	if ($no == 0){
		$strno = "新規";
	}else{
		$strno = "{$no}番"; 
	}

	if (strlen($mail)>0){
		if (!preg_match("/^[^@]+@([-a-z0-9]+\.)+[a-z]{2,}$/", $mail)){
			$error = "{$strno}のメールアドレス[{$mail}]に誤りがあります";
		}
		//メールアドレスの重複チェック
		$stmt = $pdo->query("SELECT * FROM friends WHERE email='$mail' AND no<>'$no'");
		if ($stmt->fetchColumn()>0){
			$error = "{$strno}のメールアドレス[{$mail}]は登録されています";
		}
	}else{
		$error = "{$strno}のメールアドレスが未入力です";
	}
}
?>

</body>
</html>
