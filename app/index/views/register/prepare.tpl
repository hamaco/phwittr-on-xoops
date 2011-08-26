<h2>ユーザ登録（無料）</h2>

<div class="commonForm">
  <formerr form="user" />
  
  <?e $userForm->open("a: doRegister", "common") ?>
    <table>
      <tr>
        <th><?e $userForm->name("user_name") ?>:</th>
        <td><?e $userForm->text("user_name") ?></td>
      </tr>
      <tr>
        <th><?e $userForm->name("password") ?>:</th>
        <td><?e $userForm->password("password") ?></td>
      </tr>
      <tr>
        <th><?e $userForm->name("email") ?>:</th>
        <td><?e $userForm->text("email") ?></td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" value="同意し、アカウントを登録する" />
        </td>
      </tr>
    </table>
  <?e $userForm->close() ?>
  
  <?php echo Helpers_Js::formValidator($userForm) ?>
</div>
