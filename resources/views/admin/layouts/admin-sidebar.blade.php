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
        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Chronic-diseases',$url)) ? 'active-tab' : '' }}" href="{{ route('Chronic-disease') }}">
                <i class="fa fa-user-secret
                {{-- Icon Tab Class --}}
                {{ (in_array('Chronic-diseases',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Chronic diseases') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('blood-type',$url)) ? 'active-tab' : '' }}" href="{{ route('blood-type') }}">
                <i class="bi bi-droplet-fill"
                {{-- Icon Tab Class --}}
                {{ (in_array('blood-type',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Blood Type') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Drags-allergy',$url)) ? 'active-tab' : '' }}" href="{{ route('Drags-allergy') }}">
                <i class="bi bi-justify"
                {{-- Icon Tab Class --}}
                {{ (in_array('Drags-allergy',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Drags Allergy') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Food-allergy',$url)) ? 'active-tab' : '' }}" href="{{ route('Food-allergy') }}">
                <i class="fa fa-cutlery"
                {{-- Icon Tab Class --}}
                {{ (in_array('Food-allergy',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Food Allergy') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Specialist',$url)) ? 'active-tab' : '' }}" href="{{ route('Specialist') }}">
                <i class="fa fa-user-plus"
                {{-- Icon Tab Class --}}
                {{ (in_array('Specialist',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Specialist') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->


        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Subscription',$url)) ? 'active-tab' : '' }}" href="{{ route('Subscription') }}">
                <i class="bi bi-cash"
                {{-- Icon Tab Class --}}
                {{ (in_array('Subscription',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Subscription Plan') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link
            {{-- Active Tab Class --}}
            {{ (in_array('Hospital',$url)) ? 'active-tab' : '' }}" href="{{ route('Hospital') }}">
                <i class="bi bi-hospital"
                {{-- Icon Tab Class --}}
                {{ (in_array('Hospital',$url)) ? 'icon-tab' : '' }}"></i>
                <span>{{ __('Hospital') }}</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->




     </ul>

 </aside>
 <!-- End Sidebar-->
