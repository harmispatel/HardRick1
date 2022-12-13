 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

    @php
        $urls = array();
        $urls[] = Request::segment(2);
        $urls[] = Request::segment(3);
        $url = array_filter($urls);
    @endphp

     <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('dashboard',$url)) ? 'active-tab' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid
                {{-- Icon Tab Class --}}
                {{ (in_array('dashboard',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->



     </ul>

 </aside>
 <!-- End Sidebar-->
