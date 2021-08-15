@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $client->name }} ({{ $client->sin }})
                        <a href="" class="badge badge-success" style="float:right;margin-right:5px" data-toggle="modal" data-target="#adddocument">
                            <i class="fas fa-plus"></i>
                            Add Document
                        </a>
                        <a href="" class="badge badge-warning" style="float:right;margin-right:5px" data-toggle="modal" data-target="#addnote">
                            <i class="fas fa-plus"></i>
                            Add Note
                        </a>
                        <a href="" class="badge badge-primary" style="float:right;margin-right:5px" data-toggle="modal" data-target="#viewnotes">
                            <i class="fas fa-eye"></i>
                            Notes
                        </a>
                    </div>
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class = "alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ( Session::has('flash_message') )
                            <div class="alert alert-{{ Session::get('flash_type') }} alert-dismissible fade show" role="alert">
                                <b>{{ Session::get('flash_message') }}</b>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                @if(count($client->listdocuments)>0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover datatable datatable-User">
                                            <thead>
                                                <tr>
                                                    <th style="width:35%">Name</th>
                                                    <th>Document</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($client->listdocuments as $item)
                                                    <tr>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            @foreach ($item->documents as $item1)
                                                                <a href="/storage/{{ $item1->document }}" target="_blank">
                                                                    @if(strlen($item1->name) > 25)
                                                                        {{ substr($item1->name,0,25) }}...
                                                                    @else
                                                                        {{ $item1->name }}
                                                                    @endif
                                                                </a>
                                                                <a href="/home/client/document/{{ $item1->id }}/delete" style="color:red" onclick="return confirm('Do you want to delete {{ $item1->name }} ?'); ">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                                <br>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ date("F jS Y",strtotime($item->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h6>No Document Found.</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="adddocument" tabindex="-1" aria-labelledby="adddocumentLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adddocumentLabel">Add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/home/client/document/store" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Name*</label>
                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="document" class="col-md-2 col-form-label text-md-right">Document*</label>
                            <div class="col-md-10">
                                <input id="docment" type="file"  name="document[]" required multiple>
                            </div>
                        </div>
                        <input type="hidden" name="client" value="{{ $client->id }}">
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addnote" tabindex="-1" aria-labelledby="addnoteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addnoteLabel">Add Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/home/client/note/store">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="note" type="text" class="form-control" placeholder="Note..." maxlength="200" name="note" required  data-toggle="tooltip" data-placement="bottom" title="Max Characeter Limit is 200.">
                            </div>
                        </div>
                        <span style="float: right">
                            <span id="rem">200</span>
                        </span>
                        <input type="hidden" name="client" value="{{ $client->id }}">
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewnotes" tabindex="-1" aria-labelledby="viewnotesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewnotesLabel">View Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(count($client->notes) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable datatable-User">
                                <thead>
                                    <tr>
                                        <th style="width:80%">Note</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($client->notes as $item)
                                        <tr>
                                            <td>{{ $item->note }}</td>
                                            <td>{{ date("F jS Y",strtotime($item->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h6>No Notes Found.</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 
@section('JS')
    <script>
        jQuery(document).ready(function ()
        {
            $('#note').keyup(function() {
                $('#rem').text(200-$(this).val().length)
            });
        });
    </script>
@endsection