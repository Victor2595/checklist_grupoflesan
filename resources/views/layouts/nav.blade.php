<header id="header">
    <div class="container-fluid">
        <div id="logo" class="pull-left">
            <a href="{{ route('principal') }}"><img src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" alt="grupoflesan.png"  title="GRUPO FLESAN | Confianza que Construye" /></a>
        </div>
        <nav id="nav-menu-container">
            <ul class="nav-menu">
                <li class="menu-has-children" id="nav-ad"><a href="{{ route('principal') }}"> Checklist Semanal</a></li>
                @if(Auth::user()->rol[0]->id_rol == '10')
                <li class="menu-has-children" id="nav-gpreguntas"><a href="{{ route('questions') }}"> Gestión Preguntas</a>
                <li class="menu-has-children" id="nav-guser"><a href="{{ route('gestion_user') }}"> Gestión Usuarios</a>
                @endif
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                    @if(is_array(request()->session()->get('avatar')))
                        <img src="{{ asset(implode(request()->session()->get('avatar'))) }}" class="user-image" alt="User Image">
                    @else
                        <img src="{{ asset('img/login-icono.png') }}" class="user-image" alt="User Image">
                    @endif
                    </a>
                    <div class="dropdown-menu" style="position: absolute;right: 0;left: auto;border-top-right-radius: 0;border-top-left-radius: 0;padding: 1px 0 0 0;    border-color: #444242;;width: 280px;">
                        <div class="user-header" style="height: 175px;padding: 10px;text-align: center;-webkit-box-sizing: border-box;white-space: normal">
                            @if(is_array(request()->session()->get('avatar')))
                            <img src="{{ asset(implode(request()->session()->get('avatar'))) }}" class="img-circle" alt="User Image" style="z-index: 5;height: 90px;width: 90px;border: 3px solid;border-color: transparent;border-color: rgba(255,255,255,0.2);border-radius: 50%;vertical-align: middle;">
                            @else
                            <img src="{{ asset('img/login-icono.png') }}" class="img-circle" alt="User Image" style="z-index: 5;height: 90px;width: 90px;border: 3px solid;border-color: transparent;border-color: rgba(255,255,255,0.2);    border-radius: 50%;vertical-align: middle;">
                            @endif
                            <p style="z-index: 5;color: #000;font-size: 17px;margin-top: 10px;-webkit-box-sizing: border-box;display:block;text-transform: inherit;font-weight: inherit;padding-right: 0;padding-left: 0;"> {{  Auth::user()->name }}  - <span class="nombreSide" style="font-style: inherit;"><?php 
                                if(Auth::user()->rol[0]->id_rol == 10){
                                    $rol = 'Administrador Logística';
                                }else if(Auth::user()->rol[0]->id_rol == 10){
                                    $rol = 'Encargado Bodega';
                                }
                            ?>
                            <?php echo $rol; ?></span></p>
                        </div>
                        <div class="user-footer text-right" style="background-color: #dd4b39;padding: 10px;">
                            
                              <a href="{{ route('logout') }}" class="btn btn-default btn-flat" style="padding-top: 10px" onclick="event.preventDefault();document.getElementById('logout-form').submit();" ><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                        </div>
                    </div>
                </li>
            </ul>
        </nav><!-- #nav-menu-container -->
    </div>
</header>