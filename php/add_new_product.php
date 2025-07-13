<?php
// Add new product in the database
include_once('db_connect.php');
include_once('admin_status.php');
include_once('check_product_validation.php');
include_once('notify_users.php');
requireAdmin($con, 'inventory');

$edit = $_GET['edit'] ?? null;

if (!isset($edit) || !in_array($edit, ['add', 'edit'])) {
    echo json_encode(['status' => 400, 'message' => 'Invalid product ID.']);
    exit();
}

$category_id = $_POST['category_id'] ?? null;
$brand = $_POST['brand'] ?? null;
$stock_qty = $_POST['stock_qty'] ?? null;
$item_details = $_POST['item_details'] ?? null;
$price = $_POST['price'] ?? null;

if (!isset($category_id, $brand, $stock_qty, $item_details, $price)) {
    echo json_encode(['status' => 400, 'message' => 'All fields are required.']);
    exit();
}

if (!is_numeric($stock_qty) || $price <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Price must be greater than zero.']);
    exit();
}

if (!is_numeric($stock_qty) || $stock_qty < 0) {
    echo json_encode(['status' => 400, 'message' => 'Stock quantity must be a non-negative number.']);
    exit();
}

$stmt = $con->prepare("SELECT 1 FROM Categories WHERE category_id = ?");
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Category not found.']);
    exit;
}


$item_name = $_POST['item_name'] ?? null;
if (!isset($item_name)) {
    echo json_encode(['status' => 400, 'message' => 'Item name is required for editing.']);
    exit();
}

$params = 'sis';
$values = [$item_name, $category_id, $brand];

$sql = "
    SELECT item_name 
    FROM Products 
    WHERE item_name = ? AND category_id = ? AND brand = ?";

if ($edit === 'edit') {
    $product_id = $_POST['product_id'] ?? null;
    if (!isset($product_id)) {
        echo json_encode(['status' => 400, 'message' => 'Product ID is required for editing.']);
        exit();
    }
    $stmt = $con->prepare("
        SELECT product_id 
        FROM Products 
        WHERE product_id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
        echo json_encode(['status' => 404, 'message' => 'Product not found.']);
        exit;
    }
    $stmt->close();

    $params .= 'i';
    $values[] = $product_id;
    $sql .= " AND product_id != ?";
}

$stmt = $con->prepare($sql);
$stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    echo json_encode(['status' => 400, 'message' => 'Product already exists in this category.']);
    exit();
}
$stmt->close();

if ($edit === 'add') {
    $stmt = $con->prepare("
        INSERT INTO Products (item_name, category_id, brand, stock_qty, item_details, price) 
        VALUES (?, ?, ?, ?, ?, ?)
        ");
    $stmt->bind_param('sissds', $item_name, $category_id, $brand, $stock_qty, $item_details, $price);
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;
        $stmt->close();

        echo json_encode([
            'status' => 200,
            'message' => 'Product added successfully.',
            'product_id' => $product_id
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to add product.']);
    }
} else if ($edit === 'edit') {
    $con->begin_transaction();

    try {
        $stmt = $con->prepare("
        UPDATE Products 
        SET item_name = ?, category_id = ?, brand = ?, stock_qty = ?, item_details = ?, price = ? 
        WHERE product_id = ?
        ");
        $stmt->bind_param('sisisdi', $item_name, $category_id, $brand, $stock_qty, $item_details, $price, $product_id);
        $stmt->execute();
        $stmt->close();

        $orders = checkProductValidation($con, " ca.type = 'order' AND ca.status = 'pending'", '', []);
        $cart_id_list = [];

        foreach ($orders as $cart_id_invalid => $items) {
            foreach ($items as $product_id => $item) {
                if ($item['quantity'] <= 0) {
                    $cart_id_list[$cart_id_invalid] = true;
                    break;
                }
            }
        }

        $cart_id_list = array_keys($cart_id_list);

        if (!empty($cart_id_list)) {
            $placeholders = implode(',', array_fill(0, count($cart_id_list), '?'));
            $stmt = $con->prepare("
            UPDATE Carts 
            SET status = 'rejected' 
            WHERE cart_id IN ($placeholders) AND type = 'order'
        ");
            $stmt->bind_param(str_repeat('i', count($cart_id_list)), ...$cart_id_list);
            $stmt->execute();
            $stmt->close();

            $date_time = date('Y-m-d H:i:s');
            $notifications = [];

            foreach ($cart_id_list as $id) {
                $notifications[] = [
                    'cart_id' => $id,
                    'message' => 'Your reservation has been rejected due to invalid items.',
                    'date_time_created' => $date_time,
                    'status' => 'rejected'
                ];
            }

            notifyUsers($con, $notifications);
        }

        $con->commit();
        echo json_encode(['status' => 200, 'message' => 'Product updated.']);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}
