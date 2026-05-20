export const Modal = ( elements ) => {
    // $(`#root-modal`).remove();
    return `
    <div class="modal" tabindex="-1" id="root-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${elements.title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ${elements.body}
                </div>
            </div>
        </div>
    </div>`
}

function closeModal( id_modal ) {

    $(`#${id_modal}`).remove();

    // const modalElement =
    //     document.getElementById(id_modal);

    // const modal =
    //     bootstrap.Modal.getInstance(
    //         modalElement
    //     );

    // if (modal) {
    //     modal.hide();
    // }
}
window.closeModal = closeModal;