const menuItems = document.querySelectorAll('.menu-item');

    const iconMap = {
        'lapis.png': 'lapis-azul.png',
        'casa.png': 'casa-azul.png',
        'grafico.png': 'grafico-azul.png',
        'sair.png': 'sair-azul.png'
    };

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            menuItems.forEach(i => {
                i.classList.remove('active');
                const img = i.querySelector('img');
                const fileName = img.getAttribute('src').split('/').pop();
                if (fileName.includes('-azul')) {
                    const whiteIcon = fileName.replace('-azul', '');
                    img.setAttribute('src', './assets/icons/' + whiteIcon);
                }
            });

            item.classList.add('active');
            const img = item.querySelector('img');
            const fileName = img.getAttribute('src').split('/').pop();
            if (!fileName.includes('-azul')) {
                const blueIcon = fileName.replace('.png', '-azul.png');
                img.setAttribute('src', './assets/icons/' + blueIcon);
            }
        });
    });