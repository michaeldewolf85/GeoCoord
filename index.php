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

  /**
   * @var object $data
   *  Data on the point.
   */
  private $data;

  public function __construct($options) {
   $this->options = $options;
   $this->data = new stdClass();
   for ($i = 0; $i < $options['grid_size']; $i++) {
     for ($j = 0; $j < $options['grid_size']; $j++) {
        $this->points["{$i}:{$j}"] = array(
          'row' => $i,
          'column' => $j,
          'x' => $i * $options['x_increm'],
          'y' => $j * $options['y_increm'],
          'value' => rand(0, 100),
        );
      } 
    }
  }

  /**
   * Given a set of geo-coordinates, retrieve aggregate point data from all 
   * available points.
   *
   * @return object
   *  Returns data on the point
   */
  public function fetch($x, $y) {
    $this->crunch($x, $y);
    return $this->data;
  }

  /**
   * Given a set of geo-coordinates, retrieve aggregate point data from all 
   * available points.
   */
  private function crunch($x, $y) {
    // Subtract one b/c the row and column keys start at 0.
    $max_index = $this->options['grid_size'] - 1;
    if ($x > $max_index * $this->options['x_increm'] ||
        $y > $max_index * $this->options['y_increm']
    ) {
      throw new InvalidArgumentException('Invalid coordinates'); 
    }
    
    $row_num = round($x / $this->options['x_increm']);
    $col_num = round($y / $this->options['y_increm']);
    $next_row_num = $row_num < $max_index ? $row_num + 1 : $row_num - 1;
    $next_col_num = $col_num < $max_index ? $col_num + 1 : $col_num - 1;

    $data = &$this->data;
    $data->closestPoint = $this->fetchPoint($this->points["{$row_num}:{$col_num}"], $x, $y);
    $data->swingX = $this->fetchPoint($this->points["{$next_row_num}:{$col_num}"], $x, $y);
    $data->swingY = $this->fetchPoint($this->points["{$row_num}:{$next_col_num}"], $x, $y);
    $this->weightedAverage($data);
  }

  /** 
   * Helper function to populate point metadata.
   *  
   * @param array $point
   *  The point to populate.
   * @param float $x
   *  The x coordinate of the reference point.
   * @param float $y 
   *  The y coordinate of the reference point.
   *
   * @return array $point
   *  The updated point.
   */
  private function fetchPoint($point, $x, $y) {
   $x_dist = abs($point['x'] - $x);
   $y_dist = abs($point['y'] - $y);
   $point['distance'] = sqrt(pow($x_dist, 2) + pow($y_dist, 2));
   return $point;
  }

  /**
   * Calculate the weighted average value for the point.
   *
   * @param stdClass $data
   *  The data, passed by reference.
   */
  private function weightedAverage(&$data) {
    $accuracy = $this->options['accuracy'];
    $closest_dist = $data->closestPoint['distance'];
    $x_factor = round($closest_dist / $data->swingX['distance'] * $accuracy);
    $y_factor = round($closest_dist / $data->swingY['distance'] * $accuracy);
    $avg_sum = $accuracy * $data->closestPoint['value'] + 
               $x_factor * $data->swingX['value'] + 
               $y_factor * $data->swingY['value']; 
    $data->wa = $avg_sum / ($accuracy + $x_factor + $y_factor);
  }

}


$a = new Points(
  array(
    'grid_size' => 5,
    'x_increm' => 0.5,
    'y_increm' => 0.67,
    'accuracy' => 10,
  ));

$b = $a->fetch(1.2, 2.62);
var_dump($b);
