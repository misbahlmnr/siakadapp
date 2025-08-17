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
        ajax: string;
        columns: any[];
        role: string | undefined;
        editRoute: string;
        deleteRoute: string;
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
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'flex items-center justify-center gap-1',
                render: (data) => {
                    return `
                        <button class="btn-edit text-blue-500 cursor-pointer" data-id="${data}">Edit</button> |
                        ${detailRoute ? `<button class="btn-detail text-green-500 cursor-pointer" data-id="${data}">Detail</button> |` : ''} 
                        <button class="btn-delete text-red-500 cursor-pointer" data-id="${data}">Hapus</button>
                    `;
                },
            },
        ],
        drawCallback: function () {
            $('.btn-edit').on('click', function () {
                const id = $(this).data('id');
                router.visit(route(editRoute, { role, id }));
            });

            $('.btn-detail').on('click', function () {
                const id = $(this).data('id');
                router.visit(route(detailRoute, { role, id }));
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
                        router.delete(route(deleteRoute, { role, id }), {
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
