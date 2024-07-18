@extends('layouts.app')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#employeeTable').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
        });

        var optionFeed = {
            complete:function(response){
                $('#errors').hide();
                if (!$.isEmptyObject(response.responseJSON.errors)) {
                    $('#errors').show();
                    $.each(response.responseJSON.errors, function(index, value) {
                        $('#errors').find('ul').append('<li>'+value+'</li>');
                    });
                } else {
                    $('#createForm')[0].reset();
                    $('#employeeAdd').modal('hide');
                    swal("Good job!", response.responseJSON.message, "success");
                    $('#employeeTable').DataTable().ajax.reload();
                }
            }
        };
        $('body').on('click', '.save-employee', function(event) {
            $(this).parents('form').ajaxForm(optionFeed);

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
                        </thead>
                        <tbody>
                            @forelse ($employees as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->start_date }}</td>
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

    <!-- Modal -->
    <div class="modal fade" id="employeeAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="employeeAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeAddLabel">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('home.store') }}" method="post" id="createForm">
                    <div class="modal-body">
                        <div id='errors' class="alert alert-danger" style="display: none">
                            <ul></ul>
                        </div>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="posisi" class="form-label">Posisi</label>
                            <input type="text" class="form-control" id="posisi" name="position" placeholder="Posisi pegawai">
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
@endsection
