<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <h1 class="purple-title mb-4 text-center">Báo cáo doanh thu web TITI</h1>
    
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card purple-card">
                <div class="card-body">
                    <form action="/webbanhang/Report/revenue" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo date('Y-m-t'); ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-purple me-2">
                                <i class="bi bi-search"></i> Xem báo cáo
                            </button>
                            <a href="/webbanhang/Report/exportExcel" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Xuất Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card purple-card text-center h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Tổng đơn hàng</h6>
                    <h2 class="mb-3"><?php echo $summary->total_orders ?? 0; ?></h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-cart-check"></i> Đơn hàng
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card purple-card text-center h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Tổng doanh thu</h6>
                    <h2 class="mb-3"><?php echo number_format($summary->total_revenue ?? 0, 0, ',', '.'); ?> đ</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-currency-exchange"></i> Doanh thu
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card purple-card text-center h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Giá trị trung bình đơn hàng</h6>
                    <h2 class="mb-3"><?php echo number_format($summary->avg_order_value ?? 0, 0, ',', '.'); ?> đ</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-receipt"></i> Trung bình
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card purple-card text-center h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Sản phẩm đã bán</h6>
                    <h2 class="mb-3"><?php echo $summary->total_items_sold ?? 0; ?></h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-box-seam"></i> Sản phẩm
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card purple-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Doanh thu theo tháng <?php echo date('Y'); ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card purple-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top 5 sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đã bán</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td>
                                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </td>
                                    <td class="text-end"><?php echo $product->total_quantity; ?></td>
                                    <td class="text-end"><?php echo number_format($product->total_revenue, 0, ',', '.'); ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card purple-card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Doanh thu theo ngày (tháng <?php echo date('m/Y'); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th class="text-center">Số đơn hàng</th>
                            <th class="text-center">Số sản phẩm</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dailyData)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $totalRevenue = 0;
                            $totalOrders = 0;
                            $totalItems = 0;
                            ?>
                            <?php foreach ($dailyData as $day): ?>
                                <?php 
                                $totalRevenue += $day->revenue;
                                $totalOrders += $day->total_orders;
                                $totalItems += $day->items_sold;
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($day->order_date)); ?></td>
                                    <td class="text-center"><?php echo $day->total_orders; ?></td>
                                    <td class="text-center"><?php echo $day->items_sold; ?></td>
                                    <td class="text-end"><?php echo number_format($day->revenue, 0, ',', '.'); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-info fw-bold">
                                <td>Tổng cộng</td>
                                <td class="text-center"><?php echo $totalOrders; ?></td>
                                <td class="text-center"><?php echo $totalItems; ?></td>
                                <td class="text-end"><?php echo number_format($totalRevenue, 0, ',', '.'); ?> đ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for monthly chart
    const months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
    const revenueData = Array(12).fill(0);
    const ordersData = Array(12).fill(0);
    
    <?php foreach ($monthlyData as $data): ?>
        revenueData[<?php echo $data->month - 1; ?>] = <?php echo $data->revenue; ?>;
        ordersData[<?php echo $data->month - 1; ?>] = <?php echo $data->total_orders; ?>;
    <?php endforeach; ?>
    
    // Create monthly revenue chart
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Doanh thu (đ)',
                    data: revenueData,
                    backgroundColor: 'rgba(138, 43, 226, 0.5)',
                    borderColor: 'rgba(138, 43, 226, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Số đơn hàng',
                    data: ordersData,
                    type: 'line',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Doanh thu (đ)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Số đơn hàng'
                    }
                }
            }
        }
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>