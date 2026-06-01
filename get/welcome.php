<?php
//Form method
// if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if (isset($_GET['user_name']) && isset($_GET['fav_color'])) {
        $submitName = htmlspecialchars($_GET['user_name']);
        $submitColor = htmlspecialchars($_GET['fav_color']);
    } else {
        $submitName = "Guest";
        $submitColor = "white";
    }
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $submitName; ?>!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex justify-center items-center" style=background-color:<?php echo $submitColor; ?>;>
    <div class="w-1/3 bg-white rounded-xl shadow-2xl flex flex-col gap-6 p-8">

        <div class="flex justify-center">
            <img src="../Images/user.png" class="w-10 h-10 border rounded-full p-2 bg-indigo-200">
        </div>

        <div class="flex flex-col items-center">
            <h1 class="font-bold text-2xl">Hello,<?php echo $submitName; ?>!</h1>
            <p class="text-sm text-gray-400">We're glad to have you here.</p>
        </div>

        <div class="bg-slate-100 border rounded-md shadow-lg p-2 flex justify-center gap-3">
            <span class="w-6 h-6 rounded-full border-2" style=background-color:<?php echo $submitColor; ?>;></span>
            <p class="text-center">Your favorite color is <b><?php echo $submitColor; ?>.</b></p>
        </div>

        <div>
            <p class="text-gray-400 text-xs text-center">🔗Look at the URL bar above &#8212; you can see your data inside it!</p>
        </div>

        <a href="index.php" class="rounded-md p-2 text-white bg-indigo-700 text-center hover:bg-indigo-400">&#8592; Go Back</a>
    </div>

</body>

</html>