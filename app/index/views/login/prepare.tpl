<h2>ログイン</h2>

<div class="commonForm">
  <partial name="error" />
  
  <form class="common" action="<?= uri("a: doLogin") ?>" method="post">
    <table>
      <tr>
        <th>ユーザ名 or <br/>メールアドレス</th>
        <td><input tabindex="1" type="text" name="username_or_email" value="<?= $username_or_email ?>" /></td>
      </tr>
      <tr>
        <th>パスワード</th>
        <td><input tabindex="2" type="password" name="password" value="<?= $password ?>" /></td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <div class="formButton">
            <input type="hidden" name="back_uri" value="<?= $aclUser->back_uri ?>" />
            <input tabindex="3" type="submit" value="ログイン" />
          </div>
        </td>
      </tr>
    </table>
  </form>
</div>
