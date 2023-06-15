<div class="status">
  <hlink uri="n: users, c: {$status->User->uname}, a: ">
    <img alt="<?= $aUser->uname ?>"
         title="<?= $aUser->uname ?>"
         src="<?e user_avatarize($aUser) ?>">
  </hlink>
  
  <h2><?= $aUser->user_name ?></h2>
  
  <p>
    <?e $aStatus->comment|showComment ?>
    <span><?= $aStatus->created_at|showUpdatedAt ?></span>
  </p>
</div>
