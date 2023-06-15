<partial name="settingsmenu" />

<div class="commonForm settings">
  <partial name="error" />
  
  <form class="common" action="<?= uri("a: picture") ?>" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th class="picture">
          <img alt="<?= $aclUser->name ?>"
               title="<?= $aclUser->name ?>"
               src="<?e user_avatarize($aclUser) ?>" />
        </th>
        <td>
          <input type="file" name="picture" /><br/>
          <span class="explain">画像は700KB以下でなくてはいけません。</span><br/>
          <span class="explain">対応フォーマットはJPEG, GIF, PNGです。</span>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
          <input type="submit" value="保存する" />
        </td>
      </tr>
    </table>
  </form>
</div>
