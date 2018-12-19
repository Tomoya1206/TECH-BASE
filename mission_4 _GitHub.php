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
	/*追加または編集*/
	if(isset($_POST["submit_add"])){
		$edit2 = htmlspecialchars($_POST["edit2"],ENT_QUOTES);
	
		/*追加モード*/
		if(empty($edit2)){
		
			/*idは、mission4_idのデータベース参照*/
			$sql='SELECT*FROM mission4_id';
			$stmt=$pdo->query($sql);
			$results=$stmt->fetchAll();
			foreach($results as $row){
				$id= $row['id'];
			}
			/*ポストされたデータを取得*/
			$new_name = htmlspecialchars($_POST["new_name"],ENT_QUOTES);
			$new_comment = htmlspecialchars($_POST["new_comment"],ENT_QUOTES);
			$new_pw = htmlspecialchars($_POST["new_pw"],ENT_QUOTES);
			$date = date("Y/m/d H:i:s");
			
			/*エラーチェック*/
			if((empty($new_name))||(empty($new_comment))||(empty($new_pw))){
				$error = "すべての欄を埋めてください。";
				}
			else{
				$sql=$pdo->prepare("INSERT INTO mission4(id,name,comment,date,pass) VALUES(:id,:name,:comment,:date,:pass)");
				$sql->bindParam(':id',$id,PDO::PARAM_INT);
				$sql->bindParam(':name',$new_name,PDO::PARAM_STR);
				$sql->bindParam(':comment',$new_comment,PDO::PARAM_STR);
				$sql->bindParam(':date',$date,PDO::PARAM_STR);
				$sql->bindParam(':pass',$new_pw,PDO::PARAM_STR);
				$sql->execute();
				
				/*＋1したidをmission4_idに編集して代入*/
				$id++;
				$sql = 'update mission4_id set id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();

			}
		}
		
		/*編集モード*/
		if(is_numeric($edit2)){
			$id = $edit2;
			$edit_name = htmlspecialchars($_POST["new_name"],ENT_QUOTES);
			$edit_comment = htmlspecialchars($_POST["new_comment"],ENT_QUOTES);
			
			/*編集後のパスワードは入力されれば更新され、入力されなければもとのパスワード*/
			$edit_pw = htmlspecialchars($_POST["new_pw"],ENT_QUOTES);
			if(empty($edit_pw)){
				$sql='SELECT*FROM mission4';
				$stmt=$pdo->query($sql);
				$results=$stmt->fetchAll();
				foreach($results as $row){
					if($edit2==$row['id']){
						$edit_pw = $row['pass'];
					}
				}
			}
			
			$date = date("Y/m/d H:i:s");
			$sql = 'update mission4 set name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name',$edit_name,PDO::PARAM_STR);
			$stmt->bindParam(':comment',$edit_comment,PDO::PARAM_STR);
			$stmt->bindParam(':date',$date,PDO::PARAM_STR);
			$stmt->bindParam(':pass',$edit_pw,PDO::PARAM_STR);
			$stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$stmt->execute();	
		}
	}
	
	/*削除*/
	if(isset($_POST["submit_del"])){
		$del = htmlspecialchars($_POST["del"],ENT_QUOTES);
		$pw_del = htmlspecialchars($_POST["pw_del"],ENT_QUOTES);
		if(is_numeric($del)&&!empty($pw_del)){
			$sql='SELECT*FROM mission4';
			$stmt=$pdo->query($sql);
			$results=$stmt->fetchAll();
			foreach($results as $row){
				if($del==$row['id']){
					$pass = $row['pass'];
				}
			}
			
			/*パスワード確認*/
			if($pass == $pw_del){
				$id=$del;
				$sql='delete from mission4 where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();
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
	<input type="text" name="new_name" maxlength="255" value="" >
	<br>コメント：
	<input type="text" name="new_comment" maxlength="255" value="" >
	<br>パスワード：
	<input type="password" name="new_pw" maxlength="255" value="" >
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
