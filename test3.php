<?php
class CheckDate {
  public $currentDate;
  public $color;
  public $width;

  function __construct($name, $color) {
    echo "Jalan.. \n";
    $this->name = $name;
    $this->color = $color;
  }
  function get_name() {
    return $this->name;
  }
  function get_color() {
    return $this->color;
  }
  function get_name_and_color() {
      $this->name = "arafat";
      return $this->name . "-" . $this->color;
  }
}

$apple = new Fruit("Apple", "red");
echo $apple->get_name();
echo "<br>";
echo $apple->get_color();
echo "<br>";
echo $apple->get_name_and_color();
?>