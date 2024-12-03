@extends('backend.layouts.master')

@section('main-content')
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3 bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold">LES UTILISATEURS</h4>
        <a href="{{route('users.create')}}" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ajouter un utilisateur">
            <h5> <i class="fas fa-plus"></i>Ajouter un utilisateur</h5>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered" id="user-dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>Date d'inscription</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td class="text-center">
                            @if($user->photo)
                                <img src="{{$user->photo}}" class="img-fluid rounded-circle border" style="max-width:50px;" alt="{{$user->name}}">
                            @else
                                <img src="{{asset('backend/img/avatar.png')}}" class="img-fluid rounded-circle border" style="max-width:50px;" alt="avatar">
                            @endif
                        </td>
                        <td>{{(($user->created_at)? $user->created_at->format('d/m/Y') : '')}}</td>
                        <td>{{$user->role}}</td>
                        <td class="text-center">
                            @if($user->status=='active')
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-warning">Inactif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('users.edit',$user->id)}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{route('users.destroy',[$user->id])}}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger dltBtn" data-id="{{$user->id}}" data-toggle="tooltip" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    .card-header {
        background-image: linear-gradient(90deg, #4a62ecfb, #3370f5);
        color: #fff;
    }
    table td, table th {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        $('#user-dataTable').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [6, 7] }
            ]
        });

        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            swal({
                title: "Êtes-vous sûr ?",
                text: "Une fois supprimé, cette action est irréversible.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
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
