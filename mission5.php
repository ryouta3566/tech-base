<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>        
</head>
<body>
    
<?php
    //DB接続設定
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    // テーブル作成
     $sql = "CREATE TABLE IF NOT EXISTS mytable"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date char(32),"
	. "pass char(16)"
	.");";
	$stmt = $pdo->query($sql);
	
	if (!empty($_POST["chNumber"])){
	    $date = date("Y/m/d H:i:s");
	    $pass = $_POST["pass"];
	    $id = $_POST["chNumber"]; //変更する番号
	    $name = $_POST["name"];
	    $comment = $_POST["message"]; //変更したい名前、コメントは自分で決めること
	    
	    $sql = 'UPDATE mytable SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	    $stmt->execute();
	}else if(!empty($_POST["name"]) && !empty($_POST["message"]) && $_POST["pass"]=="pass"){
	    $sql = $pdo -> prepare("INSERT INTO mytable (name, comment,date ,pass)
        VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment =  $_POST["message"];//好きな名前、好きな言葉は自分で決めること
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["pass"];
        $sql -> execute();
	}else if(!empty($_POST["delete"]) && $_POST["pass"]=="pass"){
	    $sql = 'delete from mytable where id=:id and pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $id =$_POST["delete"];
            $pass =$_POST["pass"];
        $stmt->execute();
	}else if(!empty($_POST["change"]) && $_POST["pass"]=="pass"){
	    //編集番号とパスワードが一致している時に投稿フォームに表示させる
	    $id = $_POST["change"];
	    $pass = $_POST["pass"];
	    $sql = 'SELECT * FROM mytable WHERE id=:id and pass=:pass';
	    $stmt = $pdo->prepare($sql); //差し替えるパラメータを含めて記述したSQLを準備
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT); //その差し替えるパラメータの値を指定してから
	    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	    $stmt->execute(); //SQLを実行する
	    $results = $stmt->fetchAll();
	        if(!empty($results[0]['id'])){
                $change= $results[0]['id'];
            }
            if(!empty($results[0]['name'])){
                $chName= $results[0]['name'];
            }
            if(!empty($results[0]['comment'])){
                $chComment= $results[0]['comment'];
            }
            if(!empty($results[0]['pass'])){
                $chPass=$results[0]['pass'];
            }
	}
?>
 <form action="" method="post">
    <input type="text" name="name" placeholder="名前" 
    value="<?php if( !empty($chName) ){ echo $chName; } ?>">
    <input type="text" name="message" placeholder="コメント" 
    value="<?php if( !empty($chComment) ){ echo $chComment; } ?>">
    <input type="password" name="pass" placeholder="パスワード" 
    value="<?php if(!empty($chPass)){echo $chPass;}?>">
    <input type="submit" value="送信">
    <input type="hidden" name="chNumber" 
    value="<?php if( !empty($change) ){ echo $change; } ?>">
</form>
<form action="" method="post">
    <input type="number" name="delete" placeholder="削除番号">
    <input type="submit" value="削除">
    <input type="password" name="pass" placeholder="パスワード">
</form>
<form action="" method="post">
    <input type="number" name="change" placeholder="編集番号">
    <input type="submit" value="編集">
    <input type="password" name="pass" placeholder="パスワード">
</form>

<?php
    
    $sql = 'SELECT * FROM mytable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].' '.'<br>';
    }

?>
</body>
</html>