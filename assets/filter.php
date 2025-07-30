<style>
  select {
    display: inline-block;
    width: auto;
    /* หรือระบุเป็น 120px, 150px ตามต้องการ */
    min-width: 120px;
    padding: 8px 12px;
    border: 2px solid #000;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
    background: #fff;
    transition: border 0.3s;
  }

  select:focus {
    border-color: #333;
  }

  .search {
    display: inline-block;
    width: auto;
    /* หรือระบุเป็น 120px, 150px ตามต้องการ */
    min-width: 120px;
    padding: 8px 12px;
    border: 2px solid #000;
    border-radius: 8px;
    font-size: 16px;
    outline: none;
    background: #fff;
    transition: border 0.3s;
  }

  /* ปรับสไตล์ปุ่มค้นหา */
  .search-btn {
    background: white;
    border: none;
    border-radius: 50%;
    /* วงกลม */
    width: 40px;
    height: 40px;
    display: flex;
    /* จัดให้อยู่กลาง */
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: background 0.3s, color 0.3s;

  }

  .search-btn:hover {
    background: #000;
    color: #fff;
  }
  
  .search-btn i {
    color: black;
    /* สีไอคอน */
    font-size: 16px;
    /* ขนาดไอคอน */
  }
  .search-btn i:hover {
    color: white;
  }


  .search-container {
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<center>
  <form method="get" style="margin-bottom: 10px;">
    <select name="status">
      <option value="">--สถานะ--</option>
      <option value="แจ้งซ่อม" <?= isset($_GET['status']) && $_GET['status'] == 'แจ้งซ่อม' ? 'selected' : '' ?>>แจ้งซ่อม</option>
      <option value="กำลังดำเนินการ" <?= isset($_GET['status']) && $_GET['status'] == 'กำลังดำเนินการ' ? 'selected' : '' ?>>กำลังดำเนินการ</option>
      <option value="เสร็จสิ้น" <?= isset($_GET['status']) && $_GET['status'] == 'เสร็จสิ้น' ? 'selected' : '' ?>>เสร็จสิ้น</option>
    </select>

    <select name="branch_prefix">
      <option value="">--สาขา--</option>
      <option value="suki" <?= isset($_GET['branch_prefix']) && $_GET['branch_prefix'] == 'suki' ? 'selected' : '' ?>>suki</option>
      <option value="bbq" <?= isset($_GET['branch_prefix']) && $_GET['branch_prefix'] == 'bbq' ? 'selected' : '' ?>>bbq</option>
    </select>

    <div class="search-container">
      <input class="search" placeholder="ค้นหา" type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
    </div>


  </form>
</center>