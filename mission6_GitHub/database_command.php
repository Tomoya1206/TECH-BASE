<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>

<body>

<?php
/*3-1*/
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

/*テーブル削除*/
/*
$sql='DROP TABLE programlog';
$stmt=$pdo->query($sql);
*/

/*3-2
$sql="CREATE TABLE IF NOT EXISTS mission4_id"
."("
."id TEXT"
.");";

$stmt=$pdo->query($sql);
*/
/*
$sql="CREATE TABLE IF NOT EXISTS programlog"
."("
."no int(11),"
."logno int(11),"
."title varchar(100),"
."content text,"
."pic varchar(200),"
."mov varchar(200),"
."upddate date,"
."PRIMARY KEY(logno)"
.");";

$stmt=$pdo->query($sql);
*/

/*3-3*/
$sql='SHOW TABLES';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[0];
	echo '<br>';
	}
echo"<hr>";

/*3-4*/
$sql='SHOW CREATE TABLE friends';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[1];
	}
echo"<hr>";

$sql='SHOW CREATE TABLE friendinfo';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[1];
	}
echo"<hr>";

$sql='SHOW CREATE TABLE friendmsg';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[1];
	}
echo"<hr>";

$sql='SHOW CREATE TABLE programlog';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[1];
	}
echo"<hr>";

$sql='SHOW CREATE TABLE programres';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[1];
	}
echo"<hr>";

/*3-5
$sql=$pdo->prepare("INSERT INTO mission4_id(id) VALUES(:id)");
$sql->bindParam(':id',$id,PDO::PARAM_STR);
$id='1';
$sql->execute();
*/

/*3-8*/
/*$no=0;
$sql='delete from friendinfo where no=0';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':no',$id,PDO::PARAM_INT);
$stmt->execute();
*/


/*3-6*/

$sql='SELECT*FROM friends';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
echo "friends:<br/>\n";
foreach($results as $row){
	echo $row['no'].',';
	echo $row['name'].',';
	echo $row['birth'].',';
	echo $row['email'].'<br>';
}

$sql='SELECT*FROM friendinfo';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
echo "friendinfo:<br/>\n";
foreach($results as $row){
	echo $row['no'].',';
	echo $row['usrid'].',';
	echo $row['usrpw'].',';
	echo $row['msg'].',';
	echo $row['upddate'].'<br>';
}

$sql='SELECT*FROM friendmsg';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
echo "friendmsg:<br/>\n";
foreach($results as $row){
	echo $row['no'].',';
	echo $row['msgno'].',';
	echo $row['tomono'].',';
	echo $row['title'].',';
	echo $row['content'].',';
	echo $row['readflg'].',';
	echo $row['upddate'].'<br>';
}

$sql='SELECT*FROM programlog';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
echo "programlog:<br/>\n";
foreach($results as $row){
	echo $row['no'].',';
	echo $row['logno'].',';
	echo $row['title'].',';
	echo $row['content'].',';
	echo $row['pic'].',';
	echo $row['mov'].',';
	echo $row['upddate'].'<br>';
}

$sql='SELECT*FROM programres';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
echo "programres:<br/>\n";
foreach($results as $row){
	echo $row['no'].',';
	echo $row['logno'].',';
	echo $row['resno'].',';
	echo $row['tomono'].',';
	echo $row['contentres'].',';
	echo $row['readflg'].',';
	echo $row['upddate'].'<br>';
}

/*$sql='SELECT*FROM mission4_id';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
	echo $row['id'];
}
*/

?>


</body>
</html>

