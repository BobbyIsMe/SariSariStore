<?php
// Check product validation
include_once('db_connect.php');

function checkProductValidation($con, $filter, $values, $params)
{

    $stmt = $con->prepare("
    SELECT ca.cart_id, p.product_id, c.variation_id, (p.stock_qty - c.item_qty) AS quantity
    FROM Carts ca
    JOIN Cart_Items c ON ca.cart_id = c.cart_id
    JOIN Products p ON c.product_id = p.product_id
    JOIN Variations v ON c.variation_id = v.variation_id
    WHERE " . $filter);
    if(!empty($params))
    $stmt->bind_param($values, $params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $carts = [];
        
        while ($row = $result->fetch_assoc()) {
            
            $carts[$row['cart_id']][$row['product_id']] = [
                'variation_id' => $row['variation_id'],
                'quantity' => $row['quantity']
            ];
            // if ($row['product_id'] == null) {
            //     json_encode(['status' => 404, 'message' => 'Product is unavailable.']);
            //     exit();
            // }
            // if ($row['variation_id'] == null) {
            //     json_encode(['status' => 404, 'message' => 'Variation is unavailable.']);
            //     exit();
            // }
            // if ($row['quantity'] <= 0) {
            //     json_encode(['status' => 400, 'message' => 'Insufficient stock for product ID: ' . $row['product_id']]);
            //     exit();
            // }
        }
        $stmt->close();
        return $carts;
    } else {
        $stmt->close();
        return null;
    }
}
