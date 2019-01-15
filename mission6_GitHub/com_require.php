<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php

//データベースへの接続
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
// ■　セッションスタートとログイン処理
//----------------------------------------	
session_start();		//セッション開始
if (!isset($_SESSION["my_login"]) || $_SESSION["my_login"]==0){
	header("Location: http://tt-635.99sv-coco.com/mission6/login.php");	//ログインページにジャンプ
	exit;
}
//----------------------------------------	
// ■　変数初期化
//----------------------------------------
$error = "";			//エラーメッセージ
$my_no = $_SESSION["my_no"];	//ログイン会員番号
$my_id = $_SESSION["my_id"];	//ログインユーザID
$usr_no = $my_no;		//このページの会員番号
$usr_id = $my_id;		//このページのユーザID
$msg_user_no 	= 0;		//メッセージを見ることができる会員番号
$page 		= 1;		//ページ番号
$kaku 		= "";		//拡張子
$log_no 	= 0;		//ログ番号
$title 		= "";		//タイトル
$content 	= "";		//内容
$pic	 = "";			//画像
$mov	 = "";			//動画
//--------------------------------------------------
// □ 画像パスを取得
//--------------------------------------------------
$pic_path = get_path();
$pic_path.="image";
//--------------------------------------------------
// □ 画像フォルダの作成
//--------------------------------------------------
if (!file_exists($pic_path)){
	mkdir($pic_path,0777);
}

//--------------------------------------------------
// □ 動画パスを取得
//--------------------------------------------------
$mov_path = get_path();
$mov_path.="movie";
//--------------------------------------------------
// □ 動画フォルダの作成
//--------------------------------------------------
if (!file_exists($mov_path)){
	mkdir($mov_path,0777);
}
//----------------------------------------	
// ■　GETされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="GET"){
	//--------------------------------
	// □ ユーザ番号
	//--------------------------------
	if (isset($_GET["usr_no"])){
		$usr_no = $_GET["usr_no"];	//番号
		//-----------------------------------------------------
		// □：メッセージテーブル(friendmsg)からデータを読む
		//-----------------------------------------------------
		$sql = "SELECT * FROM friendinfo WHERE no='$usr_no'";
		$stmt = $pdo->query($sql);
		if ($stmt->fetchColumn()>0){
			$sql = "SELECT * FROM friendinfo WHERE no='$usr_no'";
			$stmt = $pdo->query($sql);
			$row = $stmt->fetch();
			$usr_id = $row["usrid"];
		}
	}
	//--------------------------------
	// □ ログ番号
	//--------------------------------
	if (isset($_GET["log_no"])){
		$log_no = $_GET["log_no"];
		//-----------------------------------------------------
		// □：プログラム日記テーブル(programlog)からデータを読む
		//-----------------------------------------------------
		$sql = "SELECT * FROM programlog WHERE no='$my_no' AND logno='$log_no'";
		$stmt = $pdo->query($sql);
		if ($stmt->fetchColumn()>0){
			$sql = "SELECT * FROM programlog WHERE no='$my_no' AND logno='$log_no'";
			$stmt = $pdo->query($sql);
			$row = $stmt->fetch();
			$title = $row["title"];
			$content = $row["content"];
			$pic = $row["pic"];
			$mov = $row["mov"];
		}
	}
	//--------------------------------
	// □ メッセージ番号
	//--------------------------------
	if (isset($_GET["msg_no"])){
		$msg_no = $_GET["msg_no"];
		//-----------------------------------------------------
		// □：メッセージテーブル(friendmsg)からデータを読む
		//-----------------------------------------------------
		$sql = "SELECT * FROM friendmsg WHERE msgno='$msg_no'";
		$stmt = $pdo->query($sql);
		if ($stmt->fetchColumn()>0){
			$sql = "SELECT * FROM friendmsg WHERE msgno='$msg_no'";
			$stmt = $pdo->query($sql);
			$row = $stmt->fetch();
			$msg_user_no = $row["no"];
		}
	}
	//--------------------------------
	// □ ページ番号
	//--------------------------------
	if (isset($_GET["page"])){
		$page = $_GET["page"];
	}
	if ($page<=1){$page=1;}
}
?>


</body>
</html>