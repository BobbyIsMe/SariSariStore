<?php
// Add/edit variations to a product
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'inventory');

$edit = $_GET['edit'] ?? null;

if (!isset($edit) || !in_array($edit, ['add', 'edit'])) {
    echo json_encode(['status' => 400, 'message' => 'Only add/edit variations.']);
    exit();
}

if ($edit === 'add') {
    $product_id = $_POST['product_id'] ?? null;
    $variation_name = $_POST['variation_name'] ?? null;

    if (!isset($product_id) || !isset($variation_name)) {
        echo json_encode(['status' => 400, 'message' => 'Product ID and variation name are required.']);
        exit();
    }

    // $stmt = $con->prepare("
    //     SELECT product_id 
    //     FROM Products 
    //     WHERE product_id = ?");
    // $stmt->bind_param('i', $product_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $stmt->close();
    // if (!$result || $result->num_rows === 0) {
    //     echo json_encode(['status' => 404, 'message' => 'Product not found.']);
    //     exit();
    // }

    $stmt = $con->prepare("INSERT INTO Variations (product_id, variation_name) VALUES (?, ?)");
    $stmt->bind_param('is', $product_id, $variation_name);
    $stmt->execute();
    if($stmt->affected_rows === 0)
    {
        echo json_encode(['status' => 404, 'message' => 'Product not found.']);
        $stmt->close();
        exit();
    }
    $stmt->close();

} else if ($edit === 'edit') {
    $variation_list = $_POST['variation_list'] ?? null;

    if (!is_array($variation_list) || empty($variation_list)) {
        echo json_encode(['status' => 400, 'message' => 'Variation list is required for editing.']);
        exit();
    }

    $cases = "";
    $params = [];
    $types = "";
    $ids = [];

    foreach ($variation_list as $item) {
        if (!isset($item['variation_id'], $item['variation_name'])) continue;

        $id = (int) $item['variation_id'];
        $name = $item['variation_name'];

        $cases .= "WHEN ? THEN ? ";
        $params[] = $id;
        $params[] = $name;
        $types .= "is";

        $ids[] = $id;
    }

    $in_placeholders = implode(',', array_fill(0, count($ids), '?'));
    $params = array_merge($params, $ids);
    $types .= str_repeat('i', count($ids));

    $stmt = $con->prepare("
    UPDATE Variations
    SET variation_name = CASE variation_id
    $cases
    END
    WHERE variation_id IN ($in_placeholders)
    ");
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['status' => 200, 'message' => 'Variations updated successfully.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to update variations.']);
    }

    $stmt->close();
}
