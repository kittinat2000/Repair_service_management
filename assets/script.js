// ฟังก็ชั่นกดดูรูป
function showImage(src) {
  document.getElementById('lightbox-img').src = src;
  document.getElementById('lightbox').style.display = 'flex';
}
// ฟังก์ชั่นกดปิดรูป
function closeLightbox() {
  document.getElementById('lightbox').style.display = 'none';
}


let actionType = null;
let selectedId = null;
let toTarget = null;

function showConfirm(id, action, to = null) {
  actionType = action;
  selectedId = id;
  toTarget = to;

  const iconEl = document.getElementById('modalIcon');
  if (action === 'delete') {
    iconEl.innerHTML = '<i class="fa fa-trash" style="color:red;"></i>';
  } else if (action === 'move') {
    iconEl.innerHTML = '<i class="fa fa-arrow-right" style="color:blue;"></i>';
  }

  document.getElementById('confirmModal').style.display = 'flex';
}


function hideConfirm() {
  actionType = null;
  selectedId = null;
  toTarget = null;
  document.getElementById('confirmModal').style.display = 'none';
}

function doAction() {
  if (!selectedId || !actionType) return;

  let url = '?';
  if (actionType === 'move' && toTarget) {
    url += `move=${selectedId}&to=${toTarget}`;
  } else if (actionType === 'delete') {
    url += `delete=${selectedId}`;
  }

  window.location.href = url;
}


function copyRepairData(id) {
  // ทำ AJAX request ด้วย fetch
  fetch("assets/get_repair_data.php?id=" + encodeURIComponent(id))
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert("ไม่พบข้อมูล: " + data.error);
        return;
      }

      // สร้างข้อความที่จะคัดลอก
      const copyText =
        `เลขเคส: ${data.case_number}\n` +
        `วันที่แจ้ง: ${data.created_at}\n` +
        `แจ้งช่าง: ${data.target}\n` +
        `สาขา: ${data.branch_prefix}-${data.branch}\n` +
        `เรื่อง: ${data.title}\n` +
        `รายละเอียด: ${data.description}\n` +
        `ผู้แจ้ง: ${data.reporter}\n` +
        `ช่องทางติดต่อ: ${data.contact}\n` +
        `note: ${data.note}\n` +
        `สถานะ: ${data.status}`;
        

      // คัดลอกข้อความไปคลิปบอร์ด
      navigator.clipboard.writeText(copyText).then(() => {
        alert("คัดลอกข้อมูลสำเร็จ!");
      }).catch(err => {
        console.error("Error copying: ", err);
        alert("เกิดข้อผิดพลาดในการคัดลอก");
      });
    })
    .catch(err => {
      console.error("Fetch error: ", err);
      alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
    });
}