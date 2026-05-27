<?php
$destinations = [
    [
        "image" => "Images/Destination 1.png",
        "place" => "Rome,Italy",
        "price" => "$5.42k",
        "timage" => "Images/navigation 1.png",
        "duration" => "10 Days Trip"

    ],
    [
        "image" => "Images/Destination 2.jpg",
        "place" => "London,UK",
        "price" => "$4.2k",
        "timage" => "Images/navigation 1.png",
        "duration" => "12 Days Trip"

    ],
    [
        "image" => "Images/Destination 3.png",
        "place" => "Full Europe",
        "price" => "$15k",
        "timage" => "Images/navigation 1.png",
        "duration" => "28 Days Trip"

    ]

];
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen flex flex-col items-center">
    
        <header class="text-center py-5">
            <p class="text-gray-600">Top Selling</p>
            <h1 class="font-bold text-3xl font-serif">Top Destinations</h1>
        </header>
    

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
        <?php
        foreach ($destinations as $destination) {


        ?>
            <div class="bg-white rounded-3xl shadow-xl w-[300px] hover:scale-105 transition">
                <div>
                    <img src="<?php echo $destination['image']; ?>" alt="Rome" class="w-full h-[350px] rounded-t-3xl">
                </div>

                <div class="flex flex-col p-4">
                    <div class="flex justify-between">
                        <h2 class="">
                            <?php echo $destination['place']; ?>
                        </h2>
                        <p class="">
                            <?php echo $destination['price']; ?>
                        </p>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <img src="<?php echo $destination['timage']; ?>">
                        <p class="">
                            <?php echo $destination['duration']; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>