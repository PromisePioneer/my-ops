<div class="card mb-6">
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    @if(isset(Auth::user()->profile_pic) && Auth::user()->profile_pic)
                        <img src="{{ Storage::url(Auth::user()->profile_pic) }}" alt="image"/>
                    @else
                        <img src="{{ asset('assets/media/dummy/dummy-picture.png') }}" alt="image"/>
                    @endif
                    <div
                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px"></div>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <a href="#"
                               class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">{{ Auth::user()->name }}</a>
                            <a href="#">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
																	<svg xmlns="http://www.w3.org/2000/svg" width="24px"
                                                                         height="24px" viewBox="0 0 24 24">
																		<path
                                                                                d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                                                fill="#00A3FF"/>
																		<path class="permanent"
                                                                              d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                                              fill="white"/>
																	</svg>
																</span>
                            </a>
                        </div>

                        <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                            <a href="#"
                               class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                       <i class="bi bi-person-square"></i>
                                    </span>
                                {{ collect(Auth::user()->getRoleNames())->implode('')}}</a>
                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <i class="bi bi-envelope-open-fill"></i>
                                    </span>
                                {{ Auth::user()->email }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex overflow-auto h-55px">
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 {{ Request::segment(3) === 'profile-detail' ? 'active' : '' }}"
                       href="{{ url('utility/user-profile/profile-detail') }}">Detail Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 {{ Request::segment(3) === 'identity-information' ? 'active' : '' }}"
                       href="{{ url('utility/user-profile/identity-information') }}">Informasi Identitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 {{ Request::segment(3) === 'job-information' ? 'active' : '' }}"
                       href="{{ url('utility/user-profile/job-information') }}">Informasi Pekerjaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 {{ Request::segment(3) === 'notification-detail' ? 'active' : '' }}"
                       href="{{ url('utility/user-profile/notification-detail') }}">Aktifitas</a>
                </li>
            </ul>
        </div>
    </div>
</div>
