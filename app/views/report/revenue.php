<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/webbanhang/Report" class="text-decoration-none">Báo cáo</a></li>
            <li class="breadcrumb-item active">Báo cáo doanh thu </li>
        </ol>
    </nav>

    <h1 class="purple-title mb-4">Báo cáo doanh thu</h1>
    
    <div class="card purple-card mb-4">
        <div class="card-body">
            <form action="/webbanhang/Report/revenue" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t'); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-purple me-2">
                        <i class="bi bi-search"></i> Xem báo cáo
                    </button>
                    <a href="/webbanhang/Report/exportExcel?start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t'); ?>" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Xuất Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card purple-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Biểu đồ doanh thu</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card purple-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top sản phẩm bán chạy</h5>
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
            <h5 class="mb-0">Chi tiết doanh thu theo ngày</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="revenueTable">
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
    // Data for revenue chart
    const dates = [];
    const revenues = [];
    const orders = [];
    
    <?php foreach ($dailyData as $day): ?>
        dates.push('<?php echo date('d/m', strtotime($day->order_date)); ?>');
        revenues.push(<?php echo $day->revenue; ?>);
        orders.push(<?php echo $day->total_orders; ?>);
    <?php endforeach; ?>
    
    // Create revenue chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Doanh thu (đ)',
                    data: revenues,
                    backgroundColor: 'rgba(138, 43, 226, 0.5)',
                    borderColor: 'rgba(138, 43, 226, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Số đơn hàng',
                    data: orders,
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
    
    // Initialize DataTable
    $(document).ready(function() {
        $('#revenueTable').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ hàng",
                info: "Hiển thị _START_ đến _END_ trên _TOTAL_ hàng",
                infoEmpty: "Không có dữ liệu",
                infoFiltered: "(lọc từ _MAX_ hàng)",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                }
            },
            responsive: true,
            order: [[0, 'asc']]
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>