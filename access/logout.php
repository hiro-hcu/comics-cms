<?php
  /**
   * 課題3：セッションが確立しているとき，セッションを破棄してログアウトする処理を書いてください
   */
  session_start();
  session_destroy();
  echo "ログアウトしました。<br/>";
  header("Location: login");
?>

