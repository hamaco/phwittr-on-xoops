<div class="pagelink">
  <if expr="$paginator->viewer->hasPrevious()">
  <div class="prevlink">
    <?e $paginator->prev("&lt;&lt; 前へ") ?>
  </div>
  </if>
  <if expr="$paginator->viewer->hasNext()">
  <div class="nextlink">
    <?e $paginator->next("次へ &gt;&gt;") ?>
  </div>
  </if>
</div>
