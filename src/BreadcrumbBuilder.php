<?php
namespace Seblhaire\MenuAndTabUtils;

class BreadcrumbBuilder{
  public $id;
  public $data;

  public function __construct($id, $data){
    $this->id = $id;
    if ($this->checkOptions($data)){
      $this->data = self::mergeValues(config('breadcrumbutils'), $data);
    }
  }


  private function checkOptions($options){
      if (is_array($options)){
          $checkoptions = array(
            'navclass' => 'is_string',
              'olclass' => 'is_string',
              'liclass' => 'is_string',
              'menu' => 'is_array',
              'olattr' => 'is_array',
              'liattr' => 'is_array',
              'active'=> 'is_string',
          );
          $aKeys = array_keys($checkoptions);
          foreach($options as $sKey => $sValue){
              if (!in_array($sKey, $aKeys) || !$checkoptions[$sKey]($sValue)){
                  return false;
              }
          }
          return true;
      }
      return false;
  }

  public function render(){
    $result = '<nav id="' . $this->id . '"';
    if (count($this->data['navattr'])){
      foreach ($this->data['navattr'] as $key => $value){
        $result .= ' ' . $key . '="' . $value . '"';
      }
    }
    $result .= '>'. PHP_EOL;
    $result .= '<ol id="' . $this->id . '-ol" class="' . $this->data['olclass'] . '"';
    if (count($this->data['olattr'])){
      foreach ($this->data['olattr'] as $key => $value){
        $result .= ' ' . $key . '="' . $value . '"';
      }
    }
    $result .= '>'. PHP_EOL;
    $i = 1;
    foreach ($this->data['menu'] as $idx => $item){
      if ($i == count($this->data['menu'])){
        $result .= '<li id="' . $idx . '" class="' . $this->data['liclass'] . '" aria-current="page"';
        if (isset($item['icon'])){
          $result .= ' title="' . $item['title']. '"';
        }
        if (count($this->data['liattr'])){
          foreach ($this->data['liattr'] as $key => $value){
            $result .= ' ' . $key . '="' . $value . '"';
          }
        }
        $result .= '>' . PHP_EOL;
        if (isset($item['icon'])){
          $result .= $item['icon'] . PHP_EOL;
        }else{
          $result .= $item['title'] . PHP_EOL;
        }
        $result .= '</li>'. PHP_EOL;
      }else{
        $result .= '<li id="' . $idx . '" class="' . $this->data['liclass'] . ' active" aria-current="page"';
        if (isset($item['icon'])){
          $result .= ' title="' . $item['title']. '"';
        }
        if (count($this->data['liattr'])){
          foreach ($this->data['liattr'] as $key => $value){
            $result .= ' ' . $key . '="' . $value . '"';
          }
        }
        $result .= '>' . PHP_EOL;
        $result .= '<a id="' . $idx . '-link"';
        if (isset($item['attributes']) && count($item['attributes']) > 0){
          foreach ($item['attributes'] as $key => $value){
            $result .= ' ' . $key . '="' . $value . '"';
          }
        }
        $result .= '>' . PHP_EOL;
        if (isset($item['icon'])){
          $result .= $item['icon'] . PHP_EOL;
        }else{
          $result .= $item['title'] . PHP_EOL;
        }
        $result .= '</a>' . PHP_EOL . '</li>'. PHP_EOL;
      }
    }
    $result .= '</ol>'. PHP_EOL . '</nav>'. PHP_EOL;
    return $result;
  }

  public function __toString(){
      return $this->render();
  }

  public static function mergeValues($defaults, $newValues){
    if (count($newValues) > 0){
      foreach ($newValues as $key => $value){
        if (is_array($value)){
          if (!isset($defaults[$key])) $defaults[$key] = [];
          $defaults[$key] = self::mergeValues($defaults[$key], $value);
        }else{
          $defaults[$key] = $value;
        }
      }
    }
    return $defaults;
  }
}
