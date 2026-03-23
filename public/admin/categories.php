<?php
require_once '../includes/header.php';
requireAuth(true); // Admin only

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitizeInput($_POST['name']);
    $slug = generateSlug($name);
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    
    if (createCategory($name, $slug, $parent_id, $pdo)) {
        $success = "Category added successfully!";
    } else {
        $error = "Failed to add category.";
    }
}

// Fetch categories with parent names using modular function
$categories = getCategories($pdo, false);
// Re-fetch with join for the table view
$stmt = $pdo->query("SELECT c1.*, c2.name as parent_name FROM categories c1 LEFT JOIN categories c2 ON c1.parent_id = c2.id ORDER BY c1.id DESC");
$categories_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories | Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container nav-wrapper">
            <a href="../index.php" class="logo">ADMIN PANEL</a>
            <nav class="nav-links">
                <a href="categories.php" style="color: var(--primary-color);">Categories</a>
                <a href="product-add.php">Add Product</a>
                <a href="../dashboard.php">User View</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="display: grid; grid-template-columns: 350px 1fr; gap: 2rem; margin-top: 2rem;">
            <!-- Add Category -->
            <aside>
                <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow);">
                    <h2 style="margin-bottom: 1.5rem;">New Category</h2>
                    <?php if ($success): ?><div style="background: #ecfdf5; color: #064e3b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><?php echo $success; ?></div><?php endif; ?>
                    <form action="categories.php" method="POST">
                        <input type="hidden" name="add_category" value="1">
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Name</label>
                            <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Parent Category</label>
                            <select name="parent_id" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                                <option value="">Root (None)</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" style="width: 100%;">Create Category</button>
                    </form>
                </div>
            </aside>

            <!-- Categories List -->
            <section style="background: white; border-radius: 12px; box-shadow: var(--shadow); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="padding: 1rem; text-align: left;">Name</th>
                            <th style="padding: 1rem; text-align: left;">Parent</th>
                            <th style="padding: 1rem; text-align: left;">Slug</th>
                            <th style="padding: 1rem; text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories_list as $cat): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1rem; font-weight: 600;"><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td style="padding: 1rem; color: var(--text-muted);"><?php echo $cat['parent_name'] ? htmlspecialchars($cat['parent_name']) : '-'; ?></td>
                                <td style="padding: 1rem; font-family: monospace; font-size: 0.85rem;"><?php echo htmlspecialchars($cat['slug']); ?></td>
                                <td style="padding: 1rem; text-align: right;">
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; background: #dcfce7; color: #166534;">
                                        <?php echo strtoupper($cat['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
</body>
</html>
