<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-200 min-h-screen flex justify-center items-center">
    <form action="welcome.php" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-lg w-1/3 p-8 flex flex-col gap-5">
        <div class="flex justify-center">
            <img src="../Images/user.png" class="w-10 h-10 border rounded-full p-2 bg-indigo-200">
        </div>
        <div class="flex flex-col items-center">
            <h1 class="font-bold text-2xl">Tell Us About Yourself</h1>
            <p class="text-sm text-gray-400">Fill in your details below</p>
        </div>

        <div class="flex flex-col gap-1">
            <label for="user_name">Your Name</label>
            <input type="text" id="name" name="user_name" placeholder="e.g.John doe" required class="border rounded-md shadow-lg border-gray-200 p-2 outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <div class="flex flex-col gap-1">
            <label for="fav_color">Favourite Color</label>
            <input type="text" id="color" name="fav_color" placeholder="e.g.Blue" required class="border rounded-md shadow-lg border-gray-200 p-2 outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <button type="submit" class="rounded-md p-2 text-white bg-indigo-700 w-full hover:bg-indigo-400">Submit &#8594;</button>
    </form>
</body>

</html>