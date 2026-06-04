<?php

require_once 'config/db.php';
$allTitles = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // insert
    if (isset($_POST['add_list'])) {
        $title = htmlspecialchars($_POST['title']);
        $stmt = $conn->prepare('INSERT INTO todos(title) VALUES (?)');
        $stmt->bind_param('s', $title);
        $stmt->execute();
        $stmt->close();

        header('Location:todo.php');
        exit;
    }
    //delete
    if (isset($_POST['delete_list'])) {
        $idToDelete = $_POST['list_id'];
        $stmt = $conn->prepare('DELETE FROM todos WHERE id=?');
        $stmt->bind_param('i', $idToDelete);
        $stmt->execute();
        $stmt->close();

        header('Location:todo.php');
        exit;
    }
    //update
    if (isset($_POST['update_list'])) {
        $idToUpdate = $_POST['list_id'];
        $title = htmlspecialchars($_POST['title']);

        $stmt = $conn->prepare('UPDATE todos SET title=? WHERE id=?');
        $stmt->bind_param('si', $title, $idToUpdate);
        $stmt->execute();
        $stmt->close();

        header('Location:todo.php');
        exit;
    }

    // mark task complete/incomplete
    if (isset($_POST['toggle_complete'])) {

        $taskId = $_POST['task_id'];
        $completed = isset($_POST['completed']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ?");

        $stmt->bind_param("ii", $completed, $taskId);
        $stmt->execute();
        $stmt->close();

        header("Location: todo.php");
        exit();
    }

    // clear completed tasks
    if (isset($_POST['clear_completed'])) {

        $stmt = $conn->prepare("DELETE FROM todos WHERE completed = 1");

        $stmt->execute();
        $stmt->close();

        header("Location: todo.php");
        exit();
    }
}

$result = $conn->query('SELECT * FROM todos ORDER BY id DESC');
$allTitles = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

//complete task count
$totalTasks = count($allTitles);
$completedTasks = 0;
foreach ($allTitles as $title) {
    if ($title['completed']) {
        $completedTasks++;
    }
}


$editList = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $stmt = $conn->prepare('SELECT * FROM todos WHERE id=?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editList = $result->fetch_assoc();
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 min-h-screen flex flex-col items-center mt-4">
    <section class="bg-white border rounded-lg w-1/3 p-4 flex flex-col gap-5">
        <!-- logo -->
        <div class="flex gap-2 items-center">
            <img src="../Images/checklist.png" class="w-6 h-5" alt="Checklist">
            <h1 class="font-bold text-lg">My Todo List</h1>
        </div>
        <!-- assignment -->
        <form action="todo.php" method="POST">
            <?php if ($editList): ?>
                <input type="hidden" name="list_id" value="<?php echo $editList ? $editList['id'] : '' ?>">
            <?php endif; ?>
            <div class="flex gap-4">
                <input type="text" name="title" class="w-full border rounded-md p-2 border-gray-300" value="<?php echo $editList ? $editList['title'] : '' ?>" placeholder="e.g.Assignment for today" required>
                <?php if ($editList): ?>
                    <button type="submit" name="update_list" class="bg-gradient-to-r from-green-400 to-indigo-400 rounded-lg px-4 py-2 text-white hover:bg-none hover:bg-green-600">✔</button>
                    <a href="todo.php" name="" class="bg-gradient-to-r from-red-400 to-indigo-400 rounded-lg px-4 py-2 text-white hover:bg-none hover:bg-red-600">❌</a>
                <?php else: ?>
                    <button type="submit" name="add_list" class="bg-gradient-to-r from-purple-500 to-blue-400 rounded-lg px-4 py-2 text-white hover:bg-none hover:bg-purple-400">➕</button>
                <?php endif; ?>
            </div>

        </form>

        <!-- form -->
        <?php foreach ($allTitles as $title): ?>
            <div class="flex w-full border rounded-md justify-between p-2 border-gray-300">
                <div class="flex gap-2">
                    <form action="todo.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $title['id']; ?>">
                        <input type="checkbox" name="completed" class="rounded-md border-2 border-purple-500  accent-purple-500 focus:ring-purple-500" onchange="this.form.submit()" <?php echo $title['completed'] ? 'checked' : ''; ?>>
                        <input type="hidden" name="toggle_complete" value="1">
                    </form>
                    <p class="font-semibold text-gray-700 <?php echo $title['completed'] ? 'line-through text-gray-400' : ''; ?>"><?php echo $title['title']; ?></p>
                </div>
                <div class="flex gap-6">
                    <a href="todo.php? edit_id=<?php echo $title['id'] ?>"><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                    <form action="todo.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="list_id" value="<?php echo $title['id']; ?>">
                        <button type="submit" name="delete_list"><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>

        <!-- <div class="flex w-full border rounded-md justify-between p-2 border-gray-300">
            <div class="flex gap-2">
                <input type="checkbox" value="" name="" class="h-5 w-5 rounded-sm border-2 border-purple-500 accent-purple-500 focus:ring-purple-500">
                <p>Create database schema</p>
            </div>
            <div class="flex gap-6">
                <a href=""><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                <a href=""><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></a>
            </div>
        </div>

        <div class="flex w-full border rounded-md justify-between p-2 border-gray-300">
            <div class="flex gap-2">
                <input type="checkbox" value="" name="" class="h-5 w-5 rounded-sm border-2 border-purple-500 accent-purple-500 focus:ring-purple-500">
                <p>Build PHP CRUD functions</p>
            </div>
            <div class="flex gap-6">
                <a href=""><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                <a href=""><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></a>
            </div>
        </div>

        <div class="flex w-full border rounded-md justify-between p-2 border-gray-300">
            <div class="flex gap-2">
                <input type="checkbox" value="" name="" class="h-5 w-5 rounded-sm border-2 border-purple-500 accent-purple-500 focus:ring-purple-500">
                <p>Add form validation</p>
            </div>
            <div class="flex gap-6">
                <a href=""><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                <a href=""><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></a>
            </div>
        </div>

        <div class="flex w-full border rounded-md justify-between p-2 border-gray-300">
            <div class="flex gap-2">
                <input type="checkbox" value="" name="" class="h-5 w-5 rounded-sm border-2 border-purple-500 accent-purple-500 focus:ring-purple-500">
                <p>Style with CSS</p>
            </div>
            <div class="flex gap-6">
                <a href=""><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                <a href=""><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></a>
            </div>
        </div> -->


        <div class="flex justify-between border-t border-gray-300 pt-3">

            <span class="text-gray-600 font-semibold"><?php echo $completedTasks; ?>of<?php echo $totalTasks; ?>completed
            </span>

            <form action="todo.php" method="POST">
                <button type="submit" name="clear_completed" class="text-fuchsia-700 font-semibold hover:text-fuchsia-900 transition-colors" onclick="return confirm('Delete all completed tasks?')">
                    Clear completed
                </button>
            </form>

        </div>
    </section>
</body>

</html>