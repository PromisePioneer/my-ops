<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4  {{ request()->is('operational/joint-closures') ? 'active' : '' }}"
           href="{{ url('operational/joint-closures') }}"
           aria-selected="true" role="tab">Joint Closures</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4 {{ request()->is('operational/odp-map') ? 'active' : '' }}"
           href="{{ url('operational/odp-map') }}">
            Koordinat
        </a>
    </li>
</ul>