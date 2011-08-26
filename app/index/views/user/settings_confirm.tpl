<partial name="settingsmenu" />

<div class="commonForm settings">
  <table>
    <tr>
      <th><?e $settingsForm->name("user_name") ?>:</th>
      <td><?= $settingsForm->user_name ?></td>
    </tr>
    <tr>
      <th><?e $settingsForm->name("email") ?>:</th>
      <td><?= $settingsForm->email ?></td>
    </tr>
    <tr>
      <th><?e $settingsForm->name("private_flag") ?>:</th>
      <td>
        <if expr="$settingsForm->private_flag">
        非公開
        <else />
        公開
        </if>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="button">
        <form action="<?= uri("a: settings") ?>" method="post" style="display: inline;">
          <fieldset>
            <?e $settingsForm->hidden("user_name") ?>
            <?e $settingsForm->hidden("email") ?>
            <?e $settingsForm->hidden("private_flag") ?>
            <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
            <input type="hidden" name="mode" value="save" />
            <input type="submit" value="保存" />
          </fieldset>
        </form>
      </td>
    </tr>
  </table>
</div>
