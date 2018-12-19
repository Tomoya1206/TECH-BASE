<html>
<head>
	<title>mission_4</title>
	<meta charset="utf-8">
</head>

<body>

<?php
/*データベースへ接続*/
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

/*テーブルの作成*/
$sql="CREATE TABLE IF NOT EXISTS mission4"
."("
."id INT,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
."pass TEXT"
.");";

$stmt=$pdo->query($sql);

/*POST送信された時に処理を実行*/
if ($_SERVER["REQUEST_METHOD"]=="POST"){

/*編集する値をフォームに渡す*/
	if(isset($_POST["submit_edit"])){
		$edit = htmlspecialchars($_POST["edit"],ENT_QUOTES);
		$pw_edit = htmlspecialchars($_POST["pw_edit"],ENT_QUOTES);
		if(is_numeric($edit)&&!empty($pw_edit)){
			$sql='SELECT*FROM mission4';
			$stmt=$pdo->query($sql);
			$results=$stmt->fetchAll();
			foreach($results as $row){
				if($edit==$row['id']){
					$pass = $row['pass'];
				}
			}
			
			/*パスワード確認*/
			if($pass == $pw_edit){
				$sql='SELECT*FROM mission4';
				$stmt=$pdo->query($sql);
				$results=$stmt->fetchAll();
				foreach($results as $row){
					if($edit==$row['id']){
						$edit_name = $row['name'];
						$edit_comment = $row['comment'];
						}
					}
			}
			else{
				$error = "パスワードが違っています。";
			}
		}

	}
}
?>

<?php
/*エラー文を表示*/
echo $error."<br/>\n";
?>

<form action="mission_4.php" method="post">
	<コメントリストに追加><br>
	名前：
	<input type="text" name="new_name" maxlength="255" value="<?php echo $edit_name;?>" >
	<br>コメント：
	<input type="text" name="new_comment" maxlength="255" value="<?php echo $edit_comment;?>" >
	<br>パスワード：
	<input type="password" name="new_pw" maxlength="255" value="" >
	<input type="hidden" name="edit2"  maxlength="255" value="<?php echo $edit;?>">
	<input type="submit" name="submit_add" maxlength="255" value="送信" >
</form>

<form action="mission_4.php" method="post">
	<br><コメントリストを削除>
	<br>削除対象番号(半角数字)：
	<input type="text" name="del"  maxlength="255" value="">
	<br/>パスワード：
	<input type="password" name="pw_del"  maxlength="255" value="">
	<input type="submit" name="submit_del" value="削除">
</form>
<br>

<!--編集番号は'mission_4_edit'に送る-->
<form action="mission_4_edit.php" method="post">
	<コメントリストを編集>
	<br>編集対象番号(半角数字)：
	<input type="text" name="edit"  maxlength="255" value="">
	<br/>パスワード：
	<input type="password" name="pw_edit"  maxlength="255" value="">
	<input type="submit" name="submit_edit" value="編集">
</form>

<h2>*コメントリスト*</h2>

<?php
/*テーブルからデータを読み込む*/
$sql='SELECT*FROM mission4 ORDER BY id';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
}
?>

</body>
</html>

