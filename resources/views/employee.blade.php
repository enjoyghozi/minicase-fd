@extends('layouts.app')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#employeeTable').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                order: [
                    ['created_at', "desc"]
                ]
            });
        });

        var optionFeed = {
            complete: function(response) {
                $('#errors').hide();
                if (!$.isEmptyObject(response.responseJSON.errors)) {
                    $('#errors').show();
                    $('#errors-edit').find('ul').html('');
                    $.each(response.responseJSON.errors, function(index, value) {
                        $('#errors').find('ul').append('<li>' + value + '</li>');
                    });
                } else {
                    $('#createForm')[0].reset();
                    swal("Good job!", response.responseJSON.message, "success");
                }
            }
        };
        $('body').on('click', '.save-employee', function(event) {
            $(this).parents('form').ajaxForm(optionFeed);
        });
    </script>
    <script>
        $('body').on('click', '.edit-employee', function(event) {
            var id = ($(this).data('id'));
            $.ajax({
                    url: '/employee/' + id + '/edit',
                    type: 'GET',
                })
                .done(function(response) {
                    $('#editForm').find('#name').val(response.data.name);
                    $('#editForm').find('#nip').val(response.data.nip);
                    $('#editForm').find('#posisi').val(response.data.position);

                    $('#editForm').attr('action', response.url);

                    $('#editModal').modal('show');
                })
                .fail(function() {
                    console.log('error');
                })
                .always(function() {
                    console.log('complete');
                });
        });

        var optionFeed = {
            complete: function(response) {
                $('#errors-edit').hide();
                if (!$.isEmptyObject(response.responseJSON.errors)) {
                    $('#errors-edit').show();
                    $('#errors-edit').find('ul').html('');
                    $.each(response.responseJSON.errors, function(index, value) {
                        $('#errors-edit').find('ul').append('<li>' + value + '</li>');
                    });
                } else {
                    $('#editForm')[0].reset();
                    $('#employee-' + response.responseJSON.id).html('');
                    var htmlAppe =
                        '<td>' + response.responseJSON.data.name + '</td>' +
                        '<td>' + response.responseJSON.data.nip + '</td>' +
                        '<td>' + response.responseJSON.data.position + '</td>' +
                        '<td>' + response.responseJSON.data.start_date + '</td>' +
                        '<td>' +
                        '<a href="javascript:void(0)" class="btn btn-secondary btn-sm edit-employee" data-bs-toggle="modal" data-bs-target="#employeeEdit" data-id="' +
                        response.responseJSON.id + '">Edit</a>' +
                        '</td>';

                    $('#employee-' + response.responseJSON.id).html(htmlAppe);
                    swal("Good job!", response.responseJSON.message, "success");
                }
            }
        };
        $('body').on('click', '.update-employee', function(event) {
            $(this).parents('form').ajaxForm(optionFeed);
        });
    </script>

    <script>
        $('body').on('click', '.delete-employee', function(event) {
            var deleteId = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "Your will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    url: '/employee/' + deleteId,
                    type: 'Get',
                })
                .done(function(response) {
                    $('#employee-' + deleteId).remove();
                    swal("Deleted!", response.message, "success");
                })
                .fail(function() {
                    console.log('error');
                })
                .always(function() {
                    console.log('complete');
                });
            });
        })
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5>List Pegawai</h5>
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeAdd">Tambah</a>
                    </div>
                </div>

                <div class="mt-5">
                    <table id="employeeTable" class="display nowrap" style="width:100%">
                        <thead>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Posisi</th>
                            <th>Mulai Bekerja</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @forelse ($employees as $item)
                                <tr id="employee-{{ $item->id }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->start_date }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm edit-employee"
                                            data-bs-toggle="modal" data-bs-target="#employeeEdit"
                                            data-id="{{ $item->id }}">Edit</a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-employee"
                                            data-bs-toggle="modal"
                                            data-id="{{ $item->id }}">Hapus</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="employeeAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="employeeAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeAddLabel">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('employee.store') }}" method="post" id="createForm">
                    <div class="modal-body">
                        <div id='errors' class="alert alert-danger" style="display: none">
                            <ul></ul>
                        </div>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip"
                                placeholder="Nomor Induk Pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="posisi" class="form-label">Posisi</label>
                            <input type="text" class="form-control" id="posisi" name="position"
                                placeholder="Posisi pegawai">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save-employee">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="employeeEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="employeeEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeEditLabel">Edit Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="editForm">
                    @method('PATCH')
                    <div class="modal-body">
                        <div id='errors-edit' class="alert alert-danger" style="display: none">
                            <ul></ul>
                        </div>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip"
                                placeholder="Nomor Induk Pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="posisi" class="form-label">Posisi</label>
                            <input type="text" class="form-control" id="posisi" name="position"
                                placeholder="Posisi pegawai">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary update-employee">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
