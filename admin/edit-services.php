<?php
session_start();
require_once '../includes/database.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
$message = '';
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pdo = getDBConnection();
    if ($action === 'add') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        try {
            $stmt = $pdo->prepare("INSERT INTO services (title, description) VALUES (?, ?)");
            $stmt->execute([$title, $description]);
            $message = 'Service added successfully!';
        } catch (PDOException $e) {
            $message = 'Error adding service: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        try {
            $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $id]);
            $message = 'Service updated successfully!';
        } catch (PDOException $e) {
            $message = 'Error updating service: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        try {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Service deleted successfully!';
        } catch (PDOException $e) {
            $message = 'Error deleting service: ' . $e->getMessage();
        }
    }
}
// Fetch all services
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC, id DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
    $message = 'Error fetching services: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Services - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #222 url('../assets/images/mypic.png') no-repeat center center fixed; background-size: cover; }
        .edit-container {
            max-width: 800px;
            margin: 120px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 40px;
            border: 1px solid rgba(173,139,115,0.2);
            position: relative;
            z-index: 2;
        }
        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .edit-header h1 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .add-btn {
            background-color: #AD8B73;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-bottom: 20px;
            display: inline-block;
            text-align: center;
        }
        .service-list {
            margin-top: 20px;
        }
        .service-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .service-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
        }
        .service-actions button {
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(173,139,115,0.08);
            transition: background 0.2s, color 0.2s, transform 0.15s;
            margin-left: 10px;
        }
        .service-actions button.edit {
            background: #b89b85;
            color: #fffbe9;
        }
        .service-actions button.edit:hover {
            background: #ad8b73;
            color: #fff;
            transform: translateY(-2px) scale(1.04);
        }
        .service-actions button.delete {
            background: #f44336;
            color: #fff;
        }
        .service-actions button.delete:hover {
            background: #d32f2f;
            color: #fffbe9;
            transform: translateY(-2px) scale(1.04);
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        .submit-btn {
            background-color: #AD8B73;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <a href="dashboard.php" class="back-btn" style="background:rgba(173,139,115,0.2);color:#AD8B73;border:1px solid rgba(173,139,115,0.3);padding:10px 20px;border-radius:6px;font-family:'Sora',sans-serif;font-weight:600;cursor:pointer;transition:all 0.3s;text-decoration:none;display:inline-block;">← Dashboard</a>
            <button class="add-btn" onclick="openAddModal()">+ Add Service</button>
        </div>
        <div class="edit-header">
            <h1>Edit Services</h1>
            <p>Manage your service offerings and descriptions below.</p>
        </div>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <div class="service-list">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="service-item">
                        <div>
                            <div class="service-title"><?php echo htmlspecialchars($service['title']); ?></div>
                            <div style="color:#666;font-size:0.95rem;">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </div>
                        </div>
                        <div class="service-actions">
                            <button class="edit" onclick='openEditModal(<?php echo json_encode($service); ?>)'>Edit</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this service?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center;color:#888;font-size:1.1rem;">No services found. Add your first service!</div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Add Service Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2 style="text-align:center;color:#AD8B73;">Add New Service</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="title">Service Title:</label>
                    <input type="text" id="title" name="title" required placeholder="Enter service title">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required placeholder="Describe your service"></textarea>
                </div>
                <button type="submit" class="submit-btn">Add Service</button>
            </form>
        </div>
    </div>
    <!-- Edit Service Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2 style="text-align:center;color:#AD8B73;">Edit Service</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_title">Service Title:</label>
                    <input type="text" id="edit_title" name="title" required placeholder="Enter service title">
                </div>
                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <textarea id="edit_description" name="description" required placeholder="Describe your service"></textarea>
                </div>
                <button type="submit" class="submit-btn">Update Service</button>
            </form>
        </div>
    </div>
    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        function openEditModal(service) {
            document.getElementById('edit_id').value = service.id;
            document.getElementById('edit_title').value = service.title;
            document.getElementById('edit_description').value = service.description;
            document.getElementById('editModal').style.display = 'block';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
