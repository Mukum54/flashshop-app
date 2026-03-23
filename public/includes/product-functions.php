<?php
/**
 * Product Management Functions
 */

function getProducts($pdo, $limit = 10, $category_id = null, $search = '') {
    $query = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
    $params = [];
    
    if ($category_id) {
        $query .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    if ($search) {
        $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $query .= " LIMIT " . (int)$limit;
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getProductById($id, $pdo) {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createProduct($data, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, stock, sku, category_id, images, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'], 
            $data['slug'], 
            $data['description'], 
            $data['price'], 
            $data['stock'], 
            $data['sku'], 
            $data['category_id'], 
            $data['images'], 
            $data['status']
        ]);
    } catch (PDOException $e) {
        error_log("Create Product Error: " . $e->getMessage());
        return false;
    }
}

function uploadProductImages($files) {
    $image_paths = [];
    $upload_dir = __DIR__ . '/../uploads/products/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    foreach ($files['tmp_name'] as $key => $tmp_name) {
        $filename = time() . '_' . $files['name'][$key];
        if (move_uploaded_file($tmp_name, $upload_dir . $filename)) {
            $image_paths[] = 'uploads/products/' . $filename;
        }
    }
    return $image_paths;
}
?>
