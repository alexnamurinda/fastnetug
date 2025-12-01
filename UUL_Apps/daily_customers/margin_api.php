<?php
header('Content-Type: application/json');
require_once 'database.php';

$conn = getDbConnection();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_categories':
            getCategories($conn);
            break;

        case 'search_products':
            searchProducts($conn);
            break;

        case 'get_product_details':
            getProductDetails($conn);
            break;

        case 'add_product':
            addProduct($conn);
            break;

        case 'update_selling_price':
            updateSellingPrice($conn);
            break;

        case 'verify_passcode':
            verifyPasscode();
            break;

        case 'get_all_products':
            getAllProducts($conn);
            break;

        case 'delete_product':
            deleteProduct($conn);
            break;

        case 'update_category':
            updateCategory($conn);
            break;

        case 'add_category':
            addCategory($conn);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();

function getCategories($conn)
{
    $sql = "SELECT * FROM product_categories ORDER BY category_name";
    $result = $conn->query($sql);

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode(['success' => true, 'categories' => $categories]);
}

function searchProducts($conn)
{
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';

    $sql = "SELECT id, product_name, category, packing_quantity, selling_price, stock_available 
            FROM products WHERE 1=1";

    $params = [];
    $types = '';

    if (!empty($search)) {
        $sql .= " AND product_name LIKE ?";
        $params[] = "%$search%";
        $types .= 's';
    }

    if (!empty($category)) {
        $sql .= " AND category = ?";
        $params[] = $category;
        $types .= 's';
    }

    $sql .= " ORDER BY product_name LIMIT 20";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode(['success' => true, 'products' => $products]);
}

function getProductDetails($conn)
{
    $productId = $_GET['product_id'] ?? 0;

    $sql = "SELECT id, product_name, category, packing_quantity, selling_price, cost_price, 
                   stock_available, price_last_modified
            FROM products WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Calculate margin
        $sellingPrice = floatval($row['selling_price']);
        $costPrice = floatval($row['cost_price']);

        if ($sellingPrice > 0) {
            $margin = (($sellingPrice - $costPrice) / $sellingPrice) * 100;
        } else {
            $margin = 0;
        }

        $row['margin'] = round($margin, 2);
        // Don't send cost_price to frontend for security
        unset($row['cost_price']);

        echo json_encode(['success' => true, 'product' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
}

function addProduct($conn)
{
    $productName = $_POST['product_name'] ?? '';
    $category = $_POST['category'] ?? 'Art Paper';
    $packingQuantity = $_POST['packing_quantity'] ?? '';
    $sellingPrice = floatval($_POST['selling_price'] ?? 0);
    $costPrice = floatval($_POST['cost_price'] ?? 0);
    $stockAvailable = intval($_POST['stock_available'] ?? 0);

    if (empty($productName)) {
        throw new Exception('Product name is required');
    }

    $sql = "INSERT INTO products (product_name, category, packing_quantity, selling_price, cost_price, stock_available)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssddi', $productName, $category, $packingQuantity, $sellingPrice, $costPrice, $stockAvailable);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Product added successfully',
            'product_id' => $conn->insert_id
        ]);
    } else {
        throw new Exception('Failed to add product: ' . $stmt->error);
    }
}

function updateSellingPrice($conn)
{
    $productId = $_POST['product_id'] ?? 0;
    $newSellingPrice = floatval($_POST['selling_price'] ?? 0);
    $passcode = $_POST['passcode'] ?? '';

    // Verify passcode
    if ($passcode !== '789') {
        echo json_encode(['success' => false, 'message' => 'Invalid passcode']);
        return;
    }

    // Get current product details
    $sql = "SELECT selling_price, cost_price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $oldSellingPrice = floatval($row['selling_price']);
        $costPrice = floatval($row['cost_price']);

        // Calculate old and new margins
        $oldMargin = $oldSellingPrice > 0 ? (($oldSellingPrice - $costPrice) / $oldSellingPrice) * 100 : 0;
        $newMargin = $newSellingPrice > 0 ? (($newSellingPrice - $costPrice) / $newSellingPrice) * 100 : 0;

        // Update selling price
        $updateSql = "UPDATE products SET selling_price = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('di', $newSellingPrice, $productId);
        $updateStmt->execute();

        // Log in margin history
        $historySql = "INSERT INTO margin_history (product_id, old_selling_price, new_selling_price, old_margin, new_margin)
                       VALUES (?, ?, ?, ?, ?)";
        $historyStmt = $conn->prepare($historySql);
        $historyStmt->bind_param('idddd', $productId, $oldSellingPrice, $newSellingPrice, $oldMargin, $newMargin);
        $historyStmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Selling price updated successfully',
            'new_margin' => round($newMargin, 2)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
}

function verifyPasscode()
{
    $passcode = $_POST['passcode'] ?? '';

    if ($passcode === '789') {
        echo json_encode(['success' => true, 'message' => 'Passcode verified']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid passcode']);
    }
}

function getAllProducts($conn)
{
    $category = $_GET['category'] ?? '';

    $sql = "SELECT id, product_name, category, packing_quantity, selling_price, stock_available, price_last_modified
            FROM products";

    if (!empty($category)) {
        $sql .= " WHERE category = ?";
    }

    $sql .= " ORDER BY product_name";

    if (!empty($category)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode(['success' => true, 'products' => $products]);
}

function deleteProduct($conn)
{
    $productId = $_POST['product_id'] ?? 0;

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        throw new Exception('Failed to delete product');
    }
}

function updateCategory($conn)
{
    $productId = $_POST['product_id'] ?? 0;
    $category = $_POST['category'] ?? '';

    $sql = "UPDATE products SET category = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $category, $productId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        throw new Exception('Failed to update category');
    }
}

function addCategory($conn)
{
    $categoryName = $_POST['category_name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($categoryName)) {
        throw new Exception('Category name is required');
    }

    $sql = "INSERT INTO product_categories (category_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $categoryName, $description);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Category added successfully',
            'category_id' => $conn->insert_id
        ]);
    } else {
        throw new Exception('Failed to add category: ' . $stmt->error);
    }
}
?>