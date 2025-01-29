<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3 border-bottom pb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-soft-ui-dashboard">Online Builder</a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link text-body font-weight-bold px-0" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Sign Out</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    @if(auth()->user()->role == USER_ROLE_TEACHER)
    <div class="fullscreen-popup d-none">
        <div class="popup-content">
            <div class="text-center mt-3">
                <span class="text-dark">{{__('Confirm You Are Active Now')}}</span>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2 teacher-active-btn">{{__('Active')}}</button>
            </div>
        </div>
    </div>
    <input type="hidden" id="teacher-active-check" value="{{ route('teacher.active-check') }}">
    <input type="hidden" id="teacher-active-route" value="{{ route('teacher.active-route') }}">
    @endif

</nav>
<!-- End Navbar -->