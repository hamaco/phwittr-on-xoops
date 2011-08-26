<if expr="$aclUser->isAuthenticated() || $isLoginPage">
  <!-- @todo ??? -->
<else />
  <h2>ここからログイン</h2>

  <form action="<?= uri("c: login, a: doLogin") ?>" method="post">
    <dl>
      <dt>ユーザ名かメールアドレス</dt>
      <dd><input type="text" name="username_or_email" /></dd>
      <dt>パスワード</dt>
      <dd><input type="password" name="password" /></dd>
    </dl>
    <div>
      <input type="submit" value="ログイン" />
    </div>
  </form>
</if>

<h2>ユーザ登録（無料）</h2>

<p>
  <hlink uri="c: register, a: prepare">ユーザ登録</hlink>
</p>
