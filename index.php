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
        $this->points[] = array(
          'row' => $i,
          'column' => $j,
        );
      } 
    }
  }

  /**
   * Given a set of geocoordinates, retrieve aggregate point data from all 
   * available points.
   */
  public function fetch($x, $y) {

    if ($x > $this->options['grid_size'] * $this->options['x_increm'] ||
        $y > $this->options['grid_size'] * $this->options['y_increm']
    ) {
      throw new InvalidArgumentException('Invalid coordinates'); 
    }

  }

  /**
   * Helper that builds a single cell.
   */
  private function genCell($options) {

  }
}


$a = new Points(
  array(
    'grid_size' => 5,
    'x_increm' => 0.5,
    'y_increm' => 0.67,
  ));

$a->fetch(1, 25);
