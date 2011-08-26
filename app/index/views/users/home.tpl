<div class="othersHeader">
  <img alt="<?= $aUser->getName() ?>"
       title="<?= $aUser->getName() ?>"
       src="<?e user_avatarize($aUser->uid) ?>" />
  <h2><?= $aUser->getName() ?></h2>
  
  <if expr="$aclUser->isAuthenticated()">
    <if expr="$isFollowed">
      <form action="<?= uri("n: default, c: friendship, a: destroy") ?>" method="post">
        <div>
          <input type="hidden" name="id" value="<?= $aUser->uid ?>" />
          <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
          <input type="submit" value="フォロー解除" />
        </div>
      </form>
    <else />
      <form action="<?= uri("n: default, c: friendship, a: create") ?>" method="post">
        <div>
          <input type="hidden" name="id" value="<?= $aUser->uid ?>" />
          <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
          <input type="submit" value="フォローする" />
        </div>
      </form>
    </if>
  </if>
</div>

<if expr="$paginator->results">
  <table class="statuses">
    <foreach from="$paginator->results" key="$i" value="$status">
    <tr>
      <td class="status">
        <p>
          <if equals="$i, 0">
          <span class="first">
          <else />
          <span>
          </if>
            <?e $status->comment|showComment ?>
          </span>
          <hlink uri="n: default, c: index, a: status, param: {$status->id}"><?= showUpdatedAt($status->created_at) ?></hlink>
        </p>
      </td>
    </tr>
    </foreach>
  </table>
</if>
