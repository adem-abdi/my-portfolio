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
    
    if ($action === 'add') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $project_date = $_POST['date'] ?? '';
        $github_link = $_POST['github_link'] ?? '';
        $demo_link = $_POST['demo_link'] ?? '';
        $tech_stack = $_POST['tech_stack'] ?? '';
        $image = '';
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/projects/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . $_FILES['image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = 'assets/images/projects/' . $fileName;
            }
        }
        
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, project_date, github_link, demo_link, tech_stack, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $project_date, $github_link, $demo_link, $tech_stack, $image]);
            $message = 'Project added successfully!';
        } catch (PDOException $e) {
            $message = 'Error adding project: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $project_date = $_POST['date'] ?? '';
        $github_link = $_POST['github_link'] ?? '';
        $demo_link = $_POST['demo_link'] ?? '';
        $tech_stack = $_POST['tech_stack'] ?? '';
        
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, project_date = ?, github_link = ?, demo_link = ?, tech_stack = ? WHERE id = ?");
            $stmt->execute([$title, $description, $project_date, $github_link, $demo_link, $tech_stack, $id]);
            $message = 'Project updated successfully!';
        } catch (PDOException $e) {
            $message = 'Error updating project: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'Project deleted successfully!';
        } catch (PDOException $e) {
            $message = 'Error deleting project: ' . $e->getMessage();
        }
    }
}

