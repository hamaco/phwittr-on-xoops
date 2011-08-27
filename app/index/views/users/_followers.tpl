<if expr="$paginator->results">
  <table class="followers">
    <foreach from="$paginator->results" value="$item">
    <tr>
      <td class="image">
        <hlink uri="n: users, c: {$item->Users->uname}, a: ">
          <img alt="<?= $item->Users->uname ?>"
               title="<?= $item->Users->uname ?>"
               src="<?e user_avatarize($item->Users->uid) ?>" />
        </hlink>
      </td>
      <td class="user">
        <strong>
          <hlink uri="n: users, c: {$item->Users->uname}, a: ">
            <?= $item->Users->getName() ?>
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
