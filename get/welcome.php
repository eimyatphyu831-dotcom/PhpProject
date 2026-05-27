<?php
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if(isset($_GET['user_name']) && isset($_GET['fav_color'])){
    $submitName = $_GET['user_name'];
    $submitColor = $_GET['fav_color'];
    }else{
    echo "You can't accept this page directly!";
    exit();
}
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex justify-center items-center" style=background-color:<?php echo $submitColor; ?>>
    <div class="w-1/3 bg-orange-100 rounded-xl shadow-2xl flex flex-col gap-5 p-6">

        <div class="flex justify-center">
            <img src="../Images/user.png" class="w-12 h-12 border rounded-full p-2 bg-gray-200">
        </div>

        <div class="flex flex-col items-center">
            <h1 class="font-bold text-2xl">Hello,<?php echo $submitName; ?></h1>
            <p>We're glad to have you here.</p>
        </div>

        <div class="bg-slate-100 border rounded-md shadow-lg p-2 flex justify-center gap-4">
            <span class="w-6 h-6 rounded-full" style=background-color:<?php echo $submitColor; ?>></span>
            <p class="text-center">Your favorite color is <?php echo $submitColor; ?>.</p>
        </div>

        <div>
            <p class="text-gray-600 text-xs">🔗Look at the URL bar above &#8212; you can see your data inside it!</p>
        </div>


        <a href="index.php" class="rounded-md p-2 text-white bg-blue-600 w-full text-center">&#8592; Go Back</a>
    </div>

</body>

</html>