import { router } from '@inertiajs/vue3';
import 'datatables.net';
import $ from 'jquery';
import Swal from 'sweetalert2';

export function useDataTable(
    selector: string,
    {
        ajax,
        columns,
        role,
        editRoute,
        deleteRoute,
        detailRoute,
        actions,
        onAction,
    }: {
        ajax: string | { url: any; data: (d: any) => void };
        columns: any[];
        role?: string | undefined;
        editRoute?: string;
        deleteRoute?: string;
        detailRoute?: string;
        actions?: (id: any, rowData?: any) => string;
        onAction?: (action: string, id: any, rowData?: any) => void;
    },
) {
    const table = $(selector).DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        pagingType: 'simple_numbers',
        ajax,
        columns: [
            ...columns,
            ...(editRoute || deleteRoute || detailRoute || actions
                ? [
                      {
                          data: 'id',
                          orderable: false,
                          searchable: false,
                          className: 'flex items-center justify-center gap-2',
                          render: (data: any, type: any, row: any) => {
                              if (actions) {
                                  return actions(data, row);
                              }
                              return `
                                    ${editRoute ? `<button class="btn-edit text-yellow-500 btn-custom-outline" data-id="${data}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>` : ''}
                                    ${detailRoute ? `<button class="btn-detail text-green-500 cursor-pointer btn-custom-outline" data-id="${data}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg></button>` : ''}
                                    ${deleteRoute ? `<button class="btn-delete text-red-500 cursor-pointer btn-custom-outline" data-id="${data}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>` : ''}
                              `;
                          },
                      },
                  ]
                : []),
        ],
        language: {
            paginate: {
                previous: `<svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-left w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>`,
                next: `<svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-right w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>`,
            },
        },
        drawCallback: function () {
            // default handler
            $('.btn-edit')
                .off('click')
                .on('click', function () {
                    const id = $(this).data('id');
                    const params = role ? { role, id } : { id };
                    router.visit(route(editRoute!, params));
                });

            $('.btn-detail')
                .off('click')
                .on('click', function () {
                    const id = $(this).data('id');
                    const params = role ? { role, id } : { id };
                    router.visit(route(detailRoute!, params));
                });

            $('.btn-delete')
                .off('click')
                .on('click', function () {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: 'Data akan dihapus secara permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ff0000',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const params = role ? { role, id } : { id };
                            router.delete(route(deleteRoute!, params), {
                                onSuccess: () => table.ajax.reload(),
                            });
                        }
                    });
                });

            // custom handler → tangkap semua tombol yang punya data-action
            if (onAction) {
                $('[data-action]')
                    .off('click')
                    .on('click', function () {
                        const id = $(this).data('id');
                        const action = $(this).data('action');
                        const rowData = table.row($(this).closest('tr')).data();
                        onAction(action, id, rowData);
                    });
            }
        },
    });

    return table;
}
