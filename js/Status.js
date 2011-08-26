var Status = new Sabel.Class({
  CLIENT_ID:              "",
  COMMENT_MAX_LENGTH:      0,
  HIGHLIGHT_COLOR: "#9999cc",
  
  currentPageNum:        1,
  isRepliesPage:     false,
  isUserArchivePage: false,
  isPublicTimeline:  false,
  isProtectedUser:   false,
  removeUri:            "",
  
  form:          null,
  ajax:          null,
  comment:       null,
  postButton:    null,
  charCounter:   null,
  highlight:     null,
  postedEffect:  null,
  latestComment: null,
  statusesCount: null,
  statusesTable: null,
  
  init: function(form, clientId, maxLength, isProtected, removeUri) {
    this.form = form;
    this.ajax = new Sabel.Ajax();
    this.CLIENT_ID = clientId;
    this.COMMENT_MAX_LENGTH = maxLength;
    this.removeUri = removeUri;
    
    var uri = new Sabel.Uri();
    this.currentPageNum    = parseInt((uri.getQueryObj().get("page") || "1"));
    this.isProtectedUser   = isProtected;
    this.isUserArchivePage = (uri.path.indexOf("user/archive")   !== -1);
    this.isRepliesPage     = (uri.path.indexOf("user/replies")   !== -1);
    this.isPublicTimeline  = (uri.path.indexOf("index/timeline") !== -1);
    
    this.charCounter = Sabel.get("charCounter");
    this.comment     = Sabel.get("comment");
    this.postButton  = Sabel.get("submitButton");
    
    this.form.observe("submit", this.post, false, this);
    this.comment.observe("keyup", this.textareaCallback, false, this);
    this.comment.observe("focus", this.textareaCallback, false, this);
    
    this.statusesTable = Sabel.find("table.statuses").item(0);
    this.latestComment = Sabel.get("latestComment");
    this.statusesCount = Sabel.get("statusesCount");
    
    this.highlight = new Sabel.Effect({duration: 1200});
    this.highlight.add(new Sabel.Effect.Highlight(this.latestComment, this.HIGHLIGHT_COLOR));
    this.highlight.add(new Sabel.Effect.Highlight(this.statusesCount, this.HIGHLIGHT_COLOR));
    
    if (!this.isRepliesPage) {
      Sabel.find("img.trashButton").observe("click", this.remove, false, this);
    }
    
    if (!this.isUserArchivePage) {
      Sabel.find("img.replyButton").observe("click", this.insertUsername, false, this);
    }
    
    this.postedEffect = new Sabel.Effect({duration: 1200})
      .add(new Sabel.Effect.Highlight("statusUpdated"))
      .add(new Sabel.Effect.Fade("statusUpdated"), true);
  },
  
  textareaCallback: function(evt) {
    var length = this.comment.value.length;
    var rem = this.COMMENT_MAX_LENGTH - this.comment.value.length;
    
    if (rem < 0) {
      this.charCounter.style.color = "#bb0000";
      this.postButton.disabled = true;
    } else if (rem <= 10) {
      this.charCounter.style.color = "#dfa19a";
      this.postButton.disabled = false;
    } else {
      this.charCounter.style.color = "#bbb";
      this.postButton.disabled = false;
    }
    
    this.charCounter.innerHTML = rem;
  },
  
  insertUsername: function(evt) {
    var text = "@" + Sabel.Event.getTarget(evt).getAttribute("username") + " ";
    this.comment.value = text + this.comment.value.replace(/^@[^\s]+\s/, "");
    this.charCounter.innerHTML = this.COMMENT_MAX_LENGTH - text.length;
    this.charCounter.style.color = "#bbb";
    this.postButton.disabled = false;
    
    this.comment.focus();
    
    if (Sabel.UserAgent.isIE) {
      var range = this.comment.createTextRange();
      range.move('character', text.length);
      range.select();
    } else if (Sabel.UserAgent.isSafari || Sabel.UserAgent.isChrome) {
      this.comment.setSelectionRange(text.length, text.length);
    }
  },
  
  post: function(evt) {
    Sabel.Event.preventDefault(evt);
    var comment = this.comment;
    if (comment.value.length === 0) return;
    
    if (comment.value.length > this.COMMENT_MAX_LENGTH) {
      this.textareaCallback();
      this.postButton.disabled = true;
    } else {
      this.ajax.request(this.form.getAttribute("action"), {
        scope:  this,
        params: {comment: comment.value, SBL_CLIENT_ID: this.CLIENT_ID},
        onComplete: function(response) {
          eval ("var result = " + response.responseText);
          if (result.code == 200) {
            this.form.reset();
            this.charCounter.style.color = "#bbb";
            this.charCounter.innerHTML = this.COMMENT_MAX_LENGTH;
            this.posted(result.values);
          } else {
            alert("投稿に失敗しました");
          }
        }
      });
    }
  },
  
  remove: function(evt) {
    if (!window.confirm("削除してもよろしいですか？")) return;
    
    var img = Sabel.Event.getTarget(evt);
    this.ajax.request(this.removeUri + img.getAttribute("statusId"), {
      scope:  this,
      params: "SBL_CLIENT_ID=" + this.CLIENT_ID,
      onComplete: function(response) {
        eval ("var result = " + response.responseText);
        if (result.code == 200) {
          this.statusesTable.deleteRow(img.parentNode.parentNode.sectionRowIndex);
          if (this.isEmpty() && this.currentPageNum > 1) {
            window.location.href = new Sabel.Uri().path + "?page=" + (this.currentPageNum - 1);
          } else {
            this.decrementStatusesCounter();
          }
        } else {
          alert("削除に失敗しました");
        }
      }
    });
  },
  
  posted: function(values) {
    this.incrementStatusesCounter();
    this.latestComment.innerHTML = values.comment;
    
    if (this.currentPageNum > 1 || this.isRepliesPage || (this.isPublicTimeline && this.isProtectedUser)) {
      this.postedEffect.play(true);
      return;
    }
    
    var tr = new Sabel.Element(document.createElement("tr"));
    tr.append("td",  null, {"class": "image"})
      .append("a",   null, {"href": values.user_home})
      .append("img", null, {alt: values.name, title: values.name, src: values.image_uri});
    
    var p = tr.append("td", null, {"class": "status"}).append("p");
    
    if (!this.isUserArchivePage) {
      p.append("strong").append("a", values.name, {"href": values.user_home});
      p.appendChild(document.createTextNode(" "));
    }
    
    p.append("span", values.comment);
    p.append("a", values.updated, {"href": values.status_uri});
    
    var td  = tr.append("td",  null, {"class": "icon"});
    var img = td.append("img", null, {"class": "trashButton", "src": values.trash_gif, "statusId": values.id});
    img.observe("click", this.remove, false, this);
    
    var tbody  = this.statusesTable.getFirstChild();
    var _tr    = tbody.getFirstChild();
    var firstr = null;
    
    if (_tr.style.display == "none" && tbody.getElementsByTagName("tr").length === 1) {
      var  tds = tr.getElementsByTagName("td");
      var _tds = _tr.getElementsByTagName("td");
      for (var i = 0; i < 3; i++) _tds.item(i).innerHTML = tds.item(i).innerHTML;
      _tr.style.display = "block";
      firstr = _tr;
    } else {
      tbody.insertBefore(tr, _tr);
      firstr = tr;
    }
    
    new Sabel.Effect({duration: 1200}).add(new Sabel.Effect.Highlight(firstr, this.HIGHLIGHT_COLOR)).play(true);
    this.highlight.play(true);
    
    return firstr;
  },
  
  incrementStatusesCounter: function() {
    this.statusesCount.innerHTML = parseInt(this.statusesCount.innerHTML) + 1;
  },
  
  decrementStatusesCounter: function() {
    this.statusesCount.innerHTML = parseInt(this.statusesCount.innerHTML) - 1;
  },
  
  isEmpty: function() {
    var trs = this.statusesTable.getElementsByTagName("tr");
    return (trs.length === 0 || (trs.length === 1 && trs.item(0).style.display == "none"));
  }
});
