<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
    <a class="navbar-brand" href="#">Dashboard</a>
    <div class="ms-auto">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
        </form>
    </div>
</nav>
<div class="container mt-4">
    <h1>Welcome, {{ auth()->user()->name }}</h1>
    <p>Your email: {{ auth()->user()->email }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Manage Products</a>
    
    <a href="{{ route('roles.index') }}" class="btn btn-primary">Manage Role</a>
</div>
</body>
</html>
