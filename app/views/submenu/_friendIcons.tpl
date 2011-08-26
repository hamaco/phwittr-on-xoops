<h2>フォロー中</h2>

<div class="friendIcons">
  <if expr="$userData->friends">
    <? for ($i = 0; $i < FRIENDS_ICON_LIMIT; $i++) : ?>
      <? if (!isset($userData->friends[$i])) break ?>
      <? $friend = $userData->friends[$i]->Users ?>
      <a href="<?= uri("n: users, c: {$friend->uname}, a: ") ?>">
        <img alt="<?= $friend->getName() ?>"
             title="<?= $friend->getName() ?>"
             src="<?e user_avatarize($friend->uid) ?>" />
      </a>
    <? endfor ?>
    <if expr="count($userData->friends) > FRIENDS_ICON_LIMIT">
      <p>
        <a href="<?= $toAllFriends ?>">ぜんぶ見る...</a>
      </p>
    </if>
  <else />
    <br />
  </if>
</div>
