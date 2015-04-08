<?php
print '<pre>';
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * @file
 * The Geocord.
 */ 

/**
 * A simple Points interface to follow.
 */
interface PointsInterface {

  /**
   * Given a set of geocoordinates, retrieve aggregate point data from all 
   * available points.
   */
  public function fetch($x, $y);

}

/**
 * The Points controller to operate on points.
 *
 * @param array options
 *  An associative array of options for to apply to this Points instance.
 */
class Points implements PointsInterface {

  /**
   * @var array $options
   *  An array of options that controls how the Points operate.
   */
  private $options;

  /**
   * @var array points
   *  An array of all the current points.
   */
  private $points = array();

  public function __construct($options) {
   $this->options = $options;
   for ($i = 0; $i < $options['grid_size']; $i++) {
     for ($j = 0; $j < $options['grid_size']; $j++) {
        $this->points["{$i}:{$j}"] = array(
          'row' => $i,
          'column' => $j,
          'value' => rand(0, 100),
        );
      } 
    }
  }

  /**
   * Given a set of geo-coordinates, retrieve aggregate point data from all 
   * available points.
   */
  public function fetch($x, $y) {
    // Subtract one b/c the row and column keys start at 0.
    $max_index = $this->options['grid_size'] - 1;
    if ($x > $max_index * $this->options['x_increm'] ||
        $y > $max_index * $this->options['y_increm']
    ) {
      throw new InvalidArgumentException('Invalid coordinates'); 
    }
    
    $row_num = round($x / $this->options['x_increm']);
    $col_num = round($y / $this->options['y_increm']);
    return $this->points["{$row_num}:{$col_num}"];
  }

}


$a = new Points(
  array(
    'grid_size' => 5,
    'x_increm' => 0.5,
    'y_increm' => 0.67,
  ));

$b = $a->fetch(1.5, 2.62);
var_dump($b);
