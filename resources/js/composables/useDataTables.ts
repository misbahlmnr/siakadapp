// resources/js/composables/useDataTable.js
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
    }: {
        ajax: string | { url: any; data: (d: any) => void };
        columns: any[];
        role?: string | undefined;
        editRoute?: string;
        deleteRoute?: string;
        detailRoute?: string;
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
            ...(editRoute || deleteRoute || detailRoute
                ? [
                      {
                          data: 'id',
                          orderable: false,
                          searchable: false,
                          className: 'flex items-center justify-center gap-1',
                          render: (data: any) => {
                              return `
                            ${editRoute ? `<button class="btn-edit text-blue-500 cursor-pointer" data-id="${data}">Edit</button> |` : ''}
                            ${detailRoute ? `<button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> |` : ''}
                            ${deleteRoute ? `<button class="btn-delete text-red-500 cursor-pointer" data-id="${data}">Hapus</button>` : ''}
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
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                const params = role ? { role, id } : { id };
                router.visit(route(editRoute, params));
            });

            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                const params = role ? { role, id } : { id };
                router.visit(route(detailRoute!, params));
            });

            $('.btn-delete').on('click', function () {
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
                        router.delete(route(deleteRoute, params), {
                            onSuccess: () => {
                                table.ajax.reload();
                            },
                        });
                    }
                });
            });
        },
    });

    return table;
}
