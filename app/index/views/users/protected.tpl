<div class="othersHeader">
  <img alt="<?= $aUser->user_name ?>"
       title="<?= $aUser->user_name ?>"
       src="<?= user_avatarize($aUser->id) ?>" />
  
  <h2><?= $aUser->user_name ?></h2>
  
  <div class="protect">
    <div class="image">
      <img src="<?= linkto("images/protect.png") ?>" />
    </div>
    
    <div class="request">
      <p class="emphasis">
        <if expr="$isRequested">
        フォローをリクエストしました。
        <else />
        このユーザはつぶやきを非公開にしています。
        </if>
      </p>
      
      <if expr="$aclUser->isAuthenticated()">
        <if expr="$isRequested">
          <p>
            承認されれば、その人のつぶやきを見られるようになります。
          </p>
          <form action="<?= uri("n: default, c: friendship, a: destroy") ?>" method="post">
            <div>
              <input type="hidden" name="id" value="<?= $aUser->id ?>" />
              <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
              <input type="submit" value="リクエストをキャンセルする" />
            </div>
          </form>
        <else />
          <p>
            フォローするにはリクエストが必要です。
          </p>
          <form action="<?= uri("n: default, c: friendship, a: create") ?>" method="post">
            <div>
              <input type="hidden" name="id" value="<?= $aUser->id ?>" />
              <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
              <input type="submit" value="リクエストを送る" />
            </div>
          </form>
        </if>
      </if>
    </div>
  </div>
</div>
