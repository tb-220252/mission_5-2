<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    if(!empty($_POST["e_num"])){
    $sql = 'SELECT * FROM tbtest_4 WHERE id=:id AND password=:password';

    $id=$_POST["e_num"];
    $editpass=$_POST["e_pass"];
    $stmt = $pdo->prepare($sql);                  
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt -> bindParam(':password', $editpass,PDO::PARAM_STR);
    $stmt->execute();                    
	$results = $stmt->fetchAll();
    $row = $results[0];
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-2</title>
</head>
<body>
    <h1>Web掲示板</h1>
    <form action="" method="post">
        <input type="txt" name="name" placeholder="名前"
         value= <?php
                if(!empty($_POST["e_num"])){
                echo $row['name'];}
        ?>><br>
        <input type="txt" name="comment" placeholder="コメント"
         value= <?php
                if(!empty($_POST["e_num"])){
                echo $row['comment'];}     
        ?>><br>
        <input type="password" name="pass" placeholder="パスワード" ><br>
        <input type="number" name="ch_num" 
         value= <?php
                if(!empty($_POST["e_pass"])){
                    echo $row['id'];
                }
       ?> >
        <!--編集モード指定時のセキュリティを作成-->
        <input type="submit" name="submit"><br>
        
        <input type="number" name="del_num" placeholder="削除対象番号"><br>
        <input type="password" name="del_pass" placeholder="パスワード" >
        <input type="submit" name="del_submit" value="削除"><br><br>
        
        <input type="number" name="e_num" placeholder="編集対象番号"><br>
        <input type="password" name="e_pass" placeholder="パスワード" >
        <input type="submit" name="e_submit" value="編集">
    </form>
    <?php

        

        $sql = "CREATE TABLE IF NOT EXISTS tbtest_4"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);//statement.query≒prepareユーザーの入力情報を含むか否かで区別。
        
     
        
        //編集機能
        if(!empty($_POST["submit"])){            //送信ボタンが押されて
        
            if(empty($_POST["ch_num"])){    
                if(empty($_POST["name"])){
                    echo "名前を入力してください<br>";
                }elseif(empty($_POST["comment"])){
                    echo "コメントを入力してください<br>";
                }elseif(empty($_POST["pass"])){
                    echo "送信パスワードを入力してください<br>";
                }else{
                    $sql = $pdo -> prepare("INSERT INTO tbtest_4 (name, comment, date, password) VALUES (:name, :comment,:date, :password)");
                    $sql -> bindParam(':name', $name,PDO::PARAM_STR);
                    //bindParam：割り当てる、パラメーターの
                    $sql -> bindParam(':comment', $comment,PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date,PDO::PARAM_STR);
                    $sql -> bindParam(':password', $pass,PDO::PARAM_STR);
	                $name=$_POST["name"];
                    $comment=$_POST["comment"];//好きな名前、好きな言葉は自分で決めること
                    $date=date("Y/m/d H:i:s");
                    $pass=$_POST["pass"];
	                $sql -> execute();//実行する
                            echo "投稿されました<br>";
                }
            
            }else{
                   
                    
                if(!empty($_POST["pass"])){
                  
                
                      
                        $edit_pass=$_POST["pass"];
                        $id = $_POST["ch_num"]; //変更する投稿番号
                        $name = $_POST["name"];
                        $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
                        $date=date("Y/m/d H:i:s");
                        
                       
                        $sql = 'UPDATE tbtest_4 SET name=:name,comment=:comment,password=:password ,date=:date WHERE id=:id ';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->bindParam(':password', $edit_pass, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                            
                            
                            echo "編集されました<br>";
                   
                }
            }
        }//ifの終わり

        //削除機能
        if(!empty($_POST["del_submit"])){ //もし削除番号が押されたら
            if(empty($_POST["del_num"]) && empty($_POST["del_pass"])){
                            echo "削除したい番号を入力してください<br>";
            }//ifの終わり
            elseif(!empty($_POST["del_num"]) && empty($_POST["del_pass"])){
                    echo "削除パスワードを入力してください<br>";
            }//elseifの終わり
            elseif(!empty($_POST["del_num"]) && !empty($_POST["del_pass"])){
                    
                    $delete=$_POST["del_num"];
                    $del_pass=$_POST["del_pass"];
                   
                        // データベース内のパスワード情報と合致しているかを捜査するプログラムが欲しい
                    $id = $_POST["del_num"];
                    $sql = 'delete from tbtest_4 where id=:id and password=:password';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':password', $del_pass, PDO::PARAM_STR);
                    $stmt->execute();
                        echo "削除が実行されました<br>";
                    //errorメッセージをはかせたいが、やる方法が不明。データベースから引っ張ってきても捜査する分岐ができない。
                    }
            //elseifの終わり
        }//elseifの終わり
        
        if(!empty($_POST["e_submit"])){
            
            if(empty($_POST["e_num"])){
                echo "編集したい番号を入力してください<br>";
            }elseif(empty($_POST["e_pass"])){
                echo "編集パスワードを入力してください<br>";
            }elseif(!empty($_POST["e_num"]) && !empty($_POST["e_pass"])){
                $edit=$_POST["e_num"];
               //パスワードあってるときはhtmlの編集モードスイッチに指定番号を表示
                    
               
            }//elseifの終わり
        }//ifの終わり
        

        //③ブラウザへの表示機能
        $sql = 'SELECT * FROM tbtest_4';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        echo "<hr>";
        }
        
    ?>
</body>
</html>