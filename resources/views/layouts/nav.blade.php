<header id="header">
    <div class="container-fluid">
        <div id="logo" class="pull-left">
            <a href="{{ route('principal') }}"><img src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" alt="grupoflesan.png"  title="GRUPO FLESAN | Confianza que Construye" /></a>
        </div>
        <nav id="nav-menu-container">
            <ul class="nav-menu">
                <li class="menu-has-children" id="nav-ad"><a href="{{ route('principal') }}"> Checklist Semanal</a>
                </li>
                <li class="menu-has-children" id="nav-gpreguntas"><a href="{{ route('questions') }}"> Gestión Preguntas</a>
                <li class="menu-has-children" id="nav-guser"><a href="{{ route('gestion_user') }}"> Gestión Usuarios</a>
                <li class="menu-has-children"><a href="#">{{ Auth::user()->name }}</a>
                    <ul>
                        <li>
                            <a href="{{ route('logout') }}" class="btn btn-danger btn-block" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="color:#ffff">Cerrar Sesion</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>  
                        </li>
                    </ul>
                </li>
            </ul>
        </nav><!-- #nav-menu-container -->
    </div>
</header>