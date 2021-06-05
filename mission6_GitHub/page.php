<html>
<head>
	<title>mission6</title>
	<meta charset="utf-8">
</head>
<body>

<?php
//======================================================================
//  ■： ページコントロール page.php
//======================================================================
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td align="right" valign="top">
<font size="2">
<?php
//-------------------------------------------
//  □： 前のページ,次のページ表示
//-------------------------------------------
$backpage=$page-1;
$nextpage=$page+1;
if ($backpage<=1){$backpage=1;}
if ($nextpage>=$maxpage){$nextpage=$maxpage;}
if ($page>1){
	echo "<a href=\"{$_SERVER["PHP_SELF"]}?page=$backpage\">前のページ</a>&nbsp;&nbsp;";
}
$from = $start + 1;
$to =$start + $list_count;
echo "{$from}件目から{$to}件目までを表示";
if ($page<$maxpage){
	echo "&nbsp;&nbsp;<a href=\"{$_SERVER["PHP_SELF"]}?page=$nextpage\">次のページ</a>\n";
}
?>
</font>
</td>
</tr>
</table>



</body>
</html>