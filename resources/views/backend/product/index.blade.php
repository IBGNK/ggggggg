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
        <h4 class="m-0 font-weight-bold">Liste des produits</h4>
        <a href="{{route('product.create')}}" class="btn btn-light btn-sm float-right" style="color: #4e73df;" data-toggle="tooltip" data-placement="bottom" title="Ajouter un produit"><i class="fas fa-plus"></i> Ajouter Produit</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($products) > 0)
            <table class="table table-hover table-striped table-bordered" id="product-dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Mis en avant</th>
                        <th>Prix</th>
                        <th>Réduction</th>
                        <th>État</th>
                        <th>Marque</th>
                        <th>Stock</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    @php
                    $sub_cat_info = DB::table('categories')->select('title')->where('id', $product->child_cat_id)->get();
                    $brands = DB::table('brands')->select('title')->where('id', $product->brand_id)->get();
                    @endphp
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->title}}</td>
                        <td>
                            {{ isset($product->cat_info['title']) ? $product->cat_info['title'] : 'Catégorie non définie' }}
                            <sub>{{ isset($product->sub_cat_info) && isset($product->sub_cat_info->title) ? $product->sub_cat_info->title : '' }}</sub>
                        </td>
                        <td>{{(($product->is_featured == 1) ? 'Oui' : 'Non')}}</td>
                        <td>{{$product->price}} CFA</td>
                        <td>{{$product->discount}}%</td>

                        <td>{{$product->condition}}</td>
                        <td>{{ucfirst($product->brand->title)}}</td>
                        <td>
                            @if($product->stock > 0)
                            <span class="badge badge-primary">{{$product->stock}} Kg</span>
                            @else
                            <span class="badge badge-danger">{{$product->stock}}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->photo)
                            @php
                            $photo = explode(',', $product->photo);
                            @endphp
                            <img src="{{$photo[0]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$product->photo}}">
                            @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="image par défaut">
                            @endif
                        </td>
                        <td>
                            @if($product->status == 'active')
                            <span class="badge badge-success">{{$product->status}}</span>
                            @else
                            <span class="badge badge-warning">{{$product->status}}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('product.edit', $product->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" title="Modifier" data-placement="bottom"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{route('product.destroy', [$product->id])}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm dltBtn" data-id={{$product->id}} style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-2">
                {{$products->links()}}
            </div>
            @else
            <h6 class="text-center">Aucun produit trouvé ! Veuillez créer un produit.</h6>
            @endif
        </div>
    </div>

</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }

    .zoom {
        transition: transform .2s; /* Animation */
    }

    .zoom:hover {
        transform: scale(3.5);
    }

    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }
</style>
@endpush

@push('scripts')

<!-- Plugins de page -->
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Scripts personnalisés de page -->
<script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>

<script>
    $('#product-dataTable').DataTable({
        "scrollX": false,
        "columnDefs": [{
            "orderable": false,
            "targets": [10, 11, 12]
        }]
    });

    // Sweet alert pour la suppression
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.dltBtn').click(function (e) {
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
