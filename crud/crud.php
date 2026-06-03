<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // crud operations ('no logic')
    if (isset($_POST['add_user'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);

        $stmt = $conn->prepare("INSERT INTO users(name,email,role) VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $role);
        $stmt->execute();
        $stmt->close();

        header("Location: crud.php");
        exit; //after redirect
    }

    if (isset($_POST['delete_user'])) {
        $idToDelete = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $idToDelete);
        $stmt->execute();
        $stmt->close();

        header("Location: crud.php");
        exit; //after redirect
    }
}
//take connection and select all users from database
$result = $conn->query('SELECT * FROM users ORDER BY id DESC');
$allUsers = $result ? $result->fetch_all(MYSQLI_ASSOC) : []; //ternary operator ->'?'
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-400 min-h-screen px-4 py-4 md:px-24">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Form -->
        <section class="w-full md:w-1/3">
            <form action="crud.php" method="POST" class="w-full bg-white rounded-md shadow-lg p-6 flex flex-col gap-4">
                <h1 class="font-bold text-2xl border-b">Add New User</h1>
                <div class="flex flex-col">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="role">Role</label>
                    <select id="role" name="role" required class="border rounded-md p-1.5">
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                        <option value="Editor">Editor</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="bg-blue-600 text-white rounded-md px-4 py-2 mt-4">+Save to Database</button>
            </form>

        </section>

        <!-- Registered Users -->
        <section class="w-full">
            <div class="w-full bg-white flex flex-col gap-4 p-6 rounded-md shadow-lg">
                <h1 class="font-bold text-2xl border-b">Registered Users</h1>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-100 text-center text-gray-700">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($allUsers as $user): ?>
                                <tr class="border-b text-center hover:bg-gray-50">
                                    <td class="p-3">#<?php echo $user['id'] ?></td>
                                    <td class="p-3"><?php echo $user['name'] ?></td>
                                    <td class="p-3"><?php echo $user['email'] ?></td>
                                    <td class="p-3 "><span class="border bg-gray-200 px-2 py-1"><?php echo $user['role'] ?></span></td>
                                    <td class="p-3">
                                        <form action="crud.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                                            <div class="flex justify-center gap-4">
                                                <button type="submit" name="edit_user" class="text-blue-600 flex hover:text-blue-800">
                                                    <img src="../Images/edit.png" alt="Edit" class="w-5 h-5 ">
                                                    Edit
                                                </button>
                                                <button type="submit" name="delete_user" class="text-red-600 flex hover:text-red-800">
                                                    <img src="../Images/delete.png" alt="Delete" class="w-5 h-5">
                                                    Delete
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- <tr class="border-b text-center">
                                <td class="p-3">#3</td>
                                <td class="p-3">Agent3</td>
                                <td class="p-3">ageb@gmail.com</td>
                                <td class="p-3"><span class="border bg-gray-200 px-2 py-1">Admin</span></td>
                                <td class="p-3">
                                    <form action="crud.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="3">
                                        <div class="flex justify-center gap-4">
                                            <button type="submit" name="edit_user" class="text-blue-600 flex">
                                                <img src="../Images/edit.png" alt="Edit" class="w-5 h-5">
                                                Edit
                                            </button>
                                            <button type="submit" name="delete_user" class="text-red-600 flex">
                                                <img src="../Images/delete.png" alt="Delete" class="w-5 h-5">
                                                Delete
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr> -->

                            <!-- <tr class="border-b text-center">
                                <td class="p-3">#2</td>
                                <td class="p-3">Luis Kiocko</td>
                                <td class="p-3">aa@gmail.com</td>
                                <td class="p-3"><span class="border bg-gray-200 px-2 py-1">Admin</span></td>
                                <td class="p-3">
                                    <form action="crud.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="2">
                                        <div class="flex justify-center gap-4">
                                            <button type="submit" name="edit_user" class="text-blue-600 flex">
                                                <img src="../Images/edit.png" alt="Edit" class="w-5 h-5">
                                                Edit
                                            </button>
                                            <button type="submit" name="delete_user" class="text-red-600 flex">
                                                <img src="../Images/delete.png" alt="Delete" class="w-5 h-5">
                                                Delete
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <!-- </tr>  -->
                            <?php if (empty($allUsers)): ?>
                                <tr>
                                    <td colspan="5" class="text-gray-500 text-center">No users found in the database.Add one!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </section>
    </div>
</body>

</html>