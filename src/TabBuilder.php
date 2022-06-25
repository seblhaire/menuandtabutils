<?php
namespace Seblhaire\MenuAndTabUtils;

class TabBuilder{
  public $data;
  public $id;

  public function __construct($id, $data){
    $this->id = $id;
    if ($this->checkOptions($data)){
      $this->data = self::mergeValues(config('tabutils'), $data);
    }
  }


  private function checkOptions($options){
      if (is_array($options)){
          $checkoptions = array(
              'ulclass' => 'is_string',
              'liclass' => 'is_string',
              'aclass' => 'is_string',
              'dropdwnclass' => 'is_string',
              'tabs' => 'is_array',
              'ulattr' => 'is_array',
              'liattr' => 'is_array',
              'adroptoggle' => 'is_string',
              'lidropdwn' => 'is_string',
              'dropdwnmenuclass' => 'is_string',
              'dropdivider' => 'is_string',
              'dropitem' => 'is_string',
              'active'=> 'is_string',
              'current' =>'is_string',
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

  public function setCurrent($current){
    $this->data['current'] = $current;
    return $this;
  }

  public function render(){
    $result = '<ul id="' . $this->id . '" class="' . $this->data['ulclass'] . '" role="tablist" ';
    if (count($this->data['ulattr'])){
      foreach ($this->data['ulattr'] as $key => $value){
        $result .= ' ' . $key . '="' . $value . '"';
      }
    }
    $result .= '>'. PHP_EOL;
    foreach ($this->data['tabs'] as $idx => $item) {
      $result .= '<li class="' . $this->data['liclass'] . '" role="presentation" ';
      if (count($this->data['liattr'])){
        foreach ($this->data['liattr'] as $key => $value){
          $result .= ' ' . $key . '="' . $value . '"';
        }
      }
      $result .= '>' . PHP_EOL;
      $result .= '<button id="' . $idx . '-tab" data-bs-toggle="tab" role="tab" type="button" data-bs-target="#' . $idx . '" aria-controls="' . $idx . '"';
      if ($idx == $this->data['current']){
        $result .= ' class="' . $this->data['btnclass'] .' ' . $this->data['active']  . '" aria-selected="true"';
      }else{
        $result .= ' class="' . $this->data['btnclass'] .'" aria-selected="false"';
      }
      if (isset($item['icon'])){
        $result .= ' title="' . $item['title']. '"';
      }
      if (isset($item['attributes'])){
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
      $result .= '</button>' . PHP_EOL . '</li>'. PHP_EOL;

    }
    $result .= '</ul>'. PHP_EOL . '<br/>';
    $result .= '<div id="' . $this->id . '-content" class="' . $this->data['tabcontentclass'] . '"';
    if (isset($item['maindivattr'])){
      foreach ($item['maindivattr'] as $key => $value){
        $result .= ' ' . $key . '="' . $value . '"';
      }
    }
    $result .= '>' . PHP_EOL;
    foreach ($this->data['tabs'] as $idx => $item) {
      $result .= '<div id="' . $idx . '" class="' . $this->data['tabdivclass'];
      if ($idx == $this->data['current']){
        $result .= ' ' . $this->data['activetab'];
      }
      if (isset($item['tabcontentattr'])){
        foreach ($item['tabcontentattr'] as $key => $value){
          $result .= ' ' . $key . '="' . $value . '"';
        }
      }
      $result .= '" role="tabpanel" aria-labelledby="' . $idx . '-tab">' . PHP_EOL;
      if (isset($item['content'])){
        $result .= $item['content'] . PHP_EOL;
      } else {
        $result .= view($item['view'], !isset($item['viewparams']) ? [] : $item['viewparams']);
      }
      $result .= '</div>' . PHP_EOL;
    }
    $result .= '</div>' . PHP_EOL;
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
