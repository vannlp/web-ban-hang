window.SetUrl = function (items) {
  try {
    window.parent.postMessage({
      type: 'lfm-selected',
      items: items,
    }, '*'); // Hoặc '*' nếu bạn cần cross-origin
    
    // window.close();
  } catch (error) {
    console.error('Gửi postMessage thất bại:', error);
    alert('Không thể gửi dữ liệu về trang cha.');
  }
};
