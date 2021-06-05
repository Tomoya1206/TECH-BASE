<?php
//======================================================================
//  ■： プログラム日記作成画面　programlog.php
//======================================================================
//----------------------------------------	
// ■　共通　require_once
//----------------------------------------	
require_once("com_require.php");
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//--------------------------------
	// □ POSTされたデータを取得
	//--------------------------------
	$log_no =$_POST["log_no"];					//プログラムログ番号
	$title = htmlspecialchars($_POST["title"], ENT_QUOTES);		//題名
	$content = htmlspecialchars($_POST["content"], ENT_QUOTES);	//内容
	//画像用変数
	$pic_name = $_FILES["pic"]["name"]; 				//ローカルファイル名 
	$pic_tmp = $_FILES["pic"]["tmp_name"]; 				//テンポラリファイルの名前 
	$pic_type = $_FILES["pic"]["type"]; 				//画像タイプ
	$pic_size = $_FILES["pic"]["size"]; 				//画像サイズ
	$chk_del = 0;							//画像削除フラグ(変更時のみ)
	if (isset($_POST["chk_pic"])){$chk_del = 1;}
	$upd_pic = "";							//初期画像名(変更時のみ)
	if (isset($_POST["upd_pic"])){$upd_pic = $_POST["upd_pic"];}
	
	//動画用変数
	$mov_name = $_FILES["mov"]["name"]; 				//ローカルファイル名 
	$mov_tmp = $_FILES["mov"]["tmp_name"]; 				//テンポラリファイルの名前 
	$mov_type = $_FILES["mov"]["type"]; 				//動画タイプ
	$mov_size = $_FILES["mov"]["size"]; 				//動画サイズ
	$chk_del_mov = 0;							//動画削除フラグ(変更時のみ)
	if (isset($_POST["chk_mov"])){$chk_del_mov = 1;}
	$upd_mov = "";							//初期動画名(変更時のみ)
	if (isset($_POST["upd_mov"])){$upd_mov = $_POST["upd_mov"];}
	//--------------------------------
	// □ 入力内容チェック
	//--------------------------------
	//題名
	if (strlen($title)==0){$error ="題名が未入力です";}
	//内容
	if (strlen($content)==0){$error ="内容が未入力です";}
	//ファイル(画像)
	if (strlen($pic_name)>0){
		if (is_uploaded_file($pic_tmp)) {
			if ($pic_size==0){$error="画像が不正です。";}
			if ($pic_size>3000000){$error="画像のサイズが大きすぎます。({$pic_size}バイト)";;}
			if ($pic_type=="image/gif"){$kaku="gif";}
			if ($pic_type=="image_png" || $pic_type=="image/x-png"){$kaku="png";}
			if ($pic_type=="image/jpeg" || $pic_type=="image/pjpeg"){$kaku="jpg";}
			if ($kaku ==""){$error ="画像種類に誤りがあります。(gif,png,jpgのみ対応しています。)";}
		}
	}
	//ファイル(動画)
	if (strlen($mov_name)>0){
		if (is_uploaded_file($mov_tmp)) {
			if ($mov_size==0){$error="動画が不正です。";}
			if ($mov_size>30000000){$error="動画のサイズが大きすぎます。({$mov_size}バイト)";;}
			if ($mov_type=="video/mp4"){$kaku="mp4";}
			if ($kaku ==""){$error ="動画種類に誤りがあります。(mp4のみ対応しています。)";}
		}
	}
	if (strlen($error)==0){
		//--------------------------------
		// □ 登録ボタンが押されたとき
		//--------------------------------
		if (isset($_POST["submit_add"])){
			//--------------------------------------------------
			// □ プログラム日記テーブル(programlog)を読む
			//--------------------------------------------------
			//ログの最大値を取得
			$log_no = 0;
			$stmt = $pdo->query("SELECT MAX(logno) AS maxno FROM programlog");
			if ($stmt->fetchColumn()>0){
				$sql = "SELECT MAX(logno) AS maxno FROM programlog";
				$stmt = $pdo->query($sql);
				$row = $stmt->fetch();
				$log_no = $row["maxno"];
			}
			$log_no++;
			//--------------------------------------------------
			// □ 画像の移動
			//--------------------------------------------------
			if (strlen($pic_name)>0){
				$ymdhis = date("YmdHis");
				$pic_name = "{$my_no}-{$log_no}-{$ymdhis}.{$kaku}";
				move_uploaded_file($pic_tmp, "$pic_path/$pic_name");
			}
			//--------------------------------------------------
			// □ 動画の移動
			//--------------------------------------------------
			if (strlen($mov_name)>0){
				$ymdhis = date("YmdHis");
				$mov_name = "{$my_no}-{$log_no}-{$ymdhis}.{$kaku}";
				move_uploaded_file($mov_tmp, "$mov_path/$mov_name");
			}
			//--------------------------------------------------
			// □ プログラム日記テーブル(programlog)に新規追加
			//--------------------------------------------------
			$sql = "INSERT INTO programlog VALUES(";
			$sql.= "$my_no,$log_no,'$title','$content','$pic_name','$mov_name',";
			$sql.= "now())";
			$stmt = $pdo->query($sql);
			$error = "登録が完了しました";
		}
		//--------------------------------
		// □ 変更ボタンが押されたとき
		//--------------------------------
		if (isset($_POST["submit_upd"])){
			//画像
			if ($chk_del == 0){
				if (strlen($pic_name)>0){
					//画像移動
					$ymdhis = date("YmdHis");
					$pic_name = "{$my_no}-{$log_no}-{$ymdhis}.{$kaku}";
					move_uploaded_file($pic_tmp, "$pic_path/$pic_name");
				}else{
					$pic_name = $upd_pic;
				}
			}else{
				if (strlen($pic)>0 && file_exists("$pic_path/$pic")){
					//画像削除
					unlink("$pic_path/$pic");	
				}
				$pic_name = "";//画像名クリア
			}
			//動画
			if ($chk_del_mov == 0){
				if (strlen($mov_name)>0){
					//動画移動
					$ymdhis = date("YmdHis");
					$mov_name = "{$my_no}-{$log_no}-{$ymdhis}.{$kaku}";
					move_uploaded_file($mov_tmp, "$mov_path/$mov_name");
				}else{
					$mov_name = $upd_mov;
				}
			}else{
				if (strlen($mov)>0 && file_exists("$mov_path/$mov")){
					//動画削除
					unlink("$mov_path/$mov");	
				}
				$mov_name = "";//動画名クリア
			}
			//--------------------------------------------------
			// □ プログラム日記テーブル(programlog)に新規追加
			//--------------------------------------------------
			$sql = "UPDATE programlog SET";
			$sql.= " title='$title',content='$content',pic='$pic_name',mov='$mov_name',upddate=now()";
			$sql.= " WHERE no=$my_no AND logno=$log_no";
			$stmt = $pdo->query($sql);
			$error = "変更が完了しました";
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
<title><?=$my_id ?> マイページ[プログラム日記を書く]</title>
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
	if ($error == "登録が完了しました" || $error == "変更が完了しました"){
		echo "<br><center><a href=\"./mypage.php\">マイページへ</a></center>\n";
		echo "</body>\n";
		echo "</html>";
		exit;
	}
}
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" enctype="multipart/form-data">
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
<td align="left" valign="top">
<table border="0" cellspacing="3" cellpadding="3"  width="100%">
<tr><td align="center" bgcolor="ffe4e1" colspan="2">
<font size="2">プログラム日記を書く</font></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">題名</font></td>
<td><input type="text" name="title" value="<?=$title ?>" size="60"></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">内容</font></td>
<td><textarea name="content" cols="50" rows="20"><?=$content?></textarea></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">画像</font></td>
<td><font size="2">
<input type="file" name="pic" size="60"></td></tr>
<tr><td align="center" bgcolor="#ffe4e1"><font size="2">動画</font></td>
<td><font size="2">
<input type="file" name="mov" size="60">
<?php
//----------------------------------------	
// ■　変更のとき画像表示
//----------------------------------------
//画像
if (strlen($pic)>0){
	$disp_pic = "http://$host/image/" .$pic;
	echo "<img src=\"$disp_pic\" border=\"0\"><br>";
	echo "<input type=\"checkbox\" name=\"chk_pic\" value=\"1\">削除\n";
	echo "<input type=\"hidden\" name=\"upd_pic\" value=\"$pic\">\n";
}
//動画
if (strlen($mov)>0){
	$disp_mov = "http://$host/movie/" .$mov;
	echo "<video src=\"$disp_mov\" border=\"0\"><br>";
	echo "<input type=\"checkbox\" name=\"chk_mov\" value=\"1\">削除\n";
	echo "<input type=\"hidden\" name=\"upd_mov\" value=\"$mov\">\n";
}
?>
</font></td></tr>
<tr><td align="center" colspan="2">
<input type="hidden" name="log_no" value="<?= $log_no?>">
<input type="reset" name="submit_reset" value="リセット">
<?php
//----------------------------------------	
// ■　新規登録/変更ボタン
//----------------------------------------
if ($log_no==0){
	echo "<input type=\"submit\" name=\"submit_add\" value=\"登録する\">";
}else{
	echo "<input type=\"submit\" name=\"submit_upd\" value=\"変更する\">";
}
?>
</td></tr>
</table>
</td></tr>
</table>
</form>
</body>
</html>