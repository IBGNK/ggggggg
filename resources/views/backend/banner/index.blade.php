@extends('backend.layouts.master')
@section('title','E-SHOP || Banner Page')

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold"> Liste Banners</h4>
        <a href="{{route('banner.create')}}" class="btn btn-light btn-sm float-right" style="color: #4e73df;" data-toggle="tooltip" data-placement="bottom" title="Add Banner">
            <i class="fas fa-plus"></i> Add Banner
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($banners) > 0)
            <table class="table table-hover table-striped table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr>
                        <td>{{$banner->id}}</td>
                        <td>{{$banner->title}}</td>
                        <td>{{$banner->slug}}</td>
                        <td>
                            @if($banner->photo)
                            <img src="{{$banner->photo}}" class="img-thumbnail" style="max-width: 80px; height: auto;" alt="{{$banner->title}}">
                            @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-thumbnail" style="max-width: 80px; height: auto;" alt="default-image">
                            @endif
                        </td>
                        <td>
                            @if($banner->status == 'active')
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('banner.edit', $banner->id)}}" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{route('banner.destroy', $banner->id)}}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-danger mx-1" data-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-end mt-3">
                {{$banners->links()}}
            </div>
            @else
            <h6 class="text-center">No banners found! Please create a banner.</h6>
            @endif
        </div>
    </div>

</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    .card-header {
        font-size: 18px;
        font-weight: bold;
    }

    .btn-sm {
        padding: 6px 12px;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
    }

    .badge {
        font-size: 14px;
    }

    .pagination {
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#banner-dataTable').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [3, 4, 5] }
            ]
        });
    });
</script>
@endpush
