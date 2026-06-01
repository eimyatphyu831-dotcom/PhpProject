<?php
session_start();
require_once 'config/db.php';

//print_r($_SESSION);

//echo "Session ID: " . session_id();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$username=htmlspecialchars($_SESSION['username']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen">
    <section class="flex flex-col gap-6">
        <!-- navigation -->
        <nav class="flex justify-between bg-gray-900 px-4 py-2 sticky top-0 items-center">
            <div class="flex gap-2">
                <span class="rounded-lg border-1 bg-blue-800 w-8"></span>
                <h1 class="text-white  font-bold text-lg">MyApp</h1>
            </div>
            <div class="flex gap-3">
                <p class="text-gray-400 text-sm pt-1">Logged in as <?php echo $username; ?></p>
                <a href="logout.php" class="border bg-red-950/50 border-red-600/40 rounded-xl px-4 py-1 text-red-500/65 shadow-lg flex text-sm"><img src="../Images/logout.png" class="w-5 pr-2">Logout</a>
            </div>

        </nav>
        <!-- main -->
        <main class="flex flex-col items-center gap-6">
            <!-- welcome -->
            <div class="flex bg-purple-200 p-4 rounded-2xl w-2/3 gap-3 border-t">
                <span class="rounded-2xl border-1 bg-blue-800 w-12 text-center text-white font-bold text-3xl items-center flex justify-center"><?php echo strtoupper(substr($username, 0, 1)); ?></span>
                <div>
                    <p class="text-gray-400 text-sm">Welcome back</p>
                    <h1 class="font-bold text-red-400 text-2xl"><?php echo $username; ?>👋</h1>
                </div>
            </div>
            </div>
            <!-- card-3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-2/3 ">
                <div class="bg-gray-900 rounded-xl p-3 shadow-lg">
                    <img src="../Images/check-button.png" class="w-8 rounded-md border border-green-500/40 bg-green-950/50 p-2 mb-2">
                    <p class="text-gray-400 text-sm">Status</p>
                    <h1 class="text-white font-bold">Authenticated</h1>
                </div>
                <div class="bg-gray-900 rounded-xl p-3 shadow-lg">
                    <img src=" ../Images/user.png" class="w-8 rounded-md border border-blue-500/40 bg-blue-950/50 p-2 mb-2">
                    <p class="text-gray-400 text-sm">Username</p>
                    <h1 class="text-white font-bold"><?php echo $username; ?></h1>
                </div>
                <div class="bg-gray-900 rounded-xl p-3 shadow-lg">
                    <img src=" ../Images/locked.png" class="w-8 rounded-md border border-purple-500/40 bg-purple-950/50 p-2 mb-2">
                    <p class="text-gray-400 text-sm">Session</p>
                    <h1 class="text-white font-bold">Active</h1>
                </div>

        </main>
    </section>

</body>

</html>