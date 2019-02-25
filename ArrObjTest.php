<?php
declare(strict_types=1);

require_once "ArrObj.php";

use PHPUnit\Framework\TestCase;

class ArrObjTest extends TestCase {

    public function testInitEmpty() {
      $arr = new ArrObj();
      $this->assertEquals([], $arr());
    }

    public function testInitEmptyArray() {
      $arr = new ArrObj([]);
      $this->assertEquals([], $arr());
    }

    public function testInitMixedValues() {
      $arr = new ArrObj([1, "a"]);
      $this->assertEquals([1, "a"], $arr());
    }

    public function testInitFail() {
      $this->expectException(TypeError);
      $arr = new ArrObj("not an array");
    }

    public function testPushShorthand() {
      $arr = new ArrObj();

      $arr[] = "pushed";
      $this->assertEquals(1, $arr->length);
      $this->assertEquals([0 => "pushed"], $arr());

      $arr[] = "pushed again";
      $this->assertEquals(2, $arr->length);
      $this->assertEquals([0 => "pushed", 1 => "pushed again"], $arr());
    }

    public function testPush() {
      $arr = new ArrObj();

      $arr->push("pushed via method");
      $this->assertEquals(1, $arr->length);
      $this->assertEquals([0 => "pushed via method"], $arr());

      $arr->push("pushed again");
      $this->assertEquals(2, $arr->length);
      $this->assertEquals([0 => "pushed via method", 1 => "pushed again"], $arr());
    }

    public function testPop() {
      $arr = new ArrObj(["a", "b", "c", "d"]);

      $val = $arr->pop();
      $this->assertEquals(3, $arr->length);
      $this->assertEquals("d", $val);

      $val = $arr->pop();
      $this->assertEquals(2, $arr->length);
      $this->assertEquals("c", $val);

      $val = $arr->pop();
      $this->assertEquals(1, $arr->length);
      $this->assertEquals("b", $val);

      $val = $arr->pop();
      $this->assertEquals(0, $arr->length);
      $this->assertEquals("a", $val);

      $val = $arr->pop();
      $this->assertEquals(0, $arr->length);
      $this->assertEquals(NULL, $val);
    }

    public function testShift() {
      $arr = new ArrObj(["a", "b", "c", "d"]);

      $val = $arr->shift();
      $this->assertEquals(3, $arr->length);
      $this->assertEquals("a", $val);

      $val = $arr->shift();
      $this->assertEquals(2, $arr->length);
      $this->assertEquals("b", $val);

      $val = $arr->shift();
      $this->assertEquals(1, $arr->length);
      $this->assertEquals("c", $val);

      $val = $arr->shift();
      $this->assertEquals(0, $arr->length);
      $this->assertEquals("d", $val);

      $val = $arr->shift();
      $this->assertEquals(0, $arr->length);
      $this->assertEquals(NULL, $val);
    }

    public function testUnshift() {
      $arr = new ArrObj([]);

      $arr->unshift("d");
      $this->assertEquals(1, $arr->length);
      $this->assertEquals($arr(), [0 => "d"]);

      $arr->unshift("c");
      $this->assertEquals(2, $arr->length);
      $this->assertEquals($arr(), [0 => "c", 1 => "d"]);

      $arr->unshift("b");
      $this->assertEquals(3, $arr->length);
      $this->assertEquals($arr(), [0 => "b", 1 => "c", 2 => "d"]);

      $arr->unshift("a");
      $this->assertEquals(4, $arr->length);
      $this->assertEquals($arr(), [0 => "a", 1 => "b", 2 => "c", 3 => "d"]);
    }

    public function testSet() {
      $arr = new ArrObj([0, 1, 2]);

      $arr[0] = "zero";
      $arr[2] = "two";
      $this->assertEquals(["zero", 1, "two"], $arr());
    }

    public function testGet() {
      $arr = new ArrObj(["a", "b", "c"]);

      $this->assertEquals("a", $arr[0]);
      $this->assertEquals("b", $arr[1]);
      $this->assertEquals("c", $arr[2]);
      $this->assertEquals(NULL, $arr[3]);
    }

    public function testIsset() {
      $arr = new ArrObj(["a", "b", "c"]);

      $this->assertEquals(true, isset($arr[0]));
      $this->assertEquals(true, isset($arr[1]));
      $this->assertEquals(true, isset($arr[2]));
      $this->assertEquals(false, isset($arr[3]));
    }

    public function testUnset() {
      $arr = new ArrObj(["a", "b", "c"]);

      unset($arr[1]);
      $this->assertEquals([0 => "a", 2 => "c"], $arr());

      unset($arr[0]);
      $this->assertEquals([2 => "c"], $arr());

      unset($arr[2]);
      $this->assertEquals([], $arr());
    }

    public function testForeach() {
      $arr = new ArrObj([1, 2, 3, 4]);
      $sumVals = 0;
      $sumKeys = 0;

      $arr->foreach(function ($val, $key) use (&$sumVals, &$sumKeys) {
        $sumVals += $val;
        $sumKeys += $key;
      });

      $this->assertEquals(10, $sumVals);
      $this->assertEquals(6, $sumKeys);
    }

    public function testReduce() {
      $arr = new ArrObj([1, 2, 3, 4]);

      $sum = $arr->reduce(function ($sum, $i) {
        return $sum + $i;
      }, 0);

      $this->assertEquals(10, $sum);
    }

    public function testIsChainable() {
      $arr = new ArrObj([1, 2, 3, 4, 5, 6]);

      $squares = $arr->filter(function ($i) {
        return $i < 4;
      })->map(function ($i) {
        return $i * $i;
      });

      $this->assertEquals([1, 4, 9], $squares());
      $this->assertEquals([1, 2, 3, 4, 5, 6], $arr());
    }

    public function testRand() {
      $arr = new ArrObj([1, 2, 3, 4, 5, 6]);

      srand(0);

      $random = $arr->rand();
      $this->assertEquals(2, $random);
    }
}
