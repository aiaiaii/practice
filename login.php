<?php
//共通変数・関数の読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
//ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  
  //変数にユーザー情報を格納
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false; 
  
  //emailの形式チェック
  validEmail($email,'email');
  //emailの最大文字数チェック
  validMaxLen($email,'email');
  
  //パスワードの半角英数字チェック
  validHalf($pass,'pass');
  //パスワードの最大文字数チェック
  validMaxLen($pass,'pass');
  //パスワードの最小文字数チェック
  validMinLen($pass,'pass');
  
  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  
  if(empty($err_msg)){
    debug('バリデーションOKです');
    
    //例外処理
    try{
      //DB接続
      $dbh = dbConnect();
      //SQL分作成
      $sql = 'SELECT password,id FROM users WHERE email = :email';
      $data=array(':email' => $email);
      //クエリ実行
      $stmt = queryPost($dbh,$sql,$data);
      //クエリ結果の取得
      $result = $stmt-> fetch(PDO::ASSOC);
      
      debug('クエリ結果の中身：'.print_r($result,true));
      
      //パスワード照合
      if(!empty($result) && password_verify($pass,array_shift($result))){
        debug('パスワードがマッチしました');
        
        //ログイン有効期限（デフォルトを１時間とする）
        $sesLimit = 60*60;
        //最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();
        
        //ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持にチェックがあります。');
          //ログイン有効期限を30日にしてセット
          $_SESSION['login_limit'] = $sesLimit *24*30;
        }else{
          debug('ログイン保持にチェックはありません。');
          //次回はログイン保持しないので、ログイン有効期限を１時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        //ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];
        
        debug('セッション変数の中身：'.print_r($_SESSION,true));
        debug('マイページへ遷移します。');
        header("Location:mypage.php");
      }else{
        debug('パスワードがアンマッチです。');
        $err_msg['common'] = MSG09;
      }
        
    }catch(Exception $e){
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<!DOCTYPE html>
<html lang="ja">

  <?php
  $siteTitle = 'ログイン';
  require('head.php');
  ?>

  <body class="page-login page-1colum">

    <!-- メニュー -->

    <?php
    require('header.php');
    ?>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      
      <!-- Main -->
      <section id="main">
        <div class="form-container">
          <form action="" method="post" class="form">
            <h2 class="title">ログイン</h2>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['email'])) echo 'err' ; ?>">
              メールアドレス
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
              パスワード
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </div>
            <label>
              <input type="checkbox" name="pass_save">次回ログインを省略する
            </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="ログイン">
            </div>
            パスワードを忘れた方は<a href="passRemindSend.php">こちら</a>
          </form>
        </div>
      </section>
    </div>
    
   <!-- footer -->
  <footer id="footer">
    Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>. All Rights Reserved.
  </footer>
  
  <script src="js/vendor/jquery-2.2.2.min.js"></script>
  <script>
    $(function(){
      var $ftr = $('#footer');
      if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight()){
        $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'});
      }
    });
    </script>