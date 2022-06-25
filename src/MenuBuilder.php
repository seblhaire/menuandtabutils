<?php
namespace Seblhaire\MenuAndTabUtils;

class MenuBuilder{
  public $id;
  public $data;

  public function __construct($id, $data){
    $this->id = $id;
    if ($this->checkOptions($data)){
      $this->data = self::mergeValues(config('menuutils'), $data);
    }
  }


  private function checkOptions($options){
      if (is_array($options)){
          $checkoptions = array(
              'ulclass' => 'is_string',
              'liclass' => 'is_string',
              'aclass' => 'is_string',
              'dropdwnclass' => 'is_string',
              'menu' => 'is_array',
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
    $result = '<ul id="' . $this->id . '" class="' . $this->data['ulclass'] . '"';
    if (count($this->data['ulattr'])){
      foreach ($this->data['ulattr'] as $key => $value){
        $result .= ' ' . $key . '="' . $value . '"';
      }
    }
    $result .= '>'. PHP_EOL;
    foreach ($this->data['menu'] as $idx => $item){
      if (isset($item['dropdown'])){
        $result .= '<li id="' . $idx . '" class="' . $this->data['lidropdwn'] . '"';
        if (count($this->data['liattr'])){
          foreach ($this->data['liattr'] as $key => $value){
            $result .= ' ' . $key . '="' . $value . '"';
          }
        }
        $result .='>' . PHP_EOL;
        $result .= '<a class="' . $this->data['adroptoggle'] .
           ($idx == $this->data['current'] ? ' ' . $this->data['active'] : '').
           '" href="#" id="' . $idx . 'Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"';
         if (isset($item['icon'])){
           $result .= ' title="' . $item['title']. '"';
         }
         if (isset($item['attributes'])){
           foreach ($item['attributes'] as $key => $value){
             $result .= ' ' . $key . '="' . $value . '"';
           }
         }
        $result .='>' . PHP_EOL;
        if (isset($item['icon'])){
          $result .= $item['icon'];
        } else {
          $result .= $item['title'];
        }
        $result .= '</a>' . PHP_EOL;
        $result .= '<ul class="' . $this->data['dropdwnmenuclass'] .'" aria-labelledby="' . $idx . 'Dropdown">'. PHP_EOL;
        foreach ($item['dropdown'] as $idx2 => $item2){
          if (is_null($item2)){
            $result .= '<li><hr class="' . $this->data['dropdivider'] .'"></li>' . PHP_EOL;
          } else {
            $result .= '<li id="' . $idx2 . '"';
            if (isset($this->data['liattr'])){
              foreach ($this->data['liattr'] as $key => $value){
                $result .= ' ' . $key . '="' . $value . '"';
              }
            }
            $result .='>' . PHP_EOL;
            $result .= '<a id="' . $idx2 . '-link" class="' . $this->data['dropitem'] . '"';
            if (strpos($item2['target'], 'https') !== false){
              $result .= ' target="_blank" rel="noopener noreferrer"';
            }
            $result .= ' href="' . $item2['target'] . '"';
            if (isset($item2['icon'])){
              $result .= ' title="' . $item2['title'] . '"';
            }
            if (isset($item2['attributes'])){
              foreach ($item2['attributes'] as $key => $value){
                $result .= ' ' . $key . '="' . $value . '"';
              }
            }
            $result .= '>';
            if (isset($item2['icon'])){
              $result .= $item2['icon'];
            }else{
              $result .=  $item2['title'];
            }
            $result .= '</a></li>' . PHP_EOL;
          }
        }
        $result .= '</ul>' .  PHP_EOL . '</li>' .  PHP_EOL;
      } else {
        $result .= '<li id="' . $idx . '" class="' . $this->data['liclass'] . '"';
        if (count($this->data['liattr'])){
          foreach ($this->data['liattr'] as $key => $value){
            $result .= ' ' . $key . '="' . $value . '"';
          }
        }
        $result .= '>' . PHP_EOL;
        if ($idx == $this->data['current']){
          $result .= '<a id="' . $idx . '-link" class="' . $this->data['aclass'] .' ' . $this->data['active']  . '" aria-current="page"';
        }else{
          $result .= '<a id="' . $idx . '-link" class="' . $this->data['aclass'] .'"';
        }
        if (strpos($item['target'], 'https') !== false){
          $result .= ' target="_blank" rel="noopener noreferrer"';
        }
        $result .= ' href="' . $item['target'] .'"';
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
        $result .= '</a>' . PHP_EOL . '</li>'. PHP_EOL;
      }
    }
    $result .= '</ul>'. PHP_EOL;
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
