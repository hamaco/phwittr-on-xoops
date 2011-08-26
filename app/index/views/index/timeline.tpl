<if expr="$aclUser->isAuthenticated()">
  <partial name="user/statusForm" />
</if>

<partial name="statuses" assign="rss: true" />
