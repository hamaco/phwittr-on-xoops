<h2>あなたがフォロー <?= $paginator->count ?>人.</h2>

<partial name="_followers" assign="removable: true" />

<script type="text/javascript" src="<?= linkto("js/Friendship.js") ?>"></script>

<script type="text/javascript">
var friendship = null;

new Sabel.Event(window, "load", function(evt) {
  friendship = new Friendship("<?= $clientId ?>");
  friendship.initForDestroy("<?= uri("c: friendship, a: destroy") ?>");
});
</script>
