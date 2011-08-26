<if expr="$paginator->results">
  <table class="followers">
    <foreach from="$paginator->results" value="$item">
    <tr>
      <td class="image">
        <hlink uri="n: users, c: {$item->User->uname}, a: ">
          <img alt="<?= $item->User->uname ?>"
               title="<?= $item->User->uname ?>"
               src="<?e user_avatarize($item->User->uid) ?>" />
        </hlink>
      </td>
      <td class="user">
        <strong>
          <hlink uri="n: users, c: {$item->User->user_name}, a: ">
            <?= $item->User->user_name ?>
          </hlink>
        </strong>
        <p>
          <?= $item->created_at|datetime ?>
        </p>
      </td>
    </tr>
    </foreach>
  </table>
  
  <partial name="linkedPager" />
</if>
