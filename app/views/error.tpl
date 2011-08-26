<if expr="$errors">
  <div id="sbl_errmsg" class="sbl_error">
    <ul>
      <foreach from="$errors" value="$error">
        <li><?= $error ?></li>
      </foreach>
    </ul>
  </div>
<else />
  <div id="sbl_errmsg" class="sbl_error" style="display: none;"></div>
</if>
