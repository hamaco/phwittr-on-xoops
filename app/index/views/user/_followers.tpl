<if expr="$paginator->results">
  <table class="followers">
    <tbody>
      <foreach from="$paginator->results" value="$item">
      <tr>
        <td class="image">
          <hlink uri="n: users, c: {$item->Users->uname}, a: ">
            <img alt="<?= $item->Users->user_name ?>"
                 title="<?= $item->Users->user_name ?>"
                 src="<?e user_avatarize($item->User) ?>" />
          </hlink>
        </td>
        <td class="user">
          <strong>
            <hlink uri="n: users, c: {$item->Users->uname}, a: ">
              <?= $item->Users->user_name ?>
            </hlink>
          </strong>
          <p>
            <?= $item->created_at|datetime ?>
          </p>
        </td>
        <if expr="$removable">
        <td class="icon">
          <button class="destroy" userId="<?= $item->Users->uid ?>">解除</button>
        </td>
        </if>
      </tr>
      </foreach>
    </tbody>
  </table>
  
  <partial name="linkedPager" />
</if>
