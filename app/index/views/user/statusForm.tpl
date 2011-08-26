<div id="statusUpdated" style="display: none;">
  <p>You've Updated!</p>
</div>

<h2>いまなにしてる？</h2>

<div id="statusForm">
  <form action="<?= uri("c: status, a: update") ?>" method="post">
    <p>
      <span id="charCounter"><?= Status::COMMENT_MAX_LENGTH ?></span>
      <textarea id="comment" name="comment"></textarea>
    </p>
    <div>
      <input type="hidden" name="SBL_CLIENT_ID" value="<?= $clientId ?>" />
      <input id="submitButton" type="submit" value="投稿する" />
    </div>
  </form>
</div>

<div class="userMenu">
  <ul>
    <li><hlink uri="c: user, a: home">みんなのつぶやき</hlink></li>
    <li><hlink uri="c: user, a: replies">あなた宛のつぶやき</hlink></li>
    <li><hlink uri="c: user, a: archive">あなたのつぶやき</hlink></li>
    <li class="last"><hlink uri="c: index, a: timeline">公開つぶやき</hlink></li>
  </ul>
</div>

<script type="text/javascript" src="<?= linkto("js/Status.js") ?>"></script>

<script type="text/javascript">
var _status = null;

new Sabel.Event(window, "load", function(evt) {
  var path = new Sabel.Uri().path;
  Sabel.Array.each(Sabel.find("div.userMenu")[0].getElementsByTagName("a"), function(a) {
    if (new Sabel.Uri(a.href).path == path) {
      a.parentNode.style.backgroundColor = "#fff";
      a.style.cursor = "default";
      a.onclick = function() { return false; };
      return "BREAK";
    }
  });
  
  _status = new Status(
    // new Sabel.Element(document.forms[0]),
    new Sabel.Element(Sabel.find("#statusForm > form")[0]),
    '<?= $clientId ?>',
    <?= Status::COMMENT_MAX_LENGTH ?>,
    <?e ($isProtected) ? "true" : "false" ?>,
    '<?= uri("n: default, c: status, a: destroy, param: ") ?>'
  );
});
</script>
