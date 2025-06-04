<?php
require_once('app/config/database.php');
require_once('app/models/ReportModel.php');
require_once('app/models/OrderModel.php');
require_once('app/middleware/AuthMiddleware.php');
require_once('app/helpers/SessionHelper.php');

class ReportController
{
    private $db;
    private $reportModel;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->reportModel = new ReportModel($this->db);
        
        // Initialize session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index()
    {
        // Chỉ Admin và Staff mới có thể truy cập báo cáo
        AuthMiddleware::requireStaff();
        
        // Default to current month report
        $currentYear = date('Y');
        $currentMonth = date('m');
        $startDate = date('Y-m-01'); // First day of current month
        $endDate = date('Y-m-t');    // Last day of current month
        
        $summary = $this->reportModel->getTotalRevenueSummary();
        $monthlyData = $this->reportModel->getRevenueByMonth($currentYear);
        $dailyData = $this->reportModel->getRevenueByDateRange($startDate, $endDate);
        $topProducts = $this->reportModel->getTopSellingProducts(5);
        
        include 'app/views/report/index.php';
    }
    
    public function revenue()
    {
        // Chỉ Admin và Staff mới có thể truy cập báo cáo doanh thu
        AuthMiddleware::requireStaff();
        
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        $dailyData = $this->reportModel->getRevenueByDateRange($startDate, $endDate);
        $monthlyData = $this->reportModel->getRevenueByMonth($year);
        $topProducts = $this->reportModel->getTopSellingProducts(10, $startDate, $endDate);
        
        include 'app/views/report/revenue.php';
    }
    
    public function exportExcel()
    {
        // Chỉ Admin và Staff mới có thể xuất báo cáo Excel
        AuthMiddleware::requireStaff();
        
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        
        $data = $this->reportModel->getRevenueByDateRange($startDate, $endDate);
        $topProducts = $this->reportModel->getTopSellingProducts(10, $startDate, $endDate);
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="revenue_report_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Create Excel file
        $output = '
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <body>
            <h2>Báo cáo doanh thu từ ' . $startDate . ' đến ' . $endDate . '</h2>
            <table border="1">
                <tr>
                    <th>Ngày</th>
                    <th>Số đơn hàng</th>
                    <th>Số sản phẩm bán</th>
                    <th>Doanh thu</th>
                </tr>';
        
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalItems = 0;
        
        foreach ($data as $row) {
            $output .= '
                <tr>
                    <td>' . $row->order_date . '</td>
                    <td>' . $row->total_orders . '</td>
                    <td>' . $row->items_sold . '</td>
                    <td>' . number_format($row->revenue, 0, ',', '.') . ' đ</td>
                </tr>';
            
            $totalRevenue += $row->revenue;
            $totalOrders += $row->total_orders;
            $totalItems += $row->items_sold;
        }
        
        $output .= '
                <tr>
                    <th>Tổng cộng</th>
                    <th>' . $totalOrders . '</th>
                    <th>' . $totalItems . '</th>
                    <th>' . number_format($totalRevenue, 0, ',', '.') . ' đ</th>
                </tr>
            </table>
            
            <h2>Top sản phẩm bán chạy</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                    <th>Doanh thu</th>
                </tr>';
        
        foreach ($topProducts as $product) {
            $output .= '
                <tr>
                    <td>' . $product->id . '</td>
                    <td>' . $product->name . '</td>
                    <td>' . $product->total_quantity . '</td>
                    <td>' . number_format($product->total_revenue, 0, ',', '.') . ' đ</td>
                </tr>';
        }
        
        $output .= '
            </table>
        </body>
        </html>';
        
        echo $output;
        exit;
    }
}
?>