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

    if (isset($_POST['update_user'])) {
        $idToUpdate = $_POST['user_id'];
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);

        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $idToUpdate);
        $stmt->execute();
        $stmt->close();

        header("Location: crud.php");
        exit; //after redirect
    }
}
//take connection and select all users from database
$result = $conn->query('SELECT * FROM users ORDER BY id DESC');
$allUsers = $result ? $result->fetch_all(MYSQLI_ASSOC) : []; //ternary operator ->'?'

$editUser = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];

    $stmt = $conn->prepare('SELECT  * FROM users WHERE id=?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editUser = $result->fetch_assoc();
    }
    $stmt->close();
}


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
                <h1 class="font-bold text-2xl border-b">
                    <?php echo $editUser ? "Update User" : "Add New User"; ?>
                </h1>
                <?php if ($editUser): ?>
                    <input type="hidden" name="user_id" value="<?php echo $editUser ? $editUser['id'] : '' ?>">
                <?php endif; ?>
                <div class="flex flex-col">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $editUser ? $editUser['name'] : '' ?>" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo $editUser ? $editUser['email'] : '' ?>" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="role">Role</label>
                    <select id="role" name="role" required class="border rounded-md p-1.5">
                        <option value="Admin" <?php echo ($editUser && $editUser['role'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="Editor" <?php echo ($editUser && $editUser['role'] == 'Editor') ? 'selected' : '' ?>>Editor</option>
                        <option value="User" <?php echo (!$editUser || $editUser['role'] == 'User') ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <?php if ($editUser): ?>
                    <div class="flex gap-2 justify-center mt-2">
                        <button type="submit" name="update_user" class="bg-green-700 text-white  px-8 py-2 rounded-sm hover:bg-green-500">Update</button>
                        <a href="crud.php" class="bg-gray-400 text-black px-8 py-2 rounded-sm hover:bg-gray-300 border">Cancel</a>
                    </div>
                <?php else: ?>
                    <button type="submit" name="add_user" class="bg-blue-600 text-white rounded-md px-4 py-2 mt-2">+Save to Database</button>
                <?php endif; ?>
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
                                    <td class="p-3 "><span class="border bg-gray-200 px-2 py-1 rounded-md"><?php echo $user['role'] ?></span></td>
                                    <td class="p-3">



                                        <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                                        <div class="flex justify-center gap-4">
                                            <form action="crud.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <button type="submit" name="delete_user" class="text-red-600 flex hover:text-red-800">
                                                    <img src="../Images/delete.png" alt="Delete" class="w-5 h-5">
                                                    Delete
                                                </button>
                                            </form>
                                            <a href="crud.php?edit_id=<?php echo $user['id'] ?>" class="text-blue-600 flex hover:text-blue-800">
                                                <img src="../Images/edit.png" alt="Edit" class="w-5 h-5 ">
                                                Edit
                                                </button>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>


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