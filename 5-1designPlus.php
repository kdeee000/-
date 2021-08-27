<html>
<head>
    <title>5-1掲示板</title>
    <link rel="stylesheet" href="5-1stylesheet.css">
</head>
<body>
    <div class="fullPage">
        <div class="top">
            <h1>掲示板</h1>
        </div>

        <?php

        // DB接続設定
        $dsn = 'データベース';
        $user = 'ユーザー名';
        $dataPass = 'パスワード';
        $pdo = new PDO($dsn, $user, $dataPass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS m5test"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "created_on DATETIME,"
            . "password char(10)"
            .");";
            $stmt = $pdo->query($sql);

        $date=date('Y-m-d H:i:s');
        $newnumber="";
        $newname="";
        $newcomment="";
        $newpass=""; 
        $password="";

        //編集するとき
        if(isset($_POST["edit"]) && !empty($_POST["editno"])&& !empty($_POST["ediPass"])) {

            //変数を設定する
            $id = $_POST["editno"];
            $password = $_POST["ediPass"];

            //データを探す
            $sql = 'SELECT * FROM m5test WHERE id=:id and password=:password';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 

            //配列を取り出す
            foreach ($results as $row){

                $newnumber = $row['id']; 
                $newname = $row['name'];
                $newcomment = $row['comment'];
                $newpass=$row['password']; 
            }

                
            
        } 


        //投稿されたとき
        if(isset($_POST["submit"])){
            //全てが埋まっているとき
            if(!empty($_POST["name"])&& !empty($_POST["comment"])&& !empty($_POST["pass"])){

                //投稿は投稿でも編集したものを投稿する場合
                if(!empty($_POST["editPost"])){
                    echo $_POST["editPost"]. "番を編集しました。";

                    //変数を設定する
                    $id = $_POST["editPost"]; 
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $password = $_POST["pass"];
                    //日付を設定する
                    $DATETIME = new DateTime();
                    $DATETIME = $DATETIME->format('Y-m-d H:i:s');

                    //アップデートする
                    $sql = 'UPDATE m5test SET name=:name,comment=:comment,password=:password,created_on=:created_on WHERE id=:id and password=:password';
                    $stmt = $pdo->prepare($sql);


                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt ->bindValue(':created_on', $DATETIME, PDO::PARAM_STR);
                    $stmt->execute();
                        
                    

                }

                
                else{
                //投稿は投稿でも新規投稿の場合

                    //変数を設定する
                    $name = $_POST['name'];
                    $comment = $_POST['comment'];
                    $password = $_POST['pass'];
                    //日付を入れる
                    $DATETIME = new DateTime();
                    $DATETIME = $DATETIME->format('Y-m-d H:i:s');
                    
                    //インサートする
                    $sql = $pdo -> prepare("INSERT INTO m5test (name, comment, password, created_on) VALUES (:name, :comment, :password, :created_on)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $sql ->bindValue(':created_on', $DATETIME, PDO::PARAM_STR);
                    //実行する
                    $sql -> execute();
                }


            }else{ echo "空欄があります"; }
            
        }


        //削除するとき
        if(isset($_POST["delete"]) && !empty($_POST["deleteno"])&& !empty($_POST["delePass"])){
            //変数を設定する
            $id = $_POST["deleteno"];
            $password = $_POST["delePass"];

            //IDとパスが一緒の行を削除する
            $sql = 'DELETE from m5test where id=:id and password=:password';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();

        }





        ?>

        <form method="POST" action="">
            <!--hidden群-->
                <input type="hidden" name="editPost" value="<?php echo $newnumber; ?>">

            <!--フォーム群-->
            <div class="form">
                <h2 class="title_under">・投稿フォーム・</h2>
                    <div class="forms">
                        <h3>なまえ</h3>
                            <input type="text" name="name" placeholder="なまえ" value="<?php echo $newname; ?>"><br>

                        <h3>コメント</h3>
                            <input type="text" name="comment" placeholder="コメント" value="<?php echo $newcomment; ?>"><br>

                        <h3>パスワード</h3>
                            <input type="password" name="pass" placeholder="pass" value="<?php echo $newpass; ?>"></p><br><br>
                        <!-- 投稿ボタン -->
                            <input type="submit" name="submit" value="投稿" class="button">
                    </div>
                    <div class="forms">
                        <h3>削除番号</h3>
                            <input type="number" name="deleteno" placeholder="削除">

                            
                        <h3>パスワード</h3>
                            <input type="password" name="delePass" placeholder="pass"></p><br><br>
                        <!-- 削除ボタン -->
                            <input type="submit" name="delete" value="削除" class="button">
                    </div>
                    <div class="forms">
                        <h3>編集番号</h3>
                            <input type="number" name="editno" placeholder="編集">
                            
                        <h3>パスワード</h3>
                            <input type="password" name="ediPass" placeholder="pass"></p><br>
                        <!-- 編集ボタン -->
                            <input type="submit" name="edit" value="編集" class="button">
                    </div>

            </div>

            </form>
        <div class="main">
            <h2 class="title_under">・コメント一覧・</h2><br>
            <?php
            $sql = 'SELECT * FROM m5test';
            
                $stmt = $pdo->query($sql);

                $results = $stmt->fetchAll();
                //fetchAll=SQL文の結果を取り出す
                
                foreach ($results as $row){

                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['created_on'].'<br>';
                echo "<hr>";
                }
            ?>
        </div>
    </div>

</body>
</html>
