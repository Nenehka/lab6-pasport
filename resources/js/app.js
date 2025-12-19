require('./bootstrap');
import * as bootstrap from 'bootstrap';

// Иконки соцсетей и логотип
import vkIcon from './img/vk.png';
import tgIcon from './img/tg.png';
import wikiIcon from './img/wiki.png';
import logo from './img/logo.png';

document.addEventListener('DOMContentLoaded', () => {
    // --- ИКОНКИ В НАВБАРЕ И ФУТЕРЕ ---

    const wikiImg = document.querySelector('.socials a:nth-child(1) img');
    const vkImg   = document.querySelector('.socials a:nth-child(2) img');
    const tgImg   = document.querySelector('.socials a:nth-child(3) img');
    const logoImg = document.querySelector('.navbar-brand .navbar-logo');

    if (wikiImg) wikiImg.src = wikiIcon;
    if (vkImg)   vkImg.src = vkIcon;
    if (tgImg)   tgImg.src = tgIcon;
    if (logoImg) logoImg.src = logo;

    // --- МОДАЛКА ---

    const cards      = document.querySelectorAll('.cards-row .card');
    const modalEl    = document.getElementById('rammsteinModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('rammsteinModalLabel');
    const modalText  = document.getElementById('modalText');

    let bootstrapModal = modalEl ? new bootstrap.Modal(modalEl) : null;

    const openModal = (index) => {
        if (!modalEl || !bootstrapModal) return;
        const card = cards[index];
        if (!card) return;

        // Картинка из карточки
        const cover = card.querySelector('.card-img-top');
        if (cover) {
            modalImage.src = cover.src;
            modalImage.alt = cover.alt || '';
        } else {
            modalImage.removeAttribute('src');
            modalImage.alt = '';
        }

        // Данные из data-атрибутов или из самой карточки
        const title = card.dataset.title
            || card.querySelector('.card-title')?.textContent?.trim()
            || '';

        const description = card.dataset.description
            || card.querySelector('.card-text')?.textContent?.trim()
            || '';

        const release = card.dataset.release || '';

        // Заголовок и текст
        modalTitle.textContent = title;

        let html = '';
        if (description) html += description;
        if (release) {
            html += ` <span class="popover-text"
                           data-bs-toggle="popover"
                           title="Дата выхода альбома"
                           data-bs-content="Год выпуска: ${release}">(ℹ)</span>`;
        }

        modalText.innerHTML = html;

        // Поповер в модалке
        const popoverTrigger = modalEl.querySelector('.popover-text');
        if (popoverTrigger) {
            new bootstrap.Popover(popoverTrigger, {
                trigger: 'hover',
                placement: 'top',
            });
        }

        modalEl.dataset.currentIndex = index;
        bootstrapModal.show();
    };

    // Кнопки "Подробнее"
    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach((btn, defaultIndex) => {
        const index = btn.dataset.index
            ? parseInt(btn.dataset.index, 10)
            : defaultIndex;

        btn.addEventListener('click', () => openModal(index));
    });

    // Переключение стрелками влево/вправо
    document.addEventListener('keydown', (e) => {
        if (!modalEl || !modalEl.classList.contains('show')) return;

        let currentIndex = parseInt(modalEl.dataset.currentIndex || '0', 10);

        if (e.key === 'ArrowRight') {
            currentIndex = (currentIndex + 1) % cards.length;
            openModal(currentIndex);
        } else if (e.key === 'ArrowLeft') {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
            openModal(currentIndex);
        }
    });
});