<?php
/******************************
 * DATABASE AUTO-CREATION
 ******************************/
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "general_clients";

// 1. Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->close();

// 2. Connect to new database
$conn = new mysqli($servername, $username, $password, $dbname);

// Create table if not exists
$conn->query("
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_company VARCHAR(255) NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    contact VARCHAR(50),
    address TEXT,
    category VARCHAR(100)
)
");

// Handle Add Client
if (isset($_POST['add_client'])) {
    $company  = $_POST['client_company'];
    $name     = $_POST['client_name'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];
    $category = $_POST['category'];

    $conn->query("INSERT INTO clients (client_company, client_name, contact, address, category)
                  VALUES ('$company', '$name', '$contact', '$address', '$category')");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM clients WHERE id=$id");
}

// Handle Update
if (isset($_POST['update_client'])) {
    $id       = $_POST['id'];
    $company  = $_POST['client_company'];
    $name     = $_POST['client_name'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];
    $category = $_POST['category'];

    $conn->query("UPDATE clients SET 
        client_company='$company',
        client_name='$name',
        contact='$contact',
        address='$address',
        category='$category'
        WHERE id=$id
    ");
}

// Search
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $conn->query("SELECT * FROM clients 
                             WHERE client_company LIKE '%$search%' 
                             OR client_name LIKE '%$search%' 
                             OR category LIKE '%$search%' ");
} else {
    $result = $conn->query("SELECT * FROM clients ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Database</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="text-center mb-4">General Client Database</h2>

    <!-- Search -->
    <form class="d-flex mb-3" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Search clients..." value="<?= $search ?>">
        <button class="btn btn-primary ms-2">Search</button>
    </form>

    <!-- Add Client Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add New Client</div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Client Company</label>
                        <input type="text" name="client_company" class="form-control" required 
                               oninput="document.getElementById('client_name').value = this.value">
                    </div>

                    <div class="col-md-6">
                        <label>Client Name (Auto same as company)</label>
                        <input type="text" id="client_name" name="client_name" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Contact</label>
                        <input type="text" name="contact" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>

                    <div class="col-12">
                        <button name="add_client" class="btn btn-success w-100 mt-2">Add Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Table -->
    <div class="card">
        <div class="card-header bg-dark text-white">Clients List</div>
        <div class="card-body table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Company</th>
                    <th>Client Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Category</th>
                    <th width="180px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['client_company'] ?></td>
                    <td><?= $row['client_name'] ?></td>
                    <td><?= $row['contact'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td>
                        <!-- Update Modal Button -->
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit<?= $row['id'] ?>">
                            Edit
                        </button>

                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete client?')">Delete</a>
                    </td>
                </tr>

                <!-- UPDATE MODAL -->
                <div class="modal fade" id="edit<?= $row['id'] ?>">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5>Edit Client</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                <label>Client Company</label>
                                <input type="text" name="client_company" class="form-control mb-2" value="<?= $row['client_company'] ?>">

                                <label>Client Name</label>
                                <input type="text" name="client_name" class="form-control mb-2" value="<?= $row['client_name'] ?>">

                                <label>Contact</label>
                                <input type="text" name="contact" class="form-control mb-2" value="<?= $row['contact'] ?>">

                                <label>Address</label>
                                <input type="text" name="address" class="form-control mb-2" value="<?= $row['address'] ?>">

                                <label>Category</label>
                                <input type="text" name="category" class="form-control mb-2" value="<?= $row['category'] ?>">
                            </div>

                            <div class="modal-footer">
                                <button name="update_client" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
