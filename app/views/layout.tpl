<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $pageTitle ?></title>
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <script type="text/javascript" src="<?= linkto("js/Sabel.js") ?>"></script>
    <link type="text/css" rel="stylesheet" href="<?= linkto("css/default.css") ?>" />
  </head>
  <body>
    <!--[if IE 7]><div class="ie_7"><![endif]-->
    <!--[if IE 6]><div class="ie_6"><![endif]-->

    <div id="wrapper">
      <div id="header">
        <div class="logo">
          <h1>Phwittr</h1>
        </div>
        <div class="mainMenu">
          <ul>
            <if expr="$aclUser->isAuthenticated()">
              <li><hlink uri="n: default, c: user, a: home">ホーム</a></li>
              <li><hlink uri="n: default, c: user, a: settings">設定</a></li>
              <li><hlink uri="n: default, c: user, a: logout">ログアウト</a></li>
            <else />
              <li><hlink uri="n: default, c: login, a: prepare">ログイン</a></li>
              <li><hlink uri="n: default, c: index, a: timeline">つぶやき</a></li>
              <li><hlink uri="n: default, c: register, a: prepare">ユーザ登録</a></li>
            </if>
          </ul>
        </div>
      </div>

      <div id="contentsWrapper">
        <div id="contents">
          <?e $contentForLayout ?>
        </div>

        <div id="subMenu">
          <?e $submenuHtml ?>
        </div>
      </div>

      <div id="footer">
        <p>
          <strong>&copy; 2008 Phwittr</strong>
        </p>
      </div>
    </div>

    <!--[if (IE 6 & IE 7)]></div><![endif]-->
  </body>
</html>
