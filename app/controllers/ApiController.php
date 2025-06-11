<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/VoucherModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/middleware/AuthMiddleware.php');

class ApiController {
    protected $db;
    protected $productModel;
    protected $categoryModel;
    protected $voucherModel;
    protected $uploadDir;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->voucherModel = new VoucherModel($this->db);
        
        // Set upload directory
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/webbanhang/public/uploads/';

        // Set JSON response headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Handle OPTIONS request for CORS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    // Helper method to send JSON response
    protected function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Helper method to get JSON input data
    protected function getInputData() {
        return json_decode(file_get_contents('php://input'), true);
    }

    // Helper method to check staff authorization
    protected function requireStaff() {
        if (!SessionHelper::isLoggedIn() || (!SessionHelper::isAdmin() && !SessionHelper::isStaff())) {
            $this->sendResponse(['error' => 'Unauthorized access'], 401);
        }
    }

    // GET /api/products
    public function products() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $search = $_GET['search'] ?? null;
                $category_id = $_GET['category_id'] ?? null;
                $products = $this->productModel->getProducts($search, $category_id);
                $this->sendResponse($products);
                break;

            case 'POST':
                // Require staff authorization for creating products
                $this->requireStaff();
                
                $data = $this->getInputData();
                
                // Validate required fields
                if (empty($data['name']) || empty($data['price']) || empty($data['category_id'])) {
                    $this->sendResponse(['error' => 'Missing required fields'], 400);
                }

                // Handle image upload if included
                $image = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = $this->handleImageUpload($_FILES['image']);
                    if (!$image) {
                        $this->sendResponse(['error' => 'Failed to upload image'], 400);
                    }
                }

                $productData = [
                    'name' => $data['name'],
                    'description' => $data['description'] ?? '',
                    'price' => floatval($data['price']),
                    'category_id' => $data['category_id'],
                    'image' => $image
                ];

                $result = $this->productModel->addProduct($productData);
                if ($result) {
                    $this->sendResponse(['message' => 'Product created successfully', 'id' => $result], 201);
                } else {
                    $this->sendResponse(['error' => 'Failed to create product'], 500);
                }
                break;

            default:
                $this->sendResponse(['error' => 'Method not allowed'], 405);
                break;
        }
    }

    // GET|PUT|DELETE /api/products/{id}
    public function product($id = null) {
        if (!$id) {
            $this->sendResponse(['error' => 'Product ID is required'], 400);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $product = $this->productModel->getProductById($id);
                if ($product) {
                    $this->sendResponse($product);
                } else {
                    $this->sendResponse(['error' => 'Product not found'], 404);
                }
                break;

            case 'PUT':
                $this->requireStaff();
                
                $data = $this->getInputData();
                $productData = [];

                // Only update provided fields
                if (isset($data['name'])) $productData['name'] = $data['name'];
                if (isset($data['description'])) $productData['description'] = $data['description'];
                if (isset($data['price'])) $productData['price'] = floatval($data['price']);
                if (isset($data['category_id'])) $productData['category_id'] = $data['category_id'];

                // Handle image upload if included
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image = $this->handleImageUpload($_FILES['image']);
                    if ($image) {
                        $productData['image'] = $image;
                    }
                }

                if ($this->productModel->updateProduct($id, $productData)) {
                    $this->sendResponse(['message' => 'Product updated successfully']);
                } else {
                    $this->sendResponse(['error' => 'Failed to update product'], 500);
                }
                break;

            case 'DELETE':
                $this->requireStaff();
                
                if ($this->productModel->deleteProduct($id)) {
                    $this->sendResponse(['message' => 'Product deleted successfully']);
                } else {
                    $this->sendResponse(['error' => 'Failed to delete product'], 500);
                }
                break;

            default:
                $this->sendResponse(['error' => 'Method not allowed'], 405);
                break;
        }
    }

    // Helper method to handle image upload
    private function handleImageUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $filename = time() . '_' . basename($file['name']);
        $targetPath = $this->uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }

        return false;
    }

    // GET /api/categories
    public function categories() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $this->categoryModel->getCategories();
            $this->sendResponse($categories);
        } else {
            $this->sendResponse(['error' => 'Method not allowed'], 405);
        }
    }

    // GET /api/categories/{id}
    public function category($id = null) {
        if (!$id) {
            $this->sendResponse(['error' => 'Category ID is required'], 400);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $category = $this->categoryModel->getCategoryById($id);
            if ($category) {
                $this->sendResponse($category);
            } else {
                $this->sendResponse(['error' => 'Category not found'], 404);
            }
        } else {
            $this->sendResponse(['error' => 'Method not allowed'], 405);
        }
    }
}
