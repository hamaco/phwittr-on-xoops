<?php

interface Imanage
{
  /**
   * @param string $resource 画像データ
   *
   * @return boolean
   */
  public function validate($resource);
  
  /**
   * @param string $resource 画像データ
   *
   * @return string
   */
  public function upload($resource);
  
  /**
   * @param string $fileName ファイル名
   *
   * @return void
   */
  public function delete($fileName);
}
