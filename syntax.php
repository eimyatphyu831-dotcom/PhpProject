<?php
//path and data type output
$x = 10;
$x += 5;
var_dump($x);

//for loop
for ($i = 1; $i <= 5; $i++) {
    echo "Count:$i";
}
echo "<br>";

//while loop
$n = 1;
while ($n <= 3) {
    echo "While:$n <br>";
    $n++;
}
echo "<br>";

//do loop
$x = 10;
do {
    echo "Do:$x";
    $x++;
} while ($x <= 12);
echo "<br>";

//for each with index key => value
$fruits = ["Apple", "Banana", "Cherry"];
foreach ($fruits as $index => $fruit) {
    echo $index + 1 . ":" . $fruit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>