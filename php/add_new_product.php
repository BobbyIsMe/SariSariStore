<?php
// Add new product in the database
include_once('db_connect.php');
include_once('admin_status.php');
include_once('check_product_validation.php');
include_once('notify_users.php');
requireAdmin($con, 'inventory');

$edit = $_POST['edit'] ?? null;

if (!isset($edit) || !in_array($edit, ['add', 'edit'])) {
    echo json_encode(['status' => 400, 'message' => 'Only add/edit products.']);
    exit();
}


$item_name = $_POST['item_name'] ?? null;
$category_id = $_POST['category_id'] ?? null;
$brand = $_POST['brand'] ?? null;
$stock_qty = $_POST['stock_qty'] ?? null;
$item_details = $_POST['item_details'] ?? null;
$price = $_POST['price'] ?? null;
$image = $_FILES['image'] ?? null;
$targetDir = "../img/";

if (!isset($category_id, $brand, $stock_qty, $item_details, $price, $item_name) || (!isset($image) && $edit === 'add')) {
    echo json_encode(['status' => 400, 'message' => 'All fields are required.']);
    exit();
}

$file = $_FILES['image'] ?? null;
$image_name = '';

if (isset($image))
    $image_name = time() . "." . strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

$image_old_name = '';

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

if (!isset($item_name)) {
    echo json_encode(['status' => 400, 'message' => 'Item name is required for editing.']);
    exit();
}

$params = 'sis';
$values = [$item_name, $category_id, $brand];

if ($edit === 'add') {
    checkProduct($con, $params, $values);
    $stmt = $con->prepare("
        INSERT INTO Products (image, item_name, category_id, brand, stock_qty, item_details, price) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
    $stmt->bind_param('ssisisd', $image_name, $item_name, $category_id, $brand, $stock_qty, $item_details, $price);
    if ($stmt->execute()) {
        $stmt->close();

        echo json_encode([
            'status' => 200,
            'message' => 'Product added successfully.'
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to add product.']);
    }
} else if ($edit === 'edit') {
    $product_id = $_POST['product_id'] ?? null;
    if (!isset($product_id)) {
        echo json_encode(['status' => 400, 'message' => 'Product ID is required for editing.']);
        exit();
    }

    $stmt = $con->prepare("
    SELECT item_name
    FROM Products
    WHERE product_id = ?
    ");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        if ($result->fetch_assoc()['item_name'] !== $item_name) {
            $checkProduct($con, $params, $values);
        }
    }

    $con->begin_transaction();

    try {
        $stmt = $con->prepare("
        SELECT image, price 
        FROM Products 
        WHERE product_id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if (!$result || $result->num_rows === 0) {
            throw new Exception('Product not found.');
            exit();
        }
        $row = $result->fetch_assoc();
        $image_old_name = $row['image'];
        $old_price = $row['price'];

        if ($old_price != $price) {
            $stmt = $con->prepare("
            UPDATE Carts ca
            JOIN Cart_Items c ON ca.cart_id = c.cart_id
            SET c.subtotal = (item_qty * ?)
            WHERE c.product_id = ? AND NOT ca.status = 'closed'
            ");
            $stmt->bind_param('di', $price, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        if ($image_old_name != $image_name && $file !== null) {
            unlink($targetDir . $image_old_name);
        }

        // $params .= 'i';
        // $values[] = $product_id;
        // $sql .= " AND product_id != ?";
        $img = "";
        $params = 'sisisdi';
        $values = [$item_name, $category_id, $brand, $stock_qty, $item_details, $price, $product_id];

        if ($file !== null) {
            $img = " image = ?, ";
            $params = 'ssisisdi';
            $values = [$image_name, $item_name, $category_id, $brand, $stock_qty, $item_details, $price, $product_id];
        }
        $stmt = $con->prepare("
        UPDATE Products 
        SET " . $img . "item_name = ?, category_id = ?, brand = ?, stock_qty = ?, item_details = ?, price = ?, date_time_restocked = NOW()
        WHERE product_id = ?
        ");
        $stmt->bind_param($params, ...$values);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            throw new Exception('No changes made or product not found.');
        }
        $stmt->close();

        $orders = checkProductValidation($con, " ca.type = 'order' AND ca.status = 'pending'", '', []);
        if ($orders !== null || !empty($orders)) {
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
        }

        $con->commit();
        echo json_encode(['status' => 200, 'message' => 'Product updated.']);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}

if (($image_old_name != $image_name || $image_old_name == '') && $file !== null) {
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedTypes)) {
        echo json_encode(['status' => 400, 'message' => 'Invalid file type.']);
        exit();
    }

    if (move_uploaded_file($file['tmp_name'], $targetDir . $image_name)) {
        echo json_encode(['status' => 200, 'message' => 'File uploaded successfully.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to upload file.']);
    }
}

function checkProduct($con, $params, $values)
{
    $stmt = $con->prepare("
    SELECT item_name 
    FROM Products 
    WHERE item_name = ? AND category_id = ? AND brand = ?");
    $stmt->bind_param($params, ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        echo json_encode(['status' => 400, 'message' => 'Product already exists in this category.']);
        exit();
    }
    $stmt->close();
}
