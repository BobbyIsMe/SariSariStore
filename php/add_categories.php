<?php
// Add categories
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'inventory');

$edit = $_GET['edit'] ?? null;

if (!isset($edit) || !in_array($edit, ['add', 'edit'])) {
    echo json_encode(['status' => 400, 'message' => 'Add or edit only.']);
    exit();
}

$stmt->close();

if ($edit === 'add') {
    $category = $_POST['category'] ?? null;
    $subcategory = $_POST['subcategory'] ?? null;

    if (!isset($category) || !isset($subcategory)) {
        echo json_encode(['status' => 400, 'message' => 'Category and subcategory are required.']);
        exit();
    }

    $stmt = $con->prepare("INSERT INTO Categories (category, subcategory) VALUES (?, ?)");
    $stmt->bind_param('ss', $category, $subcategory);
    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['status' => 200, 'message' => 'Category added successfully.']);
    } else {
        $stmt->close();
        echo json_encode(['status' => 500, 'message' => 'Failed to add category.']);
    }
} else if ($edit === 'edit') {
    $category_list = $_POST['category_list'] ?? null;
    if (!isset($category_list) || empty($category_list)) {
        echo json_encode(['status' => 400, 'message' => 'Category ID is required for editing.']);
        exit();
    }

    $category_cases = "";
    $subcategory_cases = "";
    $ids = [];
    $params = [];
    $values = "";

    foreach ($category_list as $item) {
        if (!isset($item['category_id'], $item['category'], $item['subcategory'])) continue;

        $category_id = (int)$item['category_id'];
        $category = $item['category'];
        $subcategory = $item['subcategory'];

        $category_cases .= "WHEN ? THEN ? ";
        $subcategory_cases .= "WHEN ? THEN ? ";

        $params[] = $category_id;
        $params[] = $category;
        $params[] = $category_id;
        $params[] = $subcategory;

        $values .= "isis";

        $ids[] = $category_id;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $values .= str_repeat('i', count($ids));
    $params = array_merge($params, $ids);

    $stmt = $con->prepare("
    UPDATE Categories
    SET
    category = CASE category_id $category_cases END,
    subcategory = CASE category_id $subcategory_cases END
    WHERE category_id IN ($placeholders)
    ");
    $stmt->bind_param($values, ...$params);
    $stmt->execute();
    $stmt->close();
}
