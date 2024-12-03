@extends('backend.layouts.master')

@section('main-content')
<!-- Exemple DataTales -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold">Liste des Catégories</h4>
        <a href="{{ route('category.create') }}" class="btn btn-light btn-sm float-right" style="color: #4e73df;" data-toggle="tooltip" data-placement="bottom" title="Ajouter une catégorie">
            <i class="fas fa-plus"></i> Ajouter un catégorie</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($categories) > 0)
            <table class="table table-hover table-striped table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Est Parent</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->title }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->is_parent == 1 ? 'Oui' : 'Non' }}</td>
                        <td>
                            @if($category->photo)
                                <img src="{{ $category->photo }}" class="img-fluid" style="max-width:80px" alt="{{ $category->photo }}">
                            @else
                                <img src="{{ asset('backend/img/thumbnail-default.jpg') }}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                            @endif
                        </td>
                        <td>
                            @if($category->status == 'active')
                                <span class="badge badge-success">{{ $category->status }}</span>
                            @else
                                <span class="badge badge-warning">{{ $category->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Modifier"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('category.destroy', $category->id) }}" style="display:inline;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper" style="float:right;">
                {{ $categories->links() }}
            </div>
            @else
            <h6 class="text-center">Aucune catégorie trouvée ! Veuillez créer une catégorie.</h6>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('#banner-dataTable').DataTable({
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [3, 4, 5]
                }
            ]
        });

        // Confirmation de suppression SweetAlert
        $('.btn-danger').click(function (e) {
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            e.preventDefault();

            swal({
                title: "Êtes-vous sûr ?",
                text: "Une fois supprimé, vous ne pourrez pas récupérer cette donnée !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                } else {
                    swal("Vos données sont en sécurité !");
                }
            });
        });
    });
</script>
@endpush
