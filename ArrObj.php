<?php

declare(strict_types=1);

class ArrObj implements ArrayAccess {
  private $arr;

  function __construct(array $arr = []) {
    $this->arr = $arr;
  }

  public function __invoke(array $arr = NULL) : array {
    if (isset($arr)) {
      $this->arr = $arr;
    }

    return $this->arr;
  }

  public function __call($name, $args) {
    switch ($name) {
      case "map":
        return new ArrObj(array_map($args[0], $this->arr));
      case "foreach";
        $cb = $args[0];
        foreach ($this->arr as $key => $value) {
          $cb($value, $key);
        }
        return $this;
        break;
      case "push";
      case "pop";
      case "shift";
      case "unshift";
      case "reduce";
      case "rand";
        return call_user_func_array("array_$name", array_merge([&$this->arr], $args));
      default:
      if (function_exists("array_$name")) {
        $ret = call_user_func_array("array_$name", array_merge([&$this->arr], $args));
        if (is_array($ret)) {
          return new ArrObj($ret); 
        }
        return $this;
      }
    }

    throw new BadMethodCallException("$name is not a recognized function");
  }

  public function __get($name, ...$args) {
    if ($name === "length") {
      return count($this->arr);
    }
  }

  # ArrayAccess
  public function offsetExists ($offset) : bool {
    return isset($this->arr[$offset]);
  }

  public function offsetGet ($offset) {
    return $this->arr[$offset];
  }

  public function offsetSet ($offset, $value) : void {
    if (isset($this->arr[$offset])) {
      $this->arr[$offset] = $value;
    } else {
      $this->arr[] = $value;
    }
  }

  public function offsetUnset ($offset) : void {
    unset($this->arr[$offset]);
  }
}
