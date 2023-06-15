<table class="statuses">
  <tbody>
    <tr style="display: none;">
      <td class="image"></td><td class="status"></td><td class="icon"></td>
    </tr>
    
    <if expr="$paginator->results">
    <foreach from="$paginator->results" key="$i" value="$status">
      <tr>
        <td class="image">
          <hlink uri="n: users, c: {$status->User->user_name}, a: ">
            <img alt="<?= $status->User->user_name ?>"
                 title="<?= $status->User->user_name ?>"
                 src="<?e user_avatarize($status->User) ?>" />
          </hlink>
        </td>
        
        <td class="status">
          <p>
            <if expr="!$noUsername">
            <strong>
              <hlink uri="n: users, c: {$status->User->user_name}, a: "><?= $status->User->user_name ?></hlink>
            </strong>
            </if>
            
            <span><?e $status->comment|showComment ?></span><hlink uri="n: default, c: index, a: status, param: {$status->id}"><?= $status->created_at|showUpdatedAt ?></hlink>
          </p>
        </td>
        
        <td class="icon">
          <if equals="$aclUser->id, $status->user_id">
            <img statusId="<?= $status->id ?>"
                 class="trashButton"
                 src="<?= linkto("images/trash.gif") ?>" />
          <elseif expr="$aclUser->isAuthenticated()">
            <img username="<?= $status->User->user_name ?>"
                 class="replyButton"
                 src="<?= linkto("images/reply.gif") ?>" />
          </if>
        </td>
      </tr>
    </foreach>
    </if>
  </tbody>
</table>

<if expr="$rss">
<div class="rss">
  <hlink uri="a: rss">RSS</hlink>
</div>
</if>

<partial name="linkedPager" />
