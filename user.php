<?php
$users = [
    [
        "name" => "Alex Walker",
        "role" => "Adminstrator",
        "status" => "Active",
        "avatar" => "AW"
    ],
    [
        "name" => "Sarah Smith",
        "role" => "Editor",
        "status" => "Active",
        "avatar" => "SS"
    ],
    [
        "name" => "Mike Jhson",
        "role" => "Subscriber",
        "status" => "Inactive",
        "avatar" => "MJ"
    ],
    [
        "name" => "Emily Davis",
        "role" => "Author",
        "status" => "Active",
        "avatar" => "ED"
    ],
    [
        "name" => "David Wilson",
        "role" => "Contributor",
        "status" => "Inactive",
        "avatar" => "DW"
    ],
    [
        "name" => "Jessica Taylor",
        "role" => "Manager",
        "status" => "Active",
        "avatar" => "JT"
    ]
];
$showOnlyActive = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex justify-center min-h-screen">
    <div class="bg-yellow-100 w-2/3 rounded-xl p-4">
        <div class="flex justify-between items-center">
            <div class="">
                <header class="items-center">
                    <h1 class="font-semibold text-3xl">User Directory</h1>
                    <p>Generated dynamically using a PHP foreach loop.</p>
                </header>
            </div>
            <div>
                <p>Total Users:
                    <?php echo count($users) ?>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-4">
            <?php
            foreach ($users as $user) {

                if ($showOnlyActive == true && $user["status"] == 'Inactive') {
                    continue; //skip
                }
                if ($user['status'] == 'Active') {
                    $cardOpacity = "opacity-100 bg-white";
                    $badgeColor = "bg-green-200 text-green-800";
                } else {
                    $cardOpacity = "opacity-50 bg-red-300";
                    $badgeColor = "bg-gray-200 text-red-800";
                }
            ?>
                <div class="rounded-2xl shadow-md p-4 <?php echo $cardOpacity; ?> flex gap-4">
                    <div class="bg-blue-500 rounded-3xl w-10 h-10 font-bold flex justify-center items-center">
                        <?php echo $user['avatar']; ?>
                    </div>
                    <div class="">
                        <h2 class="font-bold text-lg">
                            <?php echo $user['name']; ?>
                        </h2>
                        <p class="text-gray-500">
                            <?php echo $user['role']; ?>
                        </p>
                        <span class="bg-green-200 text-green-800 font-semibold px-2 rounded-lg <?php echo $badgeColor; ?>">
                            <?php echo $user['status']; ?>
                        </span>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
</body>

</html>