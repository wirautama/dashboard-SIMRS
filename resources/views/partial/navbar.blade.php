<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown user user-menu">
        <a href="#" class="nav-link" data-toggle="dropdown">
              <img src="{{ asset ('adminlte/dist/img/avatar_male.png') }}" class="user-image" alt="User Image">
              <span class="hidden-xs"> {{ Auth::user()->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-edit mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{url('#')}}" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> {{ __('Profile') }}
          </a>
          <div class="dropdown-divider"></div>
                <a href="{{ route('auth.logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="fa  fa-unlock mr-2"></i>  {{ __('Sign Out')  }}
                </a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->