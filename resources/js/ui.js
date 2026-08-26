import $ from 'jquery';

/** Shared UI helpers: toasts, modals and the confirmation dialog. */

export function toast(message, type = 'success') {
    const colors = {
        success: 'bg-slate-900',
        error: 'bg-rose-600',
    };

    const $toast = $('<div>', {
        class: `rounded-lg px-4 py-3 text-sm text-white shadow-lg ${colors[type] ?? colors.success}`,
        text: message,
    });

    $('#toasts').append($toast);

    setTimeout(() => $toast.remove(), 3000);
}

export function openModal(id, title = null) {
    const $modal = $(`#${id}`);

    if (title) {
        $modal.find('[data-modal-title]').text(title);
    }

    $modal.removeClass('hidden').addClass('flex');
    $modal.find('input, select, textarea').not('[type=hidden]').first().trigger('focus');
}

export function closeModal(id) {
    $(`#${id}`).removeClass('flex').addClass('hidden');
}

export function confirmAction(message, onAccept) {
    const $dialog = $('#confirm-dialog');

    $dialog.find('[data-confirm-message]').text(message);
    $dialog.removeClass('hidden').addClass('flex');

    $dialog.find('[data-confirm-accept]').off('click').on('click', function () {
        closeModal('confirm-dialog');
        onAccept();
    });
}

export function clearErrors($form) {
    $form.find('[data-error]').addClass('hidden').text('');
}

export function showErrors($form, errors) {
    clearErrors($form);

    Object.entries(errors).forEach(([field, messages]) => {
        $form.find(`[data-error="${field}"]`).removeClass('hidden').text(messages[0]);
    });
}

/** Turns any jQuery AJAX failure into a user-facing message. */
export function handleFailure(xhr, $form = null) {
    if (xhr.status === 422 && $form) {
        showErrors($form, xhr.responseJSON?.errors ?? {});

        return;
    }

    if (xhr.status === 401 || xhr.status === 419) {
        window.location.reload();

        return;
    }

    toast(xhr.responseJSON?.message || window.App.messages.error, 'error');
}

/** Debounce used by the instant search inputs. */
export function debounce(callback, wait = 300) {
    let timer;

    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => callback.apply(this, args), wait);
    };
}

$(function () {
    $(document).on('click', '[data-modal-close]', function () {
        closeModal($(this).closest('[data-modal]').attr('id'));
    });

    // Clicking the backdrop (but not the panel) closes the modal.
    $(document).on('click', '[data-modal]', function (event) {
        if (event.target === this) {
            closeModal(this.id);
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            $('[data-modal]').not('.hidden').each(function () {
                closeModal(this.id);
            });
        }
    });
});
