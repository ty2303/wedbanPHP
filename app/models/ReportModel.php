<?php
class ReportModel
{
    private $conn;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
      public function getRevenueByDateRange($startDate, $endDate)
    {
        $query = "SELECT 
                    DATE(o.created_at) as order_date,
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(od.price * od.quantity) as revenue,
                    COUNT(od.id) as items_sold
                  FROM orders o
                  JOIN order_items od ON o.id = od.order_id
                  WHERE DATE(o.created_at) BETWEEN :start_date AND :end_date
                  GROUP BY DATE(o.created_at)
                  ORDER BY order_date";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
      public function getRevenueByMonth($year)
    {
        $query = "SELECT 
                    MONTH(o.created_at) as month,
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(od.price * od.quantity) as revenue,
                    COUNT(od.id) as items_sold
                  FROM orders o
                  JOIN order_items od ON o.id = od.order_id
                  WHERE YEAR(o.created_at) = :year
                  GROUP BY MONTH(o.created_at)
                  ORDER BY month";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
      public function getTopSellingProducts($limit = 10, $startDate = null, $endDate = null)
    {
        $query = "SELECT 
                    p.id,                    p.name, 
                    p.price,
                    SUM(od.quantity) as total_quantity,
                    SUM(od.price * od.quantity) as total_revenue,
                    COUNT(DISTINCT od.order_id) as order_count
                  FROM order_items od
                  LEFT JOIN products p ON od.product_id = p.id
                  LEFT JOIN orders o ON od.order_id = o.id
                  WHERE 1=1";
        
        if ($startDate && $endDate) {
            $query .= " AND DATE(o.created_at) BETWEEN :start_date AND :end_date";
        }
        
        $query .= " GROUP BY p.id, p.name
                   ORDER BY total_quantity DESC
                   LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
      public function getTotalRevenueSummary()
    {
        $query = "SELECT 
                    COUNT(DISTINCT o.id) as total_orders,
                    SUM(od.price * od.quantity) as total_revenue,
                    AVG(od.price * od.quantity) as avg_order_value,
                    COUNT(od.id) as total_items_sold
                  FROM orders o
                  JOIN order_items od ON o.id = od.order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>