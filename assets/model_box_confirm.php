<div id="confirmModal" style="
  display: none; 
  position: fixed; 
  left: 0; 
  top: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(0,0,0,0.5); 
  justify-content: center; 
  align-items: center;
">
  <div style="
    background: #fff; 
    padding: 20px; 
    border-radius: 8px; 
    text-align: center; 
    max-width: 300px;
  ">
    <div id="modalIcon" style="font-size: 40px; margin-bottom: 10px;"></div>
    <p>คุณแน่ใจที่จะดำเนินการไหม?</p>
    <button onclick="doAction()">Yes</button>
    <button onclick="hideConfirm()">No</button>
  </div>
</div>