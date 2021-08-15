@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        My Clients
                        @if(Route::currentRouteName() == "home")
                            <a href="" class="badge badge-success" style="float:right" data-toggle="modal" data-target="#registerclient">
                                <i class="fas fa-plus"></i>
                                Client
                            </a>
                        @endif
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
                        @if(method_exists($clients, 'links'))
                            <form method="POST" action="/home/client/search">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-lg-4 col-md-6 mt-md-3">
                                        <input id="name" type="text" class="form-control" name="name" placeholder="Name">
                                    </div>
                                    <div class="col-lg-4 col-md-6 mt-md-3">
                                        <input id="sin" type="text" class="form-control" minlength="9" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="sin" placeholder="SIN">
                                    </div>
                                    <div class="col-lg-2 col-md-6 mt-md-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <h6>{{ count($clients) }} Results Found.</h6>
                        @endif
                        @if(count($clients)>0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable datatable-User">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>SIN</th>
                                            @admin
                                                <th>Subscriber</th>
                                            @endadmin
                                            <th>Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $item)
                                            <tr>
                                                <td>
                                                    <a href="/home/client/{{ $item->id }}/show">
                                                        {{ $item->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $item->sin }}</td>
                                                @admin
                                                    <td>{{ $item->user->name }}</td>
                                                @endadmin
                                                <td>{{ $item->documentcount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(method_exists($clients, 'links'))
                                {{ $clients->links() }}
                            @endif
                        @else
                            <h6>No Clients Found.</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    @if(Route::currentRouteName() == "home")
        <div class="modal fade" id="registerclient" tabindex="-1" aria-labelledby="registerclientLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerclientLabel">Add Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/home/client/store">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name*</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sin" class="col-md-4 col-form-label text-md-right">SIN*</label>
                                <div class="col-md-8">
                                    <input id="sin" type="text" class="form-control" minlength="9" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="sin" required>
                                </div>
                            </div>
                            @admin
                                @if(count($user)>0)
                                    <div class="form-group row">
                                        <label for="user" class="col-md-4 col-form-label text-md-right">Select User*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" id="user" name="user" required>
                                                @foreach($user as $item)
                                                    <option value='{{ $item->id }}'>{{ $item->email }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="user" value="{{ Auth::User()->id }}">
                                @endif
                            @endadmin
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
        <div class="modal fade" id="changepassword" tabindex="-1" aria-labelledby="changepasswordLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changepasswordLabel">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/home/change-password">
                            @csrf
                            <div class="form-group row">
                                <label for="oldpassword" class="col-md-4 col-form-label text-md-right">Old Password</label>
                                <div class="col-md-8">
                                    <input id="oldpassword" type="password" class="form-control " name="oldpassword" required >
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                        
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                        
                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required >
                                </div>
                            </div>
                        
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @admin
            <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adduserModalLabel">Register</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="home/user/store">
                                @csrf
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control " name="name"  required  autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email"  required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                                    <div class="col-md-6">
                                        <label class="col-form-label">
                                            <input id="role" type="checkbox" name="role"> Admin(Shows all Clients.)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="viewuser" tabindex="-1" aria-labelledby="viewuserLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewuserLabel">Existing Users:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if(count($user)>0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover datatable datatable-User">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user as $item)
                                                
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>
                                                        @if($item->isAdmin == 1)
                                                            Admin                                
                                                        @else
                                                            Subscriber
                                                        @endif
                                                    <td>
                                                        <a href="" class='btn btn-outline-danger btn-sm changepassword' id="{{ $item->id }}" > 
                                                            <i class="fas fa-key"></i>
                                                        </a> 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6>No Users Found.</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="changepassword2" tabindex="-1" aria-labelledby="changepasswordLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changepasswordLabel">Change Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/home/change-password">
                                @csrf
                                
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control" name="password" required>
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                            
                                    <div class="col-md-8">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required >
                                    </div>
                                </div>
                                <input type="hidden" name="userid" required>
                            
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endadmin
    @endif
@endsection
@section('JS')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @admin
        <script>
            jQuery(document).ready(function ()
            {
                $('.changepassword').click(function(e){
                    e.preventDefault();
                    $("input[name='userid']").val($(this).attr('id'));
                    $('#changepassword2').modal('show');
                });
            });
        </script>
    @endadmin
@endsection