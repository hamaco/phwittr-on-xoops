<div class="settingsMenu">
  <div class="head">
    <img src="<?e user_avatarize($aclUser->id) ?>" />
    <h2><?= $aclUser->name ?></h2>
  </div>
  <ul>
    <li>
      <hlink uri="a: settings">ユーザ情報</hlink>
    </li>
    <li>
      <hlink uri="a: password">パスワード</hlink>
    </li>
    <li class="last">
      <hlink uri="a: picture">アイコン</hlink>
    </li>
  </ul>
</div>

<script type="text/javascript">
new Sabel.Event(window, "load", function(evt) {
  var path = new Sabel.Uri().path;
  Sabel.Array.each(Sabel.find("div.settingsMenu")[0].getElementsByTagName("a"), function(a) {
    if (new Sabel.Uri(a.href).path == path) {
      a.parentNode.style.backgroundColor = "#fff";
      a.style.cursor = "default";
      a.onclick = function() {
        return false;
      };
      
      return "BREAK";
    }
  });
});
</script>
