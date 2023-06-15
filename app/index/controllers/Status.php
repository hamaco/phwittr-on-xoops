<?php

class Index_Controllers_Status extends Sabel_Controller_Page
{
  /**
   * @httpMethod post
   *
   * @check_client_id
   */
  public function update()
  {
    $user   = $this->aclUser;
    $result = $this->getLogic("status")->post($user->id, $this->comment);
    
    if ($this->isAjaxRequest()) {
      if ($result->isSuccess()) {
        $this->ajax->values = array(
          "id"         => $result->aStatus->id,
          "name"       => $user->name,
          "comment"    => showComment($result->aStatus->comment),
          "updated"    => showUpdatedAt($result->aStatus->created_at),
          "user_home"  => uri("n: users, c: {$user->name}, a: "),
          "image_uri"  => user_avatarize($user),
          "status_uri" => uri("c: index, a: status, param: {$result->aStatus->id}"),
          "trash_gif"  => linkto("images/trash.gif"),
          "reply_gif"  => linkto("images/reply.gif"),
        );
      } else {
        $this->ajax->failure();
      }
    } else {
      $this->redirect->to("n: default, c: user, a: home");
    }
  }
  
  /**
   * @httpMethod post
   *
   * @check_client_id
   * @check param required nNumber
   */
  public function destroy()
  {
    if ($this->validator->hasError()) {
      $this->badRequest();
    } else {
      $this->getLogic("status")->remove($this->aclUser->id, $this->param);
    }
  }
}
