<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-200 min-h-screen flex items-center justify-center">
    <form action="process.php" method="POST" class="flex flex-col bg-white w-1/3 p-8 rounded-xl shadow-xl border border-gray-200 gap-2">
        <h1 class="font-bold text-2xl text-center mb-6">Secure Login</h1>
        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label for="email">Email</label>
            <input type="email" id="email" name="user_email" class="font-semibold rounded-md border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>
        <!-- Password -->
        <div class="flex flex-col gap-2">
            <label for="password">Password</label>
            <input type="password" id="password" name="user_password" class="font-semibold rounded-md border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>
        <button type="submit" class="bg-blue-600 p-2 rounded-md text-white font-bold mt-6">Log In</button>
    </form>
</body>

</html>