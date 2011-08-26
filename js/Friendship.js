var Friendship = new Sabel.Class({
  ajax:    null,
  CLIENT_ID: "",
  
  destroyUri:     "",
  friendsTable: null,
  
  acceptUri:       "",
  denyUri:         "",
  requestsTable: null,
  
  init: function(clientId) {
    this.ajax = new Sabel.Ajax();
    this.CLIENT_ID = clientId;
  },
  
  initForDestroy: function(uri) {
    this.destroyUri = uri;
    this.friendsTable = Sabel.find("table.followers").item(0);
    this.counter = Sabel.get("friendsCount");
    Sabel.find("button.destroy").observe("click", this.destroy, false, this);
  },
  
  initForRequest: function(acceptUri, denyUri) {
    this.acceptUri = acceptUri;
    this.denyUri   = denyUri;
    this.counter   = Sabel.get("followersCount");
    
    this.requestsTable = Sabel.find("table.requests").item(0);
    Sabel.find("button.accept").observe("click", this.accept, false, this);
    Sabel.find("button.deny").observe("click", this.deny, false, this);
  },
  
  destroy: function(evt) {
    if (!window.confirm("解除してもよろしいですか？")) return;
    
    var button = Sabel.Event.getTarget(evt);
    this.ajax.request(this.destroyUri, {
      scope:  this,
      params: "SBL_CLIENT_ID=" + this.CLIENT_ID + "&id=" + button.getAttribute("userId"),
      onComplete: function(response) {
        eval ("var result = " + response.responseText);
        if (result.code == 200) {
          this.friendsTable.deleteRow(button.parentNode.parentNode.sectionRowIndex);
          if (this.friendsTable.getElementsByTagName("tr").length === 0) {
            window.location.reload();
          } else {
            this.counter.innerHTML = parseInt(this.counter.innerHTML) - 1;
          }
        } else {
          alert("解除に失敗しました");
        }
      }
    });
  },
  
  accept: function(evt) {
    this._permit("accept", Sabel.Event.getTarget(evt));
  },
  
  deny: function(evt) {
    this._permit("deny", Sabel.Event.getTarget(evt));
  },
  
  _permit: function(type, button) {
    var str  = (type == 'accept') ? "許可" : "拒否";
    var uri  = (type == 'accept') ? this.acceptUri : this.denyUri;
    var tr   = button.parentNode.parentNode;
    var name = new Sabel.String(Sabel.find("td.user > strong > a", tr)[0].innerHTML).trim();
    
    if (!window.confirm(name + "さんからのリクエストを" + str + "します。\nよろしいですか？")) {
      return;
    }
    
    this.ajax.request(uri, {
      scope:  this,
      params: "SBL_CLIENT_ID=" + this.CLIENT_ID + "&id=" + button.getAttribute("requestor"),
      onComplete: function(response) {
        eval ("var result = " + response.responseText);
        if (result.code == 200) {
          this.requestsTable.deleteRow(tr.sectionRowIndex);
          if (this.requestsTable.getElementsByTagName("tr").length === 0) {
            window.location.reload();
          } else if (type == "accept") {
            this.counter.innerHTML = parseInt(this.counter.innerHTML) + 1;
          }
        } else {
          alert(str + "に失敗しました");
        }
      }
    });
  }
});
