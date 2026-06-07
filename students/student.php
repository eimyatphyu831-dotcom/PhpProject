<?php

require_once 'config/db.php';
$allStudents = [];
$searchQuery = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_student'])) {
    $searchQuery = trim($_GET['search_student']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // add student
    if (isset($_POST['add_student'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $course = htmlspecialchars($_POST['course']);
        $status = htmlspecialchars($_POST['status']);

        $stmt = $conn->prepare('INSERT INTO students (name,email,course,status)VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $name, $email, $course, $status);
        $stmt->execute();
        $stmt->close();

        header("Location: student.php");
        exit; //after redirect
    }

    //delete student
    if (isset($_POST['delete_student'])) {
        $idToDelete = $_POST['student_id'];
        $stmt = $conn->prepare('DELETE FROM students WHERE id=?');
        $stmt->bind_param('i', $idToDelete);
        $stmt->execute();
        $stmt->close();

        header('location:student.php');
        exit;
    }

    //update student
    if (isset($_POST['update_student'])) {
        $idToUpdate = htmlspecialchars($_POST['student_id']);
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $course = htmlspecialchars($_POST['course']);
        $status = htmlspecialchars($_POST['status']);

        $stmt = $conn->prepare('UPDATE students SET name=?,email=?,course=?,status=? WHERE id=? ');
        $stmt->bind_param('ssssi', $name, $email, $course, $status, $idToUpdate);
        $stmt->execute();
        $stmt->close();

        header("Location: student.php");
        exit; //after redirect

    }
}

// Pagination setup
$itemsPerPage = 4;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Get total count
$search = $_GET['search'] ?? '';
if ($search != '') {
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM students WHERE name LIKE ? OR email LIKE ? OR course LIKE ?");
    $searchTerm = "%$search%";
    $countStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalItems = $countResult->fetch_assoc()['total'];
    $countStmt->close();
} else {
    $countResult = $conn->query("SELECT COUNT(*) as total FROM students");
    $totalItems = $countResult->fetch_assoc()['total'];
}

// Calculate total pages
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = min($currentPage, max(1, $totalPages));
$offset = ($currentPage - 1) * $itemsPerPage;

// Fetch paginated results
if ($search != '') {
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR course LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT * FROM students ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}
$allStudents = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Calculate showing range
$startItem = $totalItems > 0 ? $offset + 1 : 0;
$endItem = min($offset + $itemsPerPage, $totalItems);
// $result = $conn->query('SELECT * FROM students ORDER BY id DESC');
// $allStudents = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$editStudent = null;
if (isset($_GET['edit_id'])) {
    $editId = intval($_GET['edit_id']);
    $stmt = $conn->prepare('SELECT * FROM students WHERE id=?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editStudent = $result->fetch_assoc();
    }
    $stmt->close();
}

$showForm = (isset($_GET['add']) && $_GET['add'] === 'true') || $editStudent !== null;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 flex justify-center py-4 px-4">

    <!-- form -->
    <?php if ($showForm): ?>
        <section class="w-1/3 pr-5">
            <form action="student.php" method="POST" class="w-full bg-gray-800 rounded-md shadow-lg p-6 flex flex-col gap-4 text-white">
                <h1 class="font-bold text-2xl border-b"><?php echo $editStudent ? 'Edit Student' : ' 📝 Add New Student'; ?></h1>

                <?php if ($editStudent): ?>
                    <input type="hidden" name="student_id" value="<?php echo $editStudent['id']; ?>">
                <?php endif; ?>

                <div class="flex flex-col">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $editStudent ? $editStudent['name'] : ''; ?>" required class="border rounded-md p-1.5 bg-gray-500 border-gray-500">
                </div>
                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $editStudent ? $editStudent['email'] : ''; ?>" required class="border rounded-md p-1.5 bg-gray-500 border-gray-500">
                </div>
                <div class="flex flex-col">
                    <label for="course">Course</label>
                    <input type="text" id="course" name="course" value="<?php echo $editStudent ? $editStudent['course'] : ''; ?>" required class="border rounded-md p-1.5 bg-gray-500 border-gray-500">
                </div>
                <div class="flex flex-col">
                    <label for="status">Status</label>
                    <select id="status" name="status" required class="border rounded-md p-1.5 bg-gray-500 border-gray-500">
                        <option value="Pending" <?php echo ($editStudent && $editStudent['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="Active" <?php echo (!$editStudent || $editStudent['status'] == 'Pending') ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?php echo ($editStudent && $editStudent['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <?php if ($editStudent): ?>
                    <div class="flex gap-2 justify-center mt-2">
                        <button type="submit" name="update_student" class="bg-green-700 text-white  px-8 py-2 rounded-sm hover:bg-green-500">Update</button>
                        <a href="student.php" class="bg-gray-400 text-black px-8 py-2 rounded-sm hover:bg-gray-300 border">Cancel</a>
                    </div>
                <?php else: ?>
                    <button type="submit" name="add_student" class="bg-gradient-to-r from-purple-500 to-blue-400 text-white rounded-md px-4 py-2 mt-2">➕  Add Student</button>
                <?php endif; ?>


            </form>
        </section>
        </div>
    <?php else: ?>
        </div>
    <?php endif; ?>

    <div class="bg-gray-700 w-2/3 p-4 rounded-lg">

        <div class="flex justify-between">
            <!-- logo -->
            <div class="flex gap-2">
                <img src="../Images/graduation-hat.png" alt="Student Records" class="w-8 h-8">
                <h1 class="font-bold text-white text-xl">Student Records</h1>
            </div>
            <form action="student.php" method="GET">
                <!-- search -->
                <div class="flex gap-3 items-center">
                    <form action="student.php" method="GET">
                        <input type="text" name="search" placeholder="🔍 Search Students..." value="<?= $_GET['search'] ?? '' ?>" class="rounded-lg shadow-lg p-3 bg-black text-white">
                    </form>
                    <!-- <input type="text" name="search_student" id="" value="<?php echo htmlspecialchars($searchQuery); ?>" class="min-w-0 flex-1 border rounded-lg bg-black text-white px-3 py-2 border-black" placeholder="🔍 Search students..." required> -->
                    <!-- <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-2 font-bold hover:bg-blue-700">Search</button> -->
                    <a href="student.php?add=true" class="bg-gradient-to-r from-purple-500 to-blue-400 rounded-lg px-4 py-2 text-white font-bold hover:opacity-90">+ Add</a>
                </div>
            </form>
        </div>
        <!-- table -->
        <table class="w-full border-t border-purple-900/40 text-left mt-6">
            <thead>
                <tr class="border-b text-md text-gray-500 border-purple-900/40 ">
                    <th class="p-4">NAME</th>
                    <th class="p-4">EMAIL</th>
                    <th class="p-4">COURSE</th>
                    <th class="p-4">STATUS</th>
                    <th class="p-4">ACTIONS</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($allStudents as $student):
                    $statusLower = strtolower($student['status']);
                    $statusClasses = 'border rounded-xl px-2 ';
                    if ($statusLower === 'pending') {
                        $statusClasses .= 'bg-yellow-200 text-yellow-700 border-yellow-900';
                    } elseif ($statusLower === 'inactive') {
                        $statusClasses .= 'bg-red-800/30 text-red-600 border-red-950';
                    } else {
                        $statusClasses .= 'bg-green-800/30 text-green-600 border-green-950';
                    }
                ?>
                    <tr class="border-b text-md text-gray-500 border-purple-900/40 ">
                        <td class="p-4 text-white font-bold"><?php echo $student['name'] ?></td>
                        <td class="p-4"><?php echo $student['email'] ?></td>
                        <td class="p-4"><?php echo $student['course'] ?></td>
                        <td class="p-4"><span class="<?php echo $statusClasses; ?>"><?php echo $student['status'] ?></span></td>
                        <td class="p-4">
                            <div class="flex gap-4">
                                <a href="student.php?edit_id=<?php echo $student['id'] ?>"><img src="../Images/pencil.png" alt="Edit" class="w-4 h-4"></a>
                                <form action="student.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id'] ?>">
                                    <button type="submit" name="delete_student"><img src="../Images/delete (1).png" alt="Delete" class="w-4 h-4"></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3" class="text-gray-500 p-4">Showing <?= $startItem ?>-<?= $endItem ?> of <?= $totalItems ?></td>
                    <td colspan="2" class="p-4">
                        <div class="flex justify-end gap-2">
                            <a href="?page=<?= max(1, $currentPage - 1) ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="rounded-md shadow-md border border-gray-500 px-2 <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                <</a>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="rounded-md shadow-md border px-2 <?= $currentPage == $i ? 'bg-purple-600 text-white' : 'border-gray-500' ?>"><?= $i ?></a>
                                    <?php endfor; ?>
                                    <a href="?page=<?= min($totalPages, $currentPage + 1) ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="rounded-md shadow-md border border-gray-500 px-2 <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>">></a>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>
    


</body>

</html>