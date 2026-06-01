<?php
session_start();
require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {

        $stmt = $conn->prepare("SELECT id,username,password FROM users WHERE username =?");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();


        if ($user && $password == $user['password']) {
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            header('Location:dashboard.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex justify-center items-center bg-slate-200">
    <section class="w-2/3 flex flex-col justify-center items-center">
        <!-- card -->
        <div>
            <img src="../Images/lock.png" class="h-10 w-10 border p-2 bg-blue-500 rounded-md">
        </div>
        <div class="text-center">
            <h1 class="font-bold text-2xl text-white">Welcome back</h1>
            <p class="text-sm text-gray-400">Sign in to your account</p>
        </div>
        <!-- error message -->
        <main class="bg-gray-800 w-1/2 border-gray-600 p-6 rounded-2xl flex flex-col gap-5 mt-5 shadow-2xl">
            <?php if (!empty($error)): ?>
                <div class="border rounded-xl shadow-lg bg-red-950/50 border-red-600/40 w-full flex px-1 py-2 gap-2">
                    <img src="../Images/remove.png" class="w-6 h-6">
                    <span class="text-red-600"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            <!-- form -->
            <form action="login.php" method="POST" class="flex flex-col gap-5">
                <div class="flex flex-col">
                    <label for="username" class="text-gray-200 font-semibold">Username</label>
                    <input type="text" id="username" name="username"
                        class="border rounded-xl shadow-lg px-1 py-2  bg-slate-600/50 outline-none focus:ring-2 focus:ring-blue-600" placeholder="Enter your username" required>
                </div>

                <div class="flex flex-col">
                    <label for="password" class="text-gray-200 font-semibold">Password</label>
                    <input type="password" id="password" name="password"
                        class="border rounded-xl shadow-lg px-1 py-2  bg-slate-600/50 outline-none focus:ring-2 focus:ring-blue-600"
                        placeholder="Enter your password" required>
                </div>
                <button type="submit" class="rounded-xl bg-blue-800 px-1 py-2 text-white font-semibold">Sign In</button>
            </form>
        </main>

        <div class="mt-6">
            <span class="text-gray-600">Don't have an account?<a href="register.php" class="text-blue-800">Register here</a></span>
        </div>
    </section>
</body>

</html>