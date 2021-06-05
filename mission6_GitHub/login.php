
<?php
//======================================================================
//  ■： ログイン画面 login.php
//======================================================================

//データベースに接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//----------------------------------------	
// ■　外部ファイルの取り込み
//----------------------------------------	
require_once("com_define.php");		//定数
require_once("com_function.php");	//関数
//----------------------------------------	
// ■　HOSTの取得
//----------------------------------------	
$host = get_host();
//----------------------------------------	
// ■　SESSION設定
//----------------------------------------	
session_start();		//セッション開始
$_SESSION["my_no"] = 0;		//自分の番号
$_SESSION["my_id"] = "";	//自分のID
$_SESSION["my_login"] = 0;	//ログイン
//----------------------------------------	
// ■　変数初期化
//----------------------------------------	
$error = "";
$usr_no = 0;
$usr_id = "";
$usr_pw = "";
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//--------------------------------------------
	// □ ログインボタンが押されたとき
	//--------------------------------------------
	if (isset($_POST["submit"])){
		//--------------------------------
		// □ POSTされたデータを取得
		//--------------------------------
		$usr_id = htmlspecialchars($_POST["usr_id"], ENT_QUOTES);	//ID
		$usr_pw = htmlspecialchars($_POST["usr_pw"], ENT_QUOTES);	//パスワード
		//--------------------------------
		// □ 入力内容チェック
		//--------------------------------
		//ユーザID
		if (strlen($usr_id)==0){$error = "ユーザIDが入力されていません";}
		//パスワード
		if (strlen($usr_pw)==0){$error = "パスワードが入力されていません";}
		if (strlen($error)==0){
			//--------------------------------------------
			// □ 友達情報テーブル(friendinfo)をチェック
			//--------------------------------------------
			$sql = "SELECT*FROM friendinfo WHERE usrid='$usr_id'";
			$stmt = $pdo->query($sql);
			if ($stmt->fetchColumn()>0){	//行が存在した場合
				$sql = "SELECT*FROM friendinfo WHERE usrid='$usr_id'";
				$stmt = $pdo->query($sql);
				$row = $stmt->fetch();
				if ($row["usrpw"] == $usr_pw){
					$sql = "SELECT*FROM friendinfo WHERE usrid='$usr_id'";
					$stmt = $pdo->query($sql);
					$row = $stmt->fetch();
					$_SESSION["my_no"] = $row["no"];
					$_SESSION["my_id"] = $usr_id;
					$_SESSION["my_login"] = 1;
					//------------------------------------
					// □ クッキーを保存する
					//------------------------------------
					setcookie("program[usr_id]",$usr_id);//ユーザIDを保存
					setcookie("program[usr_pw]",$usr_pw);//パスワードを保存
					//------------------------------------
					// □ マイページへジャンプ
					//------------------------------------
					header("Location: http://tt-635.99sv-coco.com/mission6/mypage.php");
					exit;
				}
			}else{	//行が存在しない場合
				//------------------------------------
				// □ 友達テーブル(friends)をチェック
				//------------------------------------
				$sql = "SELECT friends.no,friendinfo.usrid FROM friends";
				$sql.= " LEFT JOIN friendinfo ON friends.no=friendinfo.no";
				$sql.= " WHERE friends.email='$usr_id'";
				$stmt = $pdo->query($sql);
				if ($stmt->fetchColumn()>0){
					$sql = "SELECT friends.no,friendinfo.usrid FROM friends";
					$sql.= " LEFT JOIN friendinfo ON friends.no=friendinfo.no";
					$sql.= " WHERE friends.email='$usr_id'";
					$stmt = $pdo->query($sql);
					$row = $stmt->fetch();
					//----------------------------------------------
					// 一番最初にログインするときのチェック
					//----------------------------------------------
					if (!$row["usrid"] && $usr_pw == FIRST_PASS){
						$sql = "SELECT friends.no,friendinfo.usrid FROM friends";
						$sql.= " LEFT JOIN friendinfo ON friends.no=friendinfo.no";
						$sql.= " WHERE friends.email='$usr_id'";
						$stmt = $pdo->query($sql);
						$row = $stmt->fetch();
						$_SESSION["my_no"] = $row["no"];
						$_SESSION["my_login"] = 1;
						//------------------------------------
						// □ マイ情報設定ページへジャンプ
						//------------------------------------
						header("Location: http://tt-635.99sv-coco.com/mission6/friendinfo.php");
						exit;
					}
				}
			}
			$error = "ユーザIDかパスワードに誤りがあります";
		}
	}
}
//--------------------------------------------------------------------
// ■　クッキーを取得する(POSTでないとき)
//--------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"]!="POST"){
	if (isset($_COOKIE["cooking"])) {
		$cooking = $_COOKIE["cooking"];	//クッキーを変数に保存
		$usr_id = $program["usr_id"];	//ユーザIDを取得
		$usr_pw= $program["usr_pw"];	//ユーザパスワードを取得
	}
}
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title>プログラム日記ページ</title>
</head>
<body>
<h3>プログラム日記ページへようこそ</h3>
<?php
//--------------------------------------------------------------------
// ■　エラーメッセージがあったら表示
//--------------------------------------------------------------------
if (strlen($error)>0){
	echo "<font size=\"2\" color=\"#da0b00\">エラー：{$error}</font><p>";
}
?>
IDとパスワードを入れてログインしてください。<br>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<table border="0">
<tr><td align="left">ユーザID</td><td><input type="text" name="usr_id" value="<?=$usr_id ?>" size="30"></td></tr>
<tr><td align="left">パスワード</td><td><input type="password" name="usr_pw" value="<?=$usr_pw ?>"></td></tr>
<tr><td align="right" colspan="2"><input type="submit" name="submit" value="ログインする"></td></tr>
</table>
<font size="2" color="#556b2f">初めてログインする方へ<br>
IDにメールアドレスを入力し、メールでお知らせした初期パスワードを入力してください。<br>
IDとパスワードはログイン後に独自パスワードに変更できます。<br><br>
</font>
</form>
</body>
</html>

