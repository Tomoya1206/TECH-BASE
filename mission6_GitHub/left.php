<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php

//データベースに接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));


//===========================================================================
// ■ 左バーの表示内容記述　left.php
//===========================================================================
if ($usr_no==$my_no){
	$color = MY_COLOR;
}else{
	$color = TOMO_COLOR;
}
?>
<td align="left" valign="top" bgcolor="<?=$color?>" width="200">
<font size="2">
<?php
//-----------------------------------------------------
// □：友達情報テーブル(friendinfo)からデータを読む
//-----------------------------------------------------
$sql = "SELECT * FROM friendinfo WHERE no='$usr_no'";
$stmt = $pdo->query($sql);
if ($stmt->fetchColumn()>0){
	$sql = "SELECT * FROM friendinfo WHERE no='$usr_no'";
	$stmt = $pdo->query($sql);
	$row = $stmt->fetch();
	$msg = $row["msg"];
	$usr_id = $row["usrid"];
	echo "<b>[$usr_id]</b>";
	if ($my_no<>$usr_no){
		echo "さん";
	}
	echo "のヒトコト<p>";
	get_url($msg);
	echo "<font color=\"#2b8e57\">$msg</font>";
}
?>
<br><br>

<b>最新<?=LIST_NEW_COUNT ?>件プログラミング日記</b><p>
<?php
//-----------------------------------------------------
// □：プログラムテーブル(programlog)からデータを読む
//-----------------------------------------------------
$sql = "SELECT programlog.*,friendinfo.usrid as usrid FROM programlog";
$sql.= " LEFT JOIN friendinfo ON programlog.no=friendinfo.no";
$sql.= " ORDER BY logno DESC LIMIT 0," .LIST_NEW_COUNT;
$stmt = $pdo->query($sql);
while($row = $stmt->fetch()){
	$tm_no = $row["no"];
	$tm_id = $row["usrid"];
	$logno = $row["logno"];
	if ($tm_no<>$my_no){
		$tm_id.="さん";
	}
	$tm_title = mb_substr($row["title"],0,14) ."...";
	$tm_date = $row["upddate"];
	echo "<a href=\"./programread.php?usr_no=$tm_no&log_no=$logno\">$tm_id:$tm_title</a><p>";
}
?>
<b>お友達リンク</b><p>
<?php
//-----------------------------------------------------
// □：友達情報テーブル(friendinfo)からデータを読む
//-----------------------------------------------------
$sql = "SELECT * FROM friendinfo WHERE no<>'$my_no'";
$stmt = $pdo->query($sql);
while($row = $stmt->fetch()){
	$tomo_no = $row["no"];
	$tomo_id = $row["usrid"];
	if ($tomo_no<>$my_no){
		$tomo_id.="さん";
	}
	echo "♪<a href=\"./mypage.php?usr_no=$tomo_no\">$tomo_id</a><p>";
}
?>
</font>
</td>

</body>
</html>