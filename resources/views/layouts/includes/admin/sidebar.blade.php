<div class="logo">
    smb
</div>

<a class="btn btn-primary action" href="{{ url('/admin/orders/create') }}"><i class="fa fa-plus" aria-hidden="true"></i> create order </a>

<nav>
    <a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}"><i class="fa fa-home" aria-hidden="true"></i> home</a>
    <a class="{{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{ url('/admin/customers') }}"><i class="fa fa-users" aria-hidden="true"></i> customers</a>
    <a class="{{ Request::is('admin/project*') ? 'active' : '' }}" href="{{ url('/admin/projects') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> projects</a>
    <a class="{{ Request::is('admin/categories*') ? 'active' : '' }}" href="{{ url('/admin/categories') }}"><i class="fa fa-folder-open" aria-hidden="true"></i> categories</a>
    <a class="{{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i> premedia</a>
</nav>