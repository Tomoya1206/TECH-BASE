<?php
//======================================================================
//  ■： メッセージ一覧画面 friendmsglist.php
//======================================================================
//----------------------------------------	
// ■　共通　require_once
//----------------------------------------	
require_once("com_require.php");
//----------------------------------------	
// ■　POSTされたとき
//----------------------------------------	
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	//-----------------------------------------
	// □ メッセージの削除ボタンが押されたとき
	//-----------------------------------------
	if (isset($_POST["submit_del"])){
		$msg_no = key($_POST[submit_del]);
		$sql = "DELETE FROM friendmsg WHERE no='$my_no' AND msgno='$msg_no'";	
		$stmt = $pdo->query($sql);
		$error = "メッセージを1件削除しました";
	}
}
//=====================================================================
// ■　H T M L
//=====================================================================
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title><?=$my_id ?> マイページ[メッセージ一覧]</title>
</head>
<body>
<?php
//----------------------------------------	
// ■　ヘッダーの取り込み
//----------------------------------------	
require_once("header.php");
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
<td align="left" valign="top">
<font color=\"#6b8e23\" size="2">最新の<?=LIST_MSG_COUNT ?>件</font>
<table border="1" cellspacing="0" cellpadding="3" width="100%" bordercolor="#666666">
<tr bgcolor="<?=MY_COLOR ?>">
<td align="center"><font size="2">未読</font></td>
<td align="center"><font size="2">差出人</font></td>
<td align="center"><font size="2">タイトル</font></td>
<td align="center"><font size="2">送信日時</font></td>
<td>　</td>
</tr>
<?php
//-----------------------------------------------------
// □：メッセージテーブル(friendmsg)からデータを読む
//-----------------------------------------------------
$sql = "SELECT * FROM friendmsg";
$sql.= " WHERE no = '$my_no'";
$stmt = $pdo->query($sql);
$allkensu = $stmt->fetchColumn();
$maxpage=intval($allkensu/LIST_MSG_COUNT);
if ($allkensu % LIST_MSG_COUNT > 0){$maxpage++;}
$start = ($page-1)*LIST_MSG_COUNT;

$sql = "SELECT friendmsg.*,friendinfo.usrid as tomoid FROM friendmsg";
$sql.= " LEFT JOIN friendinfo ON friendmsg.tomono = friendinfo.no";
$sql.= " WHERE friendmsg.no='$my_no' ORDER BY friendmsg.msgno DESC LIMIT $start," .LIST_MSG_COUNT;
$stmt = $pdo->query($sql);
$count = 0;
while($row = $stmt->fetch()){
	$msg_no = $row["msgno"];
	$readflg ="&nbsp;";
	if ($row["readflg"]==0){$readflg ="★";	}
	echo <<<EOT
<tr>
<td align="center"><font size="2" color="ff6347">{$readflg}</font></td>
<td><font size="2"><a href="./mypage.php?usr_no={$row["tomono"]}">{$row["tomoid"]}さん</a></font></td>
<td><font size="2"><a href="./friendmsgread.php?msg_no=$msg_no">{$row["title"]}</a></font></td>
<td><font size="2">{$row["upddate"]}</font></td>
<td><input type="submit" name="submit_del[$msg_no]" value="削除"></td>
</tr>
EOT;
}
//ここまでwhileループ[終了の閉じカッコ]
?>
</table>
<?php
//----------------------------------------	
// ■　ページコントロールの取り込み
//----------------------------------------
$list_count = LIST_MSG_COUNT;
require_once("page.php");
?>
</td>
</tr>
</table>
</form>
</body>
</html>