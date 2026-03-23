<?php
require_once '../includes/header.php';
requireAuth(true); // Admin only

$success = '';
$error = '';
$categories = getCategories($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $status = sanitizeInput($_POST['status']);
    
    // Auto-generate SKU
    $sku = !empty($_POST['sku']) ? sanitizeInput($_POST['sku']) : strtoupper(substr($name, 0, 3) . '-' . rand(1000, 9999));
    
    // Multi-image upload
    $image_paths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $image_paths = uploadProductImages($_FILES['images']);
    }
    
    $data = [
        'name' => $name,
        'slug' => generateSlug($name),
        'description' => $description,
        'price' => $price,
        'stock' => $stock,
        'sku' => $sku,
        'category_id' => $category_id,
        'images' => json_encode($image_paths),
        'status' => $status
    ];

    if (createProduct($data, $pdo)) {
        $success = "Product added successfully! SKU: $sku";
    } else {
        $error = "Failed to add product.";
    }
}

$categoryMap = getCategories($pdo, true);
// (Reuse rendering logic from previous version or move to category-functions.php)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container nav-wrapper">
            <a href="../index.php" class="logo">ADMIN PANEL</a>
            <nav class="nav-links">
                <a href="categories.php">Categories</a>
                <a href="product-add.php" style="color: var(--primary-color);">Add Product</a>
                <a href="../dashboard.php">User View</a>
                <a href="../logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="max-width: 800px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow);">
            <h1>Add New Product</h1>
            <?php if ($success): ?><div style="background: #ecfdf5; color: #064e3b; padding: 1rem; border-radius: 8px; margin: 1rem 0;"><?php echo $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 8px; margin: 1rem 0;"><?php echo $error; ?></div><?php endif; ?>

            <form action="product-add.php" method="POST" enctype="multipart/form-data" style="display: grid; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Product Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Description</label>
                    <textarea name="description" required style="width: 100%; height: 100px; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Price ($)</label>
                        <input type="number" name="price" step="0.01" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Stock</label>
                        <input type="number" name="stock" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Category</label>
                        <select name="category_id" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Status</label>
                        <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Images</label>
                    <input type="file" name="images[]" multiple style="width: 100%;">
                </div>
                <button type="submit" style="padding: 1rem; font-size: 1.1rem;">Add Product</button>
            </form>
        </div>
    </main>
</body>
</html>
