<div class="logo">
    smb
</div>

<button class="btn btn-primary action"><i class="fa fa-plus" aria-hidden="true"></i> add project</button>

<nav>
    <a class="{{ Request::is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}"><i class="fa fa-home" aria-hidden="true"></i> home</a>
    <a class="{{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{ url('/admin/customers') }}"><i class="fa fa-users" aria-hidden="true"></i> customers</a>
    <a class="{{ Request::is('admin/projects*') ? 'active' : '' }}" href="{{ url('/admin/projects') }}"><i class="fa fa-book" aria-hidden="true"></i> projects</a>
    <a class="{{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i> premedia</a>
</nav>