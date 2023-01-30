<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('images/logo_rsbkd.png') }}" alt="RS Bunda Surabaya" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ Config::get('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <!-- Tindakan Rawat Jalan -->
          <li class="nav-item ">
            @if($modul=='tindakan-ralan')
            <a href="{{ route('pendapatan.ralan.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.ralan.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Tindakan Rawat Jalan</p>
            </a>
          </li>

          <!-- Tindakan Rawat Inap -->
          <li class="nav-item">
            @if($modul=='tindakan-ranap')
            <a href="{{ route('pendapatan.ranap.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.ranap.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Tindakan Rawat Inap</p>
            </a>
          </li>

          <!-- Tindakan Operator -->
          <li class="nav-item">
            @if($modul=='tindakan-operator')
            <a href="{{ route('pendapatan.operator.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.operator.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Tindakan Operator</p>
            </a>
          </li>

          <!-- Tindakan Anestesi -->
          <li class="nav-item">
            @if($modul=='tindakan-anestesi')
            <a href="{{ route('pendapatan.anestesi.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.anestesi.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Tindakan Anestesi</p>
            </a>
          </li>
          
          <!--Grouper Ralan-->
          <li class="nav-item">
            @if($modul=='grouper-ralan')
            <a href="{{ route('pendapatan.grouperralan.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.grouperralan.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Grouper Ralan</p>
            </a>
          </li>
          
          <!--Grouper Ranap-->
          <li class="nav-item">
            @if($modul=='grouper-ranap')
            <a href="{{ route('pendapatan.grouperranap.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.grouperranap.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Grouper Ranap</p>
            </a>
          </li>

          <!-- Obat Rawat Jalan -->
          <li class="nav-item">
            @if($modul=='obat-ralan')
            <a href="{{ route('pendapatan.obatralan.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.obatralan.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Obat Rawat Jalan</p>
            </a>
          </li>
          
          <!-- Obat Rawat Jalan -->
          <li class="nav-item">
            @if($modul=='obat-inap')
            <a href="{{ route('pendapatan.obatinap.home') }}" class="nav-link active">
            @else
            <a href="{{ route('pendapatan.obatinap.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon fas fa-th"></i>
              <p>Obat Rawat Inap</p>
            </a>
          </li>
          
          <li class="nav-item">
            @if($modul=='radiology')
            <a href="{{ route('manager.radiology.home') }}" class="nav-link active">
            @else
            <a href="{{ route('manager.radiology.home') }}" class="nav-link">
            @endif
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Radiologi
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            @if($modul=='tindakan-obatbebas')
            <a href="{{ route('manager.obatbebas.home') }}" class="nav-link active">
            @else
            <a href="{{ route('manager.obatbebas.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Obat Bebas
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            @if($modul=='tindakan-obatkronis')
            <a href="{{ route('manager.obatkronis.home') }}" class="nav-link active">
            @else
            <a href="{{ route('manager.obatkronis.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Obat Kronis
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            @if($modul=='tindakan-nonkronis')
            <a href="{{ route('manager.nonkronis.home') }}" class="nav-link active">
            @else
            <a href="{{ route('manager.nonkronis.home') }}" class="nav-link">
            @endif  
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Obat Non Kronis
              </p>
            </a>
          </li>

          <li class="nav-item">
            @if($modul=='laborat')
            <a href="{{ route('manager.laborat.home') }}" class="nav-link active">
            @else
            <a href="{{ route('manager.laborat.home') }}" class="nav-link">
            @endif    
              <i class="nav-icon far fa-image"></i>
              <p>
                Laborat
              </p>
            </a>
          </li>


          <li class="nav-item">
              @if($modul=='medrec')
              <a href="{{ route('manager.medrec') }}" class="nav-link active">
              @else
              <a href="{{ route('manager.medrec') }}" class="nav-link">
              @endif  
              <i class="nav-icon fas fa-columns"></i>
              <p>
                  Rekam Medis
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>