<?php
namespace Seblhaire\MenuAndTabUtils;

class MenuService implements MenuServiceContract{

  public function init($id, $data){
    return new MenuBuilder($id, $data);
  }
}
