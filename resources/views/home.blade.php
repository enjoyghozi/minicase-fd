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

        $("#createForm").validate({
            rules:{
                name: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                name: {
                    required: "Please enter name",
                    minlength: "Name must be at least 2 characters long"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5>List Pegawai</h5>
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah</a>
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="createForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama-pegawai" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="nama-pegawai" placeholder="Nama pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="nip-pegawai" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nama-pegawai" placeholder="Nomor Induk Pegawai">
                        </div>
                        <div class="mb-3">
                            <label for="posisi-pegawai" class="form-label">Posisi</label>
                            <input type="text" class="form-control" id="posisi-pegawai" placeholder="Posisi pegawai">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
