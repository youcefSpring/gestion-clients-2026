import $ from 'jquery';
import { clearErrors, closeModal, confirmAction, debounce, handleFailure, openModal, toast } from './ui';

const routes = () => window.App.routes;

function filters() {
    return {
        search: $('#project-search').val() ?? '',
        status: $('#project-status-filter').val() ?? '',
        customer: $('#project-customer-filter').val() ?? '',
        show_archived: $('#project-show-archived').is(':checked') ? 1 : '',
    };
}

function reloadTable() {
    const params = filters();

    $.get(routes().projects, params).done((response) => {
        $('#projects-table').html(response.html);

        const url = new URL(window.location.href);
        Object.entries(params).forEach(([key, value]) => {
            value ? url.searchParams.set(key, value) : url.searchParams.delete(key);
        });
        window.history.replaceState({}, '', url);
    }).fail((xhr) => handleFailure(xhr));
}

/** Switches the customer field between picking an existing one and typing a new one. */
function setCustomerMode(mode) {
    const $form = $('#project-form');

    $form.find('[name=customer_mode]').val(mode);

    $form.find('[data-customer-mode]').each(function () {
        const active = $(this).data('customer-mode') === mode;

        $(this).toggleClass('bg-white text-slate-900 shadow-sm', active)
            .toggleClass('text-slate-500', ! active);
    });

    $form.find('[data-customer-panel]').each(function () {
        $(this).toggleClass('hidden', $(this).data('customer-panel') !== mode);
    });

    $form.find(mode === 'new' ? '[name=customer_phone]' : '[name=customer_id]').trigger('focus');
}

function openProjectForm(project = null) {
    const $form = $('#project-form');

    $form[0].reset();
    clearErrors($form);
    $form.find('[name=id]').val(project?.id ?? '');
    $form.find('[name=customer_id]').val(project?.customerId ?? $('#project-customer-filter').val() ?? '');
    $form.find('[name=customer_name], [name=customer_phone]').val('');
    setCustomerMode('existing');
    $form.find('[name=name]').val(project?.name ?? '');
    $form.find('[name=description]').val(project?.description ?? '');
    $form.find('[name=status]').val(project?.status ?? 'new');

    openModal('project-modal', project ? window.App.messages.editProject : window.App.messages.newProject);
}

/** Keeps every customer dropdown in sync after a customer is created inline. */
function addCustomerOption(customer) {
    const label = `${customer.display_name} — ${customer.phone}`;

    $('#project-customer-filter, #project-form [name=customer_id]').each(function () {
        const $select = $(this);

        if ($select.find(`option[value="${customer.id}"]`).length) {
            $select.find(`option[value="${customer.id}"]`).text(label);
        } else {
            $select.append(new Option(label, customer.id));
        }
    });

    $('#project-form [name=customer_id]').val(customer.id);
}

$(function () {
    if (! $('#project-form').length) {
        return;
    }

    $(document).on('click', '[data-action=create-project]', () => openProjectForm());

    $(document).on('click', '[data-customer-mode]', function () {
        setCustomerMode($(this).data('customer-mode'));
    });

    $(document).on('click', '[data-action=edit-project]', function () {
        openProjectForm($(this).data());
    });

    $(document).on('submit', '#project-form', function (event) {
        event.preventDefault();

        const $form = $(this);
        const $submit = $form.find('[type=submit]').prop('disabled', true);
        const id = $form.find('[name=id]').val();

        $.ajax({
            url: id ? `${routes().projects}/${id}` : routes().projects,
            method: 'POST',
            data: {
                _method: id ? 'PUT' : 'POST',
                customer_mode: $form.find('[name=customer_mode]').val(),
                customer_id: $form.find('[name=customer_id]').val(),
                customer_name: $form.find('[name=customer_name]').val(),
                customer_phone: $form.find('[name=customer_phone]').val(),
                name: $form.find('[name=name]').val(),
                description: $form.find('[name=description]').val(),
                status: $form.find('[name=status]').val(),
            },
        }).done((response) => {
            closeModal('project-modal');
            toast(response.message);

            if (response.customer) {
                addCustomerOption(response.customer);
            }

            reloadTable();
        }).fail((xhr) => handleFailure(xhr, $form))
            .always(() => $submit.prop('disabled', false));
    });

    $(document).on('change', '[data-action=change-status]', function () {
        const $select = $(this);

        $.ajax({
            url: routes().projectStatus.replace(':id', $select.data('id')),
            method: 'POST',
            data: { _method: 'PATCH', status: $select.val() },
        }).done((response) => {
            toast(response.message);
            $select.attr('class', `rounded-full border-0 px-3 py-1.5 text-sm font-medium shadow-sm ring-1 ring-inset focus:outline-none focus:ring-2 focus:ring-slate-300 ${response.project.status_classes}`);

            const $row = $select.closest('[data-row]');
            $row.attr('class', `transition-colors ${response.project.row_classes}`);

            // Confirmed and cancelled rows leave the default list.
            if (response.project.archived && ! $('#project-show-archived').is(':checked')) {
                reloadTable();
            }
        }).fail((xhr) => {
            handleFailure(xhr);
            reloadTable();
        });
    });

    $(document).on('click', '[data-action=view-project]', function () {
        const data = $(this).data();
        const digits = String(data.digits ?? '').replace(/\D+/g, '');
        const $modal = $('#project-show-modal');

        $modal.find('[data-show=name]').text(data.name ?? '');
        $modal.find('[data-show=customer]').text(data.customer ?? '');
        $modal.find('[data-show=phone]').text(data.phone ?? '');
        $modal.find('[data-show=status]').text(data.status ?? '');
        $modal.find('[data-show=date]').text(data.date ?? '');

        const tags = String(data.description ?? '').split(/[,\n;]+/).map((tag) => tag.trim()).filter(Boolean);
        const $tags = $modal.find('[data-show=tags]').empty();

        if (tags.length) {
            tags.forEach((tag) => $tags.append(
                $('<span>', { class: 'inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200', text: tag })
            ));
        } else {
            $tags.append($('<span>', { class: 'text-sm text-slate-400', text: '—' }));
        }

        $modal.find('[data-show=customer-url]').attr('href', data.customerUrl ?? '#');
        $modal.find('[data-show=call]').attr({ href: digits ? `tel:${digits}` : '#' }).data('digits', digits).toggleClass('hidden', ! digits);
        $modal.find('[data-show=whatsapp]').attr({ href: digits ? `https://wa.me/${digits}` : '#' }).data('digits', digits).toggleClass('hidden', ! digits);

        openModal('project-show-modal');
    });

    $(document).on('click', '[data-action=delete-project]', function () {
        const id = $(this).data('id');

        confirmAction(window.App.messages.confirmDeleteProject, () => {
            $.ajax({
                url: `${routes().projects}/${id}`,
                method: 'POST',
                data: { _method: 'DELETE' },
            }).done((response) => {
                toast(response.message);
                reloadTable();
            }).fail((xhr) => handleFailure(xhr));
        });
    });

    $('#project-search').on('input', debounce(reloadTable));
    $('#project-status-filter, #project-customer-filter, #project-show-archived').on('change', reloadTable);

    $(document).on('click', '#projects-table nav a', function (event) {
        event.preventDefault();

        $.get(this.href).done((response) => $('#projects-table').html(response.html))
            .fail((xhr) => handleFailure(xhr));
    });

    document.addEventListener('customer:saved', (event) => addCustomerOption(event.detail));
});
