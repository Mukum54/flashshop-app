<?php
/**
 * Category Functions
 */

function getCategories($pdo, $nested = false) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $all = $stmt->fetchAll();
    if (!$nested) return $all;
    
    $map = [];
    foreach ($all as $cat) {
        $map[$cat['parent_id'] ?? 0][] = $cat;
    }
    return $map;
}

function getCategoryBySlug($slug, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function createCategory($name, $slug, $parent_id, $pdo) {
    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, parent_id, status) VALUES (?, ?, ?, 'active')");
    return $stmt->execute([$name, $slug, $parent_id]);
}

function buildCategoryTree($pdo) {
    return getCategories($pdo, true);
}
?>
