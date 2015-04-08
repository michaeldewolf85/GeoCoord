# GeoCoord

GeoCoord creates a Points class which implements a PointsInterface. The options it accepts can be summarized as follows:

````
array(
  'grid_size' => 5,
  'x_increm' => 0.5,
  'y_increm' => 0.67,
  'accuracy' => 10,
)
````

From these values a grid of points are created with random values (between 1 and 100) associated with each.

The Points::fetch() method returns data about the three closest points to any coordinates as well as a weighted average value for the input coordinates. This weighted average is discerned by the relative distance of the input coordinates in relation to the three closest points' values.

Here is a sample return, wa at the bottom is the weighted average.

````
object(stdClass)#2 (4) {
  ["closestPoint"]=>
  array(6) {
    ["row"]=>
    int(2)
    ["column"]=>
    int(4)
    ["x"]=>
    float(1)
    ["y"]=>
    float(2.68)
    ["value"]=>
    int(73)
    ["distance"]=>
    float(0.20880613017821)
  }
  ["swingX"]=>
  array(6) {
    ["row"]=>
    int(3)
    ["column"]=>
    int(4)
    ["x"]=>
    float(1.5)
    ["y"]=>
    float(2.68)
    ["value"]=>
    int(22)
    ["distance"]=>
    float(0.30594117081557)
  }
  ["swingY"]=>
  array(6) {
    ["row"]=>
    int(2)
    ["column"]=>
    int(3)
    ["x"]=>
    float(1)
    ["y"]=>
    float(2.01)
    ["value"]=>
    int(5)
    ["distance"]=>
    float(0.64195015382816)
  }
  ["wa"]=>
  float(44.95)
}
````
