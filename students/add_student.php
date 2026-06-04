<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-400 min-h-screen px-4 py-4">
    <div class="flex flex-col gap-6 justify-center">
        <!-- Form -->
        <section class="w-full ">
            <form action="add_student.php" method="POST" class="w-full bg-white rounded-md shadow-lg p-6 flex flex-col gap-4">
                <h1 class="font-bold text-2xl border-b">Add New Student</h1>

                <div class="flex flex-col">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="text">Course</label>
                    <input type="course" id="course" name="course" value="" required class="border rounded-md p-1.5">
                </div>
                <div class="flex flex-col">
                    <label for="status">Status</label>
                    <select id="status" name="role" required class="border rounded-md p-1.5">
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" name="add_user" class="bg-blue-600 text-white rounded-md px-4 py-2 mt-2">+Save to Database</button>
                <div class="flex gap-2 justify-center mt-2">
                    <button type="submit" name="add_user" class="bg-green-700 text-white  px-8 py-2 rounded-sm hover:bg-green-500">Update</button>
                    <a href="student.php" class="bg-gray-400 text-black px-8 py-2 rounded-sm hover:bg-gray-300 border">Cancel</a>
                </div>


            </form>
        </section>
    </div>
</body>

</html>