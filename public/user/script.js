
    function toggleDropdown(button) {
        const dropdownContent = button.nextElementSibling; // Lấy phần tử ul bên dưới nút
        const isVisible = dropdownContent.style.display === 'block';

        dropdownContent.style.display = isVisible ? 'none' : 'block'; // Đổi trạng thái hiển thị
    }

