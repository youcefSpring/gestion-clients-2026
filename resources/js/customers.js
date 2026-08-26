import $ from 'jquery';
import { clearErrors, closeModal, confirmAction, debounce, handleFailure, openModal, toast } from './ui';

const routes = () => window.App.routes;

function reloadTable() {
    const search = $('#customer-search').val() ?? '';

    $.get(routes().customers, { search }).done((response) => {
        $('#customers-table').html(response.html);
        const url = new URL(window.location.href);
        search ? url.searchParams.set('search', search) : url.searchParams.delete('search');
        window.history.replaceState({}, '', url);
    }).fail((xhr) => handleFailure(xhr));
}

export function openCustomerForm(customer = null) {
    const $form = $('#customer-form');

    $form[0].reset();
    clearErrors($form);
    $form.find('[name=id]').val(customer?.id ?? '');
    $form.find('[name=name]').val(customer?.name ?? '');
    $form.find('[name=phone]').val(customer?.phone ?? '');

    openModal('customer-modal', customer ? window.App.messages.editCustomer : window.App.messages.newCustomer);
}

$(function () {
    if (! $('#customer-form').length) {
        return;
    }

    $(document).on('click', '[data-action=create-customer]', () => openCustomerForm());

    $(document).on('click', '[data-action=edit-customer]', function () {
        openCustomerForm($(this).data());
    });

    $(document).on('submit', '#customer-form', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $submit = $form.find('[type=submit]').prop('disabled', true);
        const id = $form.find('[name=id]').val();

        $.ajax({
            url: id ? `${routes().customers}/${id}` : routes().customers,
            method: 'POST',
            data: {
                _method: id ? 'PUT' : 'POST',
                name: $form.find('[name=name]').val(),
                phone: $form.find('[name=phone]').val(),
            },
        }).done((response) => {
            closeModal('customer-modal');
            toast(response.message);
            document.dispatchEvent(new CustomEvent('customer:saved', { detail: response.customer }));
        }).fail((xhr) => handleFailure(xhr, $form))
            .always(() => $submit.prop('disabled', false));
    });

    $(document).on('click', '[data-action=delete-customer]', function () {
        const id = $(this).data('id');

        confirmAction(window.App.messages.confirmDeleteCustomer, () => {
            $.ajax({
                url: `${routes().customers}/${id}`,
                method: 'POST',
                data: { _method: 'DELETE' },
            }).done((response) => {
                toast(response.message);
                reloadTable();
            }).fail((xhr) => handleFailure(xhr));
        });
    });

    $('#customer-search').on('input', debounce(reloadTable));

    // Pagination inside the AJAX-rendered table.
    $(document).on('click', '#customers-table .pagination a, #customers-table nav a', function (event) {
        event.preventDefault();

        $.get(this.href).done((response) => $('#customers-table').html(response.html))
            .fail((xhr) => handleFailure(xhr));
    });

    document.addEventListener('customer:saved', () => {
        if ($('#customers-table').length) {
            reloadTable();
        }
    });
});