$projects = getAllProjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #222 url('../assets/images/mypic.png') no-repeat center center fixed; background-size: cover; }
        .projects-container {
            max-width: 1200px;
            margin: 120px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 40px;
            border: 1px solid rgba(173,139,115,0.2);
            position: relative;
            z-index: 2;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 2rem;
            font-weight: 700;
        }
        .add-btn {
            background: linear-gradient(135deg, #AD8B73 0%, #9B7A63 100%);
            color: #FFFBE9;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .add-btn:hover {
            background: linear-gradient(135deg, #9B7A63 0%, #AD8B73 100%);
            transform: translateY(-2px);
        }
        .back-btn {
            background: rgba(173,139,115,0.2);
            color: #AD8B73;
            border: 1px solid rgba(173,139,115,0.3);
            padding: 10px 20px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background: rgba(173,139,115,0.3);
        }
        .message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
        .message.success {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        .message.error {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .project-card {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(173,139,115,0.2);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(173,139,115,0.2);
        }
        .project-title {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .project-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .project-date {
            color: #AD8B73;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .project-links {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .project-link {
            color: #AD8B73;
            text-decoration: none;
            font-size: 0.8rem;
        }
        .project-link:hover {
            text-decoration: underline;
        }
        .project-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .edit-btn, .delete-btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        .edit-btn {
            background: rgba(173,139,115,0.2);
            color: #AD8B73;
        }
        .edit-btn:hover {
            background: rgba(173,139,115,0.3);
        }
        .delete-btn {
            background: rgba(244, 67, 54, 0.2);
            color: #c62828;
        }
        .delete-btn:hover {
            background: rgba(244, 67, 54, 0.3);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: #fff;
            margin: 4% auto;
            padding: 28px 18px 18px 18px;
            border-radius: 16px;
            width: 95%;
            max-width: 420px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 12px 40px rgba(173,139,115,0.18), 0 2px 8px rgba(0,0,0,0.10);
            border: 1px solid #f3e7db;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            animation: modalPop 0.25s cubic-bezier(.4,2,.6,1) both;
            z-index: 10001;
        }
        @keyframes modalPop {
            0% { transform: scale(0.95) translateY(40px); opacity: 0; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        .modal-content h2 {
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 1.5rem;
            color: #AD8B73;
            font-family: 'Sora', sans-serif;
            text-align: center;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .modal-content .form-group label {
            color: #AD8B73;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .modal-content .form-group input,
        .modal-content .form-group textarea {
            background: #f8f6f3;
            border: 1px solid #e2d3c2;
            color: #333;
            font-size: 1rem;
            border-radius: 6px;
            padding: 10px 12px;
            font-family: 'Lora', serif;
            transition: border 0.2s;
        }
        .modal-content .form-group input:focus,
        .modal-content .form-group textarea:focus {
            border: 1.5px solid #AD8B73;
            outline: none;
        }
        .modal-content .submit-btn {
            margin-top: 10px;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .modal-content .close {
            top: 18px;
            right: 24px;
            color: #AD8B73;
            font-size: 2rem;
            transition: color 0.2s;
        }
        .modal-content .close:hover {
            color: #9B7A63;
        }
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #AD8B73;
        }
        .close:hover {
            color: #9B7A63;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-family: 'Sora', sans-serif;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(173,139,115,0.3);
            border-radius: 6px;
            font-family: 'Lora', serif;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .submit-btn {
            background: linear-gradient(135deg, #AD8B73 0%, #9B7A63 100%);
            color: #FFFBE9;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #9B7A63 0%, #AD8B73 100%);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="projects-container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <a href="dashboard.php" class="back-btn">← Dashboard</a>
            <button class="add-btn" onclick="openAddModal()">+ Add Project</button>
        </div>
        <div class="header" style="margin-bottom:10px;">
            <h1>Manage Projects</h1>
        </div>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <div style="margin-bottom:20px;">
            <p style="color:#888;text-align:center;font-size:1rem;">Add, edit, or delete your portfolio projects. Click <b>Edit</b> to update details or <b>Delete</b> to remove a project.</p>
        </div>
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <?php if (!empty($project['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($project['image_path']); ?>" alt="Project Image" style="width:100%;max-height:180px;object-fit:cover;border-radius:8px 8px 0 0;margin-bottom:10px;">
                    <?php endif; ?>
                    <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
                    <div class="project-date"><span style="font-weight:400;color:#9B7A63;">Date:</span> <?php echo htmlspecialchars($project['project_date']); ?></div>
                    <div class="project-description"><?php echo htmlspecialchars($project['description']); ?></div>
                    <div class="project-links">
                        <?php if ($project['github_link']): ?>
                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" class="project-link" target="_blank">GitHub</a>
                        <?php endif; ?>
                        <?php if ($project['demo_link']): ?>
                            <a href="<?php echo htmlspecialchars($project['demo_link']); ?>" class="project-link" target="_blank">Demo</a>
                        <?php endif; ?>
                    </div>
                    <div class="project-actions">
                        <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($project)); ?>)">Edit</button>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this project?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2 style="text-align:center;color:#AD8B73;">Add New Project</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="title">Project Title:</label>
                    <input type="text" id="title" name="title" required placeholder="Enter project title">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required placeholder="Describe your project"></textarea>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="text" id="date" name="date" placeholder="e.g., 2024-07-27" required>
                </div>
                <div class="form-group">
                    <label for="github_link">GitHub Link:</label>
                    <input type="url" id="github_link" name="github_link" placeholder="https://github.com/your-repo">
                </div>
                <div class="form-group">
                    <label for="demo_link">Demo Link:</label>
                    <input type="url" id="demo_link" name="demo_link" placeholder="https://your-demo.com">
                </div>
                <div class="form-group">
                    <label for="tech_stack">Tech Stack:</label>
                    <input type="text" id="tech_stack" name="tech_stack" placeholder="e.g., PHP, MySQL, JavaScript">
                </div>
                <div class="form-group">
                    <label for="image">Project Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="submit-btn">Add Project</button>
            </form>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2 style="text-align:center;color:#AD8B73;">Edit Project</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_title">Project Title:</label>
                    <input type="text" id="edit_title" name="title" required placeholder="Enter project title">
                </div>
                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <textarea id="edit_description" name="description" required placeholder="Describe your project"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_date">Date:</label>
                    <input type="text" id="edit_date" name="date" required placeholder="e.g., 2024-07-27">
                </div>
                <div class="form-group">
                    <label for="edit_github_link">GitHub Link:</label>
                    <input type="url" id="edit_github_link" name="github_link" placeholder="https://github.com/your-repo">
                </div>
                <div class="form-group">
                    <label for="edit_demo_link">Demo Link:</label>
                    <input type="url" id="edit_demo_link" name="demo_link" placeholder="https://your-demo.com">
                </div>
                <div class="form-group">
                    <label for="edit_tech_stack">Tech Stack:</label>
                    <input type="text" id="edit_tech_stack" name="tech_stack" placeholder="e.g., PHP, MySQL, JavaScript">
                </div>
                <button type="submit" class="submit-btn">Update Project</button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function openEditModal(project) {
            document.getElementById('edit_id').value = project.id;
            document.getElementById('edit_title').value = project.title;
            document.getElementById('edit_description').value = project.description;
            document.getElementById('edit_date').value = project.project_date;
            document.getElementById('edit_github_link').value = project.github_link;
            document.getElementById('edit_demo_link').value = project.demo_link;
            document.getElementById('edit_tech_stack').value = project.tech_stack;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>