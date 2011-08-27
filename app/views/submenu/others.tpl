<h2>プロフィール</h2>

<ul>
  <li>
    名前 <strong><?= $userData->name ?></strong>
  </li>
</ul>

<h2>ステータス</h2>

<ul>
  <li>
    <span class="label">
      <hlink uri="n: users, c: {$userData->uname}, a: friends">このユーザがフォロー</hlink>
    </span>
    <span class="count"><?= $userData->friendsCount ?></span>
  </li>
  <li>
    <span class="label">
      <hlink uri="n: users, c: {$userData->uname}, a: followers">このユーザをフォロー</hlink>
    </span>
    <span class="count"><?= $userData->followersCount ?></span>
  </li>
  <li>
    <span class="label">
      <hlink uri="n: users, c: {$userData->uname}, a: ">これまでの投稿</hlink>
    </span>
    <span class="count"><?= $userData->statusesCount ?></span>
  </li>
</ul>

<? $_uri = uri("n: users, c: {$userData->name}, a: friends") ?>
<partial name="submenu/_friendIcons" assign="userData: $userData, toAllFriends: $_uri" />
