@php $name = Route::currentRouteName() @endphp
<aside class="main-sidebar">
  <section class="sidebar">

    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ auth()->user()->profile_image }}" alt="User Image" style="border-radius: 50%;height:43px">
      </div>
      <div class="pull-left info">
        <p>{{auth()->user()->name}}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">

      <li class="header">MAIN NAVIGATION</li>

      <li class="{{ $name == 'dashboard' ? 'active' : '' }}">
        <a href="{{ route('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
      </li>

      <li class="{{ in_array($name,['category','category.create','category.edit']) ? 'active' : '' }}">
        <a href="{{ route('category')}}"><i class="fa fa-sitemap"></i> <span>Category</span></a>
      </li>

      <li class="{{ in_array($name,['post','post.create','post.edit']) ? 'active' : '' }}">
        <a href="{{ route('post')}}"><i class="fa fa-clipboard"></i> <span>Post</span></a>
      </li>

      <li class="{{ in_array($name,['casino','casino.create','casino.edit']) ? 'active' : '' }}">
        <a href="{{ route('casino')}}"><i class="fa fa-gamepad" aria-hidden="true"></i><span>Casino</span></a>
      </li>

      <li class="{{ in_array($name,['user']) ? 'active' : '' }}">
        <a href="{{ route('user')}}"><i class="fa fa-users" aria-hidden="true"></i><span>Users</span></a>
      </li>

    </ul>
  </section>
</aside>