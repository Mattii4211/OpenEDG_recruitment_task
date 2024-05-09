const defaultModal = document.querySelector('.modal');
const modalBody = document.querySelector('.modal-body');
const modalTitle = document.querySelector('.modal-title');

function showModal(title, text) {
    const modal = new bootstrap.Modal(defaultModal);
    modalBody.innerHTML = text;
    modalTitle.innerHTML = title;
    modal.show();
};

export default showModal;