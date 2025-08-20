<?php
require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report</title>
</head>
<body>
  <h1 class="head">Report</h1>
	<div class="container mt-3">
		<div class="row" style="margin-top: 20px;">
			<!-- ฝั่งซ้าย -->
			<div class="col-6" style="border-right: 1px solid #ccc; padding-right: 10px;">
				<!-- ฟอร์มเลือกวันเริ่มต้นและสิ้นสุด -->
				<form action="function/daily_report_select_date.php" method="POST">
					<h6>รายงานผล ณ ช่วงเวลาที่เลือก </h6>
					<div class="row">
						<div class="col-6">ss
							<div class="mb-3">
								<label for="startDate" class="form-label">วันที่เริ่มต้น:</label>
								<input type="date" id="startDate" name="start_date" class="form-control" required>
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3">
								<label for="endDate" class="form-label">วันที่สิ้นสุด:</label>
								<input type="date" id="endDate" name="end_date" class="form-control" required>
							</div>
						</div>
					</div>
					<center>
						<button type="submit" class="btn btn-primary">ยืนยัน</button>
					</center>
				</form>
			</div>

			<div class="col-6" style="padding-left: 10px;">
				<!-- ปุ่มเรียกใช้ function/daily_report.php -->
				<div class="text-center">
					<h6>รายงานผล 24 ชั่วโมงล่าสุด ณ เวลาปัจจุบัน</h6>
					<a href="function/daily_report.php" class="btn btn-success">Download Report</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>