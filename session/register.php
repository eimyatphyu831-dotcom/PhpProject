<?php
session_start();
require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all field.";
    } elseif (strlen($password) < 6) {    // Check password length =>(strlen())
        $error = "Password at least 6 characters long.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param('s', $username);   //'s' => string
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already exsit!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users(username,password) VALUES(?,?)");
            $stmt->bind_param('ss', $username, $password);

            if ($stmt->execute()) {
                $success = "Registration Successful!";
            } else {
                $error = "Registration Fail!";
            }
            $stmt->close();
        }
        $check->close();
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex justify-center items-center bg-slate-300">
    <section class="w-2/3 flex flex-col justify-center items-center">
        <!-- card -->
        <div>
            <img src="../Images/add-user.png" class="h-10 w-10 border p-2 bg-blue-600 rounded-md">
        </div>
        <div class="text-center">
            <h1 class="font-bold text-2xl">Create an account</h1>
            <p class="text-sm text-gray-400">Sign up to get started</p>
        </div>

        <main class="bg-gray-800 w-1/2 border-gray-600 p-6 rounded-2xl flex flex-col gap-5 mt-5 shadow-2xl">
            <!-- error message -->
            <?php if (!empty($error)): ?>
                <div class="border rounded-xl shadow-lg bg-red-950/50 border-red-600/40 w-full flex px-1 py-2 gap-2">
                    <img src="../Images/remove.png" class="w-6 h-6">
                    <span class="text-red-600 text-sm"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            <!-- success message -->
            <?php if (!empty($success)): ?>
                <div class="border rounded-xl shadow-lg bg-green-950/50 border-green-600/40 w-full flex px-1 py-2 ">

                    <img src="../Images/check-button.png" class="w-6 h-6">
                    <span class="text-green-600 text-sm"><?php echo htmlspecialchars($success); ?></span>


                    <a href="login.php" class="text-green-700 underline pl-32">Login&#8594;</a>
                </div>
            <?php endif; ?>
            <!-- form -->
            <form action="register.php" method="POST" class="flex flex-col gap-5">
                <div class="flex flex-col">
                    <label for="username" class="text-gray-200 font-semibold">Username</label>
                    <input type="text" id="username" name="username"
                        class="border rounded-xl shadow-lg px-1 py-2  bg-slate-600/50 outline-none focus:ring-2 focus:ring-blue-600" placeholder="Choose a username" required>
                </div>

                <div class="flex flex-col">
                    <label for="password" class="text-gray-200 font-semibold">Password</label>
                    <input type="password" id="password" name="password"
                        class="border rounded-xl shadow-lg px-1 py-2  bg-slate-600/50 outline-none focus:ring-2 focus:ring-blue-600"
                        placeholder="Minimum 6 characters" required>
                </div>
                <button type="submit" class="rounded-xl bg-blue-800 px-1 py-2 text-white font-semibold">Create
                    Account</button>
            </form>
        </main>

        <div class="mt-6">
            <span class="text-gray-600">Already have an account?<a href="login.php" class="text-red-400">Login here</a></span>
        </div>
    </section>
</body>

</html>