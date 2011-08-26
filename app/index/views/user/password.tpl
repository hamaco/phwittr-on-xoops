<partial name="settingsmenu" />

<div class="commonForm settings">
  <partial name="error" />
  
  <form class="common" action="<?= uri("a: password") ?>" method="post">
    <table>
      <tr>
        <th>新規パスワード:</th>
        <td><input type="password" name="password" value="<?= $password ?>" /></td>
      </tr>
      <tr>
        <th>再入力:</th>
        <td><input type="password" name="password2" value="<?= $password2 ?>" /></td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
          <input type="submit" value="パスワード更新" />
        </td>
      </tr>
    </table>
  </form>
</div>
