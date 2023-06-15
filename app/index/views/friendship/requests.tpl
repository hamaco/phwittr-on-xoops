<h2>あなたへの新しいリクエスト</h2>

<table class="requests">
  <tbody>
    <foreach from="$requests" value="$request">
    <tr>
      <td class="image">
        <hlink uri="n: users, c: {$request->User->user_name}, a: ">
          <img alt="<?= $request->User->user_name ?>"
               title="<?= $request->User->user_name ?>"
               src="<?= user_avatarize($request->User) ?>" />
        </hlink>
      </td>
      
      <td class="user">
        <strong>
          <hlink uri="n: users, c: {$request->User->user_name}, a: ">
            <?= $request->User->user_name ?>
          </hlink>
        </strong>
        <br/>
        <?= $request->created_at|datetime ?>
      </td>
      
      <td class="icon">
        <button class="accept" requestor="<?= $request->user_id ?>">許可</button>&nbsp;
        <button class="deny" requestor="<?= $request->user_id ?>">拒否</button>
      </td>
    </tr>
    </foreach>
  </tbody>
</table>

<script type="text/javascript" src="<?= linkto("js/Friendship.js") ?>"></script>

<script type="text/javascript">
var friendship = null;

new Sabel.Event(window, "load", function(evt) {
  friendship = new Friendship("<?= $clientId ?>");
  friendship.initForRequest("<?= uri("a: accept") ?>", "<?= uri("a: deny") ?>");
});
</script>
