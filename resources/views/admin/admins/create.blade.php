@extends('layouts.admin')

@section('content')

  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Admin User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group mr-2">
        <a href="{{ route('admins.index') }}" title="All admin users">
            <button class="btn btn-sm btn-outline-success"><span data-feather="arrow-left"></span>Go Back</button>
        </a>
        <button class="btn btn-sm btn-outline-secondary">Export</button>
      </div>

      <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
        <span data-feather="calendar"></span>
        This week
      </button>
    </div>
  </div>

  <div class="table-responsive col-12">
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('admins.store') }}" method="post">
        @csrf
        <div class="card">
            <div class="card-block">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input name="name" class="form-control @error('name') is-invalid @enderror" type="text" value="" required>
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" class="form-control @error('email') is-invalid @enderror" type="email" value="" required>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="password" class="form-control @error('password') is-invalid @enderror" type="password" value="" required>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
    
            </div>

            <div class="card-footer text-muted">
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary"><span data-feather="save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>

  </div>
@endsection
