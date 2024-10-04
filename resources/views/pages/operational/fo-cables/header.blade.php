<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4  {{ request()->is('operational/fo-cables') ? 'active' : '' }}"
           href="{{ url('operational/fo-cables') }}"
           aria-selected="true" role="tab">Kabel FO</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link text-active-primary pb-4 {{ request()->is('operational/fo-cables-map') ? 'active' : '' }}"
           href="{{ url('operational/fo-cables-map') }}">
            Koordinat
        </a>
    </li>
</ul>