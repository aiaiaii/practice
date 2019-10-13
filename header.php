    <header>
      <div class="site-width">
        <h1><a href="index.html">WEBUKATU MARKET</a></h1>
        <nav id="top-nav">
          <ul>
           <?php
            if(empty($_SESSION['user_id'])){
            ?>
            <li><a href="signup.html" class="btn btn-primary">ユーザー登録</a></li>
            <li><a href="login.php">ログイン</a></li>
             <?php
              }else{
            ?>
            <li><a href="signup.html">マイページ</a></li>
            <li><a href="logout.php">ログイン</a></li>
            <?php
            }
            ?>
          </ul>
        </nav>
      </div>
    </header>