<partial name="settingsmenu" />

<div class="commonForm settings">
  <formerr form="settings" />
  
  <?e $settingsForm->open("a: settings", "common") ?>
    <table>
      <tr>
        <th><?e $settingsForm->name("user_name") ?>:</th>
        <td><?e $settingsForm->text("user_name") ?></td>
      </tr>
      <tr>
        <th><?e $settingsForm->name("email") ?>:</th>
        <td><?e $settingsForm->text("email") ?></td>
      </tr>
      <tr>
        <th><?e $settingsForm->name("private_flag") ?>:</th>
        <td>
          <?e $settingsForm->radio("private_flag", array("公開", "非公開")) ?><br/>
          <span class="explain">非公開に設定すると、あなたのつぶやきを友だちにだけに見せることができます。また、「公開中のつぶやき」に表示されることもありません。</span>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
          <input type="hidden" name="mode" value="confirm" />
          <input type="submit" value="確認画面へ" />
        </td>
      </tr>
    </table>
  <?e $settingsForm->close() ?>
  
  <?php echo Helpers_Js::formValidator($settingsForm) ?>
  
  <div class="withdrawal">
    <hlink uri="c: user, a: withdraw">アカウントを削除</hlink>
  </div>
</div>
