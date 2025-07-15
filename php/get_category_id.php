<?php 
// Load categories dropdown
include_once("db_connect.php");
include_once("admin_status.php");
requireAdmin($con, 'inventory');

$categories = [];

$stmt = $con->prepare("
SELECT category_id, category, subcategory
FROM Categories");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $categories[$category][] = [
            'category_id' => $row['category_id'],
            'subcategory' => $row['subcategory']
        ];
    }
} else if($result->num_rows === 0)
{
    echo json_encode(['status' => 404, 'message' => 'No categories found.']);
    exit();
}

$myObj = array(
    'categories' => $categories,
    'status' => 200,
    'message' => 'Succesfully retrieved categories.'
);

echo json_encode($myObj);
?>