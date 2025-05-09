function toggleMenu() {
    const sidebar = document.querySelector('.sidebar');
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeBtn = document.getElementById('closeBtn');

    sidebar.classList.toggle('active');

    // Alternar visibilidade dos botões
    if (sidebar.classList.contains('active')) {
        hamburgerBtn.style.display = 'none';
        closeBtn.style.display = 'block';
    } else {
        hamburgerBtn.style.display = 'block';
        closeBtn.style.display = 'none';
    }
}