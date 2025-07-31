<?php
include 'navbar.php';

// รวมยอดแจ้งซ่อมทั้งหมดและแยกตามแผนกและสถานะ
$statusCounts = [
  'A' => ['แจ้งซ่อม' => 0, 'กำลังดำเนินการ' => 0, 'เสร็จสิ้น' => 0],
  'B' => ['แจ้งซ่อม' => 0, 'กำลังดำเนินการ' => 0, 'เสร็จสิ้น' => 0],
  'C' => ['แจ้งซ่อม' => 0, 'กำลังดำเนินการ' => 0, 'เสร็จสิ้น' => 0]
];

$stmt = $pdo->query("SELECT target, status, COUNT(*) AS total FROM repairs WHERE deleted = 0 GROUP BY target, status");
foreach ($stmt->fetchAll() as $row) {
  $t = $row['target'];
  $s = $row['status'];
  $c = $row['total'];
  if (isset($statusCounts[$t][$s])) {
    $statusCounts[$t][$s] = $c;
  }
}

// ดึงรายการล่าสุด 10 รายการ
$recentStmt = $pdo->query("SELECT * FROM repairs WHERE deleted = 0 ORDER BY created_at DESC LIMIT 10");
$recentRepairs = $recentStmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  
  <div style="margin: 0 60px">
    <h1 class="head">Dashboard</h1>
    <div class="dashboard-card-box">
      <div class="dashboard-card it">
        <h3>IT (A)</h3>
        <p>แจ้งซ่อม: <?= $statusCounts['A']['แจ้งซ่อม'] ?></p>
        <p>กำลังดำเนินการ: <?= $statusCounts['A']['กำลังดำเนินการ'] ?></p>
        <p>เสร็จสิ้น: <?= $statusCounts['A']['เสร็จสิ้น'] ?></p>
      </div>
      <div class="dashboard-card mt">
        <h3>MT (B)</h3>
        <p>แจ้งซ่อม: <?= $statusCounts['B']['แจ้งซ่อม'] ?></p>
        <p>กำลังดำเนินการ: <?= $statusCounts['B']['กำลังดำเนินการ'] ?></p>
        <p>เสร็จสิ้น: <?= $statusCounts['B']['เสร็จสิ้น'] ?></p>
      </div>
      <div class="dashboard-card" style="background: gray">
        <h3>อื่น ๆ (C)</h3>
        <p>แจ้งซ่อม: <?= $statusCounts['C']['แจ้งซ่อม'] ?></p>
        <p>กำลังดำเนินการ: <?= $statusCounts['C']['กำลังดำเนินการ'] ?></p>
        <p>เสร็จสิ้น: <?= $statusCounts['C']['เสร็จสิ้น'] ?></p>
      </div>
    </div>

    <div class="recent-graph-wrapper">
      <div class="recent-table">
        <h2>รายการแจ้งซ่อมล่าสุด</h2>
        <table>
          <tr>
            <th>วันที่</th>
            <th>เคส</th>
            <th>สาขา</th>
            <th>เรื่อง</th>
            <th>สถานะ</th>
          </tr>
          <?php foreach ($recentRepairs as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['created_at']) ?></td>
              <td><?= htmlspecialchars($r['case_number']) ?></td>
              <td><?= htmlspecialchars($r['branch']) ?></td>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <td><?= htmlspecialchars($r['status']) ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>

      <div class="graph-canvas">
        <h2>สถิติการแจ้งซ่อม</h2>
        <canvas id="statusChart" width="600" height="600"></canvas>
      </div>
    </div>

    <script>
      const ctx = document.getElementById('statusChart').getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            'IT - แจ้งซ่อม', 'IT - กำลังดำเนินการ', 'IT - เสร็จสิ้น',
            'MT - แจ้งซ่อม', 'MT - กำลังดำเนินการ', 'MT - เสร็จสิ้น',
            'อื่นๆ - แจ้งซ่อม', 'อื่นๆ - กำลังดำเนินการ', 'อื่นๆ - เสร็จสิ้น'
          ],
          datasets: [{
            data: [
              <?= $statusCounts['A']['แจ้งซ่อม'] ?>,
              <?= $statusCounts['A']['กำลังดำเนินการ'] ?>,
              <?= $statusCounts['A']['เสร็จสิ้น'] ?>,
              <?= $statusCounts['B']['แจ้งซ่อม'] ?>,
              <?= $statusCounts['B']['กำลังดำเนินการ'] ?>,
              <?= $statusCounts['B']['เสร็จสิ้น'] ?>,
              <?= $statusCounts['C']['แจ้งซ่อม'] ?>,
              <?= $statusCounts['C']['กำลังดำเนินการ'] ?>,
              <?= $statusCounts['C']['เสร็จสิ้น'] ?>
            ],
            backgroundColor: [
              '#D64545', '#F08080', '#FAD4D4',
              '#3A6EA5', '#A0C4FF', '#c3d4ee',
              '#6B7280', '#D1D5DB', '#F3F4F6'
            ]
          }]
        },
        options: {
          responsive: false,
          plugins: {
            legend: {
              position: 'right'
            }
          }
        }
      });
    </script>
  </div>
</body>

</html>