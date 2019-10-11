<?php

//================================
// ログイン認証・自動ログアウト
//================================
//ログインしている場合
if(!empty($_SESSION['login_date'])){
  debug('ログイン済みユーザーです');
  
  //ssめめた場合
  if(($_SESSION['login_date'] + $_SESSION_['login_limit']) < time()){
    debug('ログイン有効期限オーバーです。');
      
  //セッションを削除
    session_destroy();
    //ログインページへ
    header("Location:login.php");
  }else{
    debug('ログイン有効期限ないです');
    //最終ログイン日時を現在日時に更新
    $_SESSION['login_date'] = time();
    debug('マイページへ遷移します。');
    if(basename($_SERVER['PHP_SELF']) === 'login.php');
    header("Location:mypage.php");
  }

}else{
  debug('未ログインユーザーです。');
}