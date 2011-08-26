<if expr="$paginator->results">
  <table class="followers">
    <tbody>
      <foreach from="$paginator->results" value="$item">
      <tr>
        <td class="image">
          <hlink uri="n: users, c: {$item->Users->uname}, a: ">
            <img alt="<?= $item->Users->getName() ?>"
                 title="<?= $item->Users->getName() ?>"
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
