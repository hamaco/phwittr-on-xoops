<h2>フォロー中</h2>

<div class="friendIcons">
  <if expr="$userData->friends">
    <? for ($i = 0; $i < FRIENDS_ICON_LIMIT; $i++) : ?>
      <? if (!isset($userData->friends[$i])) break ?>
      <? $friend = $userData->friends[$i]->User ?>
      <a href="<?= uri("n: users, c: {$friend->user_name}, a: ") ?>">
        <img alt="<?= $friend->user_name ?>"
             title="<?= $friend->user_name ?>"
             src="<?e user_avatarize($friend) ?>" />
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
