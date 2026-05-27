<?php
//Form Method
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $submitEmail = $_POST['user_email'];
    $submitPassword = $_POST['user_password'];

    $correctEmail = "admin@gmail.com";
    $correctPassword = "admin";

    if ($submitEmail == $correctEmail && $submitPassword == $correctPassword) {
        $message = "Welcome to Admin!";
        $bgColor = "bg-green-300 text-green-900";
    } else {
        $message = "Invalid email or password!";
        $bgColor = "bg-red-300 text-red-900";
    }
}else{
    echo "You can't accept this page directly!";
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Process</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-200 min-h-screen flex items-center justify-center ">
    <div class="w-1/3 bg-white rounded-xl p-6 flex flex-col gap-2 shadow-2xl">
        <div class="<?php echo $bgColor; ?> rounded-md p-4 shadow-xl w-full">
            <h1 class="font-bold text-xl"><?php echo $message; ?></h1>
            <p>You entred:<?php echo $submitEmail; ?></p>
        </div>

        <a href="login.php" class="bg-blue-500 text-center font-semibold px-4 py-2 mt-5 shadow-xl rounded-md">Back To Login</a>

    </div>
</body>

</html>