<!--
<h2>ようこそ！</h2>
<p>
  <img alt="<?= $userData->name ?>"
       title="<?= $userData->name ?>"
       src="<?e $userData->image|thumbnail_uri:24 ?>" />
  <span class="username"><?= $userData->name ?></span>
</p>
-->

<if expr="$userData->requestCount">
<h2>フォローのリクエスト</h2>
<p>
  <hlink uri="n: default, c: friendship, a: requests">新しいフォロー<?= $userData->requestCount ?>件！</hlink>
</p>
</if>

<h2>最近のつぶやき</h2>
<p id="latestComment">
  <if expr="$userData->latestComment">
    <?e $userData->latestComment|showComment ?>
  </if>
</p>

<h2>ステータス</h2>

<ul>
  <li>
    <span class="label"><hlink uri="n: default, c: user, a: friends">あなたがフォロー</hlink></span>
    <span id="friendsCount" class="count"><?= $userData->friendsCount ?></span>
  </li>
  <li>
    <span class="label"><hlink uri="n: default, c: user, a: followers">あなたをフォロー</hlink></span>
    <span id="followersCount" class="count"><?= $userData->followersCount ?></span>
  </li>
  <li>
    <span class="label"><hlink uri="n: default, c: user, a: archive">これまでの投稿</hlink></span>
    <span id="statusesCount" class="count"><?= $userData->statusesCount ?></span>
  </li>
</ul>

<? $_uri = uri("n: default, c: user, a: friends") ?>
<partial name="submenu/_friendIcons" assign="userData: $userData, toAllFriends: $_uri" />
