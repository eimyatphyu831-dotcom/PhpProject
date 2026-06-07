<?php
session_start();


if (!isset($_SESSION['expression'])) {
    $_SESSION['expression'] = "";
}

function isError()
{
    return $_SESSION['expression'] === "Error" ||
        $_SESSION['expression'] === "Can't divide by zero";
}

//reset on error input
function clearErrorIfNewInput()
{
    if (isError()) {
        $_SESSION['expression'] = "";
    }
}


//insert multiplication automatically
function fixMultiplication($expr)
{


    $expr = preg_replace('/(\d)\(/', '$1*(', $expr);
    $expr = preg_replace('/\)\(/', ')*(', $expr);

    return $expr;
}

//handle post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $clear    = $_POST['clear'] ?? null;
    $number   = $_POST['number'] ?? null;
    $dot      = $_POST['dot'] ?? null;
    $operator = $_POST['operator'] ?? null;
    $equal    = $_POST['equal'] ?? null;

    switch (true) {

        case isset($clear):
            $_SESSION['expression'] = "";
            break;

        case isset($number):
            clearErrorIfNewInput();
            $_SESSION['expression'] .= $number;
            break;

        case isset($dot):
            clearErrorIfNewInput();
            $_SESSION['expression'] .= ".";
            break;

        case isset($operator):
            clearErrorIfNewInput();

            $last = substr($_SESSION['expression'], -1);

            if (in_array($last, ['+', '-', '*', '/', '.'])) {
                $_SESSION['expression'] = substr($_SESSION['expression'], 0, -1);
            }

            $_SESSION['expression'] .= $operator;
            break;

        case isset($equal):

            $expr = $_SESSION['expression'];

            if ($expr === "") break;
            $expr = fixMultiplication($expr);

            if (preg_match('/\/0(?!\d)/', $expr)) {
                $_SESSION['expression'] = "Can't divide by zero";
                break;
            }

            try {

                if (preg_match('/[\+\-\*\/]{2,}/', $expr)) {
                    throw new Exception("Invalid");
                }

                $result = eval("return $expr;");
                $_SESSION['expression'] = $result;
            } catch (Throwable $e) {
                $_SESSION['expression'] = "Error";
            }

            break;
    }
}

$display = $_SESSION['expression'] ?: "0";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-900 flex justify-center">
    <section class="w-[280px] h-full bg-gray-950/50 p-4 border border-purple-600/30 mt-6 gap-4 rounded-xl shadow-2xl">
        <div class="flex justify-center">
            <img src="../Images/calculator.png" class="w-5 h-5">
            <h1 class="font-bold text-white">PHP Calculator</h1>
        </div>
        <form action="index.php" method="POST" class="my-6">
            <input type="text" id="display" name="display" placeholder="0" class="border rounded-md bg-gray-950/50 border-gray-900 p-2 text-white font-bold text-2xl w-full mb-6 outline-none focus:ring-2 ring-purple-800 text-end" value="<?php echo htmlspecialchars($display); ?>">
            <div class="grid grid-cols-4 gap-4">
                <button type="submit" name="clear" value="C" class="rounded-md  border border-purple-600/30 px-4 py-2 text-red-700 font-bold">C</button>
                <button type="submit" name="operator" value="(" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">(</button>
                <button type="submit" name="operator" value=")" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">)</button>
                <button type="submit" name="operator" value="/" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">÷</button>
                <button type="submit" name="number" value="7" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">7</button>
                <button type="submit" name="number" value="8" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">8</button>
                <button type="submit" name="number" value="9" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">9</button>
                <button type="submit" name="operator" value="*" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">×</button>
                <button type="submit" name="number" value="4" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">4</button>
                <button type="submit" name="number" value="5" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">5</button>
                <button type="submit" name="number" value="6" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">6</button>
                <button type="submit" name="operator" value="-" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">-</button>
                <button type="submit" name="number" value="1" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">1</button>
                <button type="submit" name="number" value="2" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">2</button>
                <button type="submit" name="number" value="3" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">3</button>
                <button type="submit" name="operator" value="+" class="rounded-md  border border-purple-600/30 px-4 py-2 text-purple-700 bg-purple-950/20">+</button>
                <button type="submit" name="number" value="0" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white col-span-2 font-bold">0</button>
                <button type="submit" name="dot" value="." class="rounded-md  border border-purple-600/30 px-4 py-2 text-white font-bold">.</button>
                <button type="submit" name="equal" value="=" class="rounded-md  border border-purple-600/30 px-4 py-2 text-white bg-gradient-to-r from-purple-800 to-blue-600 font-bold">=</button>

            </div>
        </form>
    </section>

</body>

</html>