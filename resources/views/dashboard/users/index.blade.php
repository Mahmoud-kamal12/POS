@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.users')</h1>

            <ol class="breadcrumb">
                <li ><a href="{{route('dashboard.dashboard.index')}}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.users')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">


                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.users') <small> {{$users->total()}}</small> </h3>

                    <form action="{{ route('dashboard.users.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control mar2" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                @if(auth()->user()->hasPermission('users_create'))
                                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus "></i> @lang('site.add')</a>
                                @endif
                                </div>
                        </div>
                    </form><!-- end of form -->



                </div><!-- end of box header -->

                <div class="box-body">
                    @if($users->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 4%">@lang('site.image_profile')</th>
                                    <th>@lang('site.first_name')</th>
                                    <th>@lang('site.last_name')</th>
                                    <th>@lang('site.email')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $index=> $user)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td ><img class="img-fluid img-thumbnail" width="30" height="30" style="border-radius: 50%" src="{{$user->image_path}}" alt="user iamge"></td>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->last_name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @if(auth()->user()->hasPermission('users_update'))
                                                <a href="{{route('dashboard.users.edit' , $user->id)}}" class="mar btn btn-info btn-sm "> <i class="fa fa-edit"></i>  &nbsp;@lang('site.edit')</a>
                                            @else
                                                <a href="#" class="mar btn btn-info btn-sm disabled "> <i class="fa fa-edit"></i>  &nbsp;@lang('site.edit')</a>
                                            @endif
                                            @if(auth()->user()->hasPermission('users_delete'))
                                                <form action="{{route('dashboard.users.destroy',$user->id)}}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm delete" style="width: 68.463px;"> <i class="fa fa-trash"></i>  &nbsp;@lang('site.delete')</button>
                                                </form>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-sm disabled" style="width: 68.463px;"> <i class="fa fa-trash"></i>  &nbsp;@lang('site.delete')</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{$users->appends(request()->query())->links()}}
                    @else
                        <h2 class="text-center">@lang('site.no_users_found')</h2>
                    @endif
                </div><!-- end of box body -->

            </div><!-- end of box -->
        </section><!-- end of content -->

    </div><!-- end of content wrapper -->




@endsection
