<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4  {{ request()->is('operational/poles') ? 'active' : '' }}"
           href="{{ url('operational/poles') }}"
           aria-selected="true" role="tab">Data Tiang</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4 {{ request()->is('operational/poles-map') ? 'active' : '' }}"
           href="{{ url('operational/poles-map') }}">
            Koordinat
        </a>
    </li>
</ul>