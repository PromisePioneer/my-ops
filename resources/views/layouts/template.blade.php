@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <title>Mayatama Solusindo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8"/>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico')}}"/>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/image-lightbox.css') }}" rel="stylesheet">

    @vite(['resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>


<body id="kt_body" class="print-content-only header-tablet-and-mobile-fixed aside-enabled"
      data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on" x-data="notifications">


<div>
    <div class="lightbox" x-data="{lightboxOpen: false, imgSrc: ''}" x-show="lightboxOpen" x-transition.opacity
         @lightbox.window="lightboxOpen = true; imgSrc = $event.detail" x-cloak>
        <div class="lightbox-container">
            <div class="lightbox-content">
                <button class="lightbox-close" @click="lightboxOpen = false">&times;</button>
                <img :src="imgSrc" @click.away="lightboxOpen = false" class="lightbox-img">
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-root">

    <div class="page d-flex flex-row flex-column-fluid">
        @include('layouts.modal.accounting-period')
        @include('layouts.partials.aside')
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <div id="kt_header" style="" class="header align-items-stretch">
                <div class="header-brand">
                    <a href="{{ url()->current() }}">
                        <img alt="Logo" src="{{ asset('assets/media/logos/mayatama-logo-full.png')}}"
                             width="150px"/>
                    </a>
                    <div id="kt_aside_toggle"
                         class="btn btn-icon w-auto px-0 btn-active-color-primary aside-minimize"
                         data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                         data-kt-toggle-name="aside-minimize">
                        <span class="svg-icon svg-icon-1 me-n1 minimize-default">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none">
										<rect opacity="0.3" x="8.5" y="11" width="12" height="2" rx="1" fill="black"/>
										<path
                                            d="M10.3687 11.6927L12.1244 10.2297C12.5946 9.83785 12.6268 9.12683 12.194 8.69401C11.8043 8.3043 11.1784 8.28591 10.7664 8.65206L7.84084 11.2526C7.39332 11.6504 7.39332 12.3496 7.84084 12.7474L10.7664 15.3479C11.1784 15.7141 11.8043 15.6957 12.194 15.306C12.6268 14.8732 12.5946 14.1621 12.1244 13.7703L10.3687 12.3073C10.1768 12.1474 10.1768 11.8526 10.3687 11.6927Z"
                                            fill="black"/>
										<path opacity="0.5"
                                              d="M16 5V6C16 6.55228 15.5523 7 15 7C14.4477 7 14 6.55228 14 6C14 5.44772 13.5523 5 13 5H6C5.44771 5 5 5.44772 5 6V18C5 18.5523 5.44771 19 6 19H13C13.5523 19 14 18.5523 14 18C14 17.4477 14.4477 17 15 17C15.5523 17 16 17.4477 16 18V19C16 20.1046 15.1046 21 14 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H14C15.1046 3 16 3.89543 16 5Z"
                                              fill="black"/>
									</svg>
								</span>
                        <span class="svg-icon svg-icon-1 minimize-active">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none">
										<rect opacity="0.3" width="12" height="2" rx="1"
                                              transform="matrix(-1 0 0 1 15.5 11)" fill="black"/>
										<path
                                            d="M13.6313 11.6927L11.8756 10.2297C11.4054 9.83785 11.3732 9.12683 11.806 8.69401C12.1957 8.3043 12.8216 8.28591 13.2336 8.65206L16.1592 11.2526C16.6067 11.6504 16.6067 12.3496 16.1592 12.7474L13.2336 15.3479C12.8216 15.7141 12.1957 15.6957 11.806 15.306C11.3732 14.8732 11.4054 14.1621 11.8756 13.7703L13.6313 12.3073C13.8232 12.1474 13.8232 11.8526 13.6313 11.6927Z"
                                            fill="black"/>
										<path
                                            d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z"
                                            fill="#C4C4C4"/>
									</svg>
								</span>
                    </div>
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
                        <div class="btn btn-icon btn-active-color-primary w-30px h-30px"
                             id="kt_aside_mobile_toggle">
                            <span class="svg-icon svg-icon-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
											<path
                                                d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                                fill="black"/>
											<path opacity="0.3"
                                                  d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                                  fill="black"/>
										</svg>
									</span>
                        </div>
                    </div>
                </div>
                <div class="toolbar">
                    <div
                        class="container-fluid py-6 py-lg-0 d-flex flex-column flex-lg-row align-items-center justify-content-lg-between">
                        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                                @yield('page-title')
                            </h1>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                <li class="breadcrumb-item text-muted">
                                    <a href="#" class="text-muted text-hover-primary">
                                        @yield('breadcrumbs') </a>
                                </li>
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-accounting-period"
                                    x-text="`Periode Pembukuan : ${accountingPeriod}`">
                            </button>
                        </div>
                        <div class="d-flex align-items-center pt-lg-0">

                            <div class="d-flex align-items-center">
                                <div class="d-flex">
                                    <div class="d-flex align-items-center">
                                        <a href="#"
                                           class="btn btn-sm btn-icon btn-icon-muted btn-active-icon-primary"
                                           data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                                           data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                                            <i class="bi bi-bell-fill fs-1 position-relative">
                                                   <span
                                                       class="badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                       x-text="notifications.length"></span>

                                            </i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-800px"
                                             data-kt-menu="true">
                                            <div class="d-flex flex-column bgi-no-repeat rounded-top overflow"
                                                 style="background-image:url('{{ asset('assets/media/misc/pattern-1.jpg')}}'); background-size: cover">
                                                <h3 class="text-white fw-bold px-9 mt-10 mb-6">
                                                    <span class="fs-8 opacity-75 ps-3"
                                                          x-text="`${notifications.length} notifikasi yang belum dibaca`"></span>
                                                </h3>
                                                <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-bold px-9">
                                                    <li class="nav-item">
                                                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active"
                                                           data-bs-toggle="tab"
                                                           href="#kt_topbar_notifications_3">Notifikasi</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active"
                                                     id="kt_topbar_notifications_3"
                                                     role="tabpanel">
                                                    <div class="scroll-y mh-325px my-5 px-8">
                                                        <template x-for="row in notifications">
                                                            <div class="d-flex flex-stack py-4">
                                                                    <span class="w-100px badge badge-light-danger mr-4"
                                                                          x-html="differenceBetweenDate(row.data.due_date)">

                                                                    </span>
                                                                <a :href="`/transaction/invoice/preview/${row.data.invoice_id}`"
                                                                   class="text-gray-800 text-hover-primary fw-bold mr-100"
                                                                   x-html="row.data.message"></a>
                                                                <span
                                                                    class="badge badge-light fs-8 float-end"
                                                                    x-text="formatDate(row.created_at)"></span>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="py-3 text-center border-top">
                                                        <a href="{{ url('/utility/user-profile/notification-detail')}}"
                                                           class="btn btn-color-gray-600 btn-active-color-primary">View
                                                            All
                                                            <span class="svg-icon svg-icon-5">
																	<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                         height="24" viewBox="0 0 24 24" fill="none">
																		<rect opacity="0.5" x="18" y="13" width="13"
                                                                              height="2" rx="1"
                                                                              transform="rotate(-180 18 13)"
                                                                              fill="black"/>
																		<path
                                                                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                            fill="black"/>
																	</svg>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="post d-flex flex-column-fluid" id="kt_post">
                    <div id="kt_content_container" class="container-xxl">
                        @yield('content')
                        <div class="row g-5 g-xl-8">
                            <div class="col-xl-12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                <div
                    class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="text-dark order-2 order-md-1">
                        <span class="text-muted fw-bold me-1">2025©</span>
                        <a href="https://mayatama.id/" target="_blank" class="text-gray-800 text-hover-primary">
                            Mayatama Solusindo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.toast')
<script src="{{ asset('assets/plugins/custom/fslightbox/fslightbox.js')}}"></script>

<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>


<script>
    function notifications() {
        return {
            buttonLoading: false,
            notifications: [],
            accountingPeriod: null,
            formAccountingPeriod: document.getElementById('form-accounting-period'),
            modalAccountingPeriod: new bootstrap.Modal(document.getElementById('modal-accounting-period')),
            async init() {
                const notifications = await axios.get('/notifications');
                this.notifications = notifications.data;
                await this.getCurrentAccountingPeriod();
            },
            differenceBetweenDate(dueDate) {
                let dateNow = "{{ Carbon::now()->format('Y-m-d') }}"

                if (dateNow.charAt(2) === '-' && dueDate.charAt(2) === '-') {
                    dateNow = new Date(this.formatDate(dateNow));
                    dueDate = new Date(this.formatDate(dueDate));
                } else {
                    dateNow = new Date(dateNow);
                    dueDate = new Date(dueDate);
                }
                let timeDiff = Math.abs(dateNow.getTime() - dueDate.getTime());
                let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                return `${diffDays} Hari`
            },
            async getCurrentAccountingPeriod() {
                try {
                    const resp = await axios.get('/accounting-period-year');
                    this.accountingPeriod = resp.data;
                } catch (error) {
                    console.log(error)
                }
            },
            formatDate(val) {
                if (val) {
                    const date = new Date(val);
                    const formatter = new Intl.DateTimeFormat('en-US', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    return formatter.format(date);
                }
            },
            async saveAccountingPeriod() {
                this.buttonLoading = true;
                try {
                    await axios.post('/accounting-period-year/update', new FormData(this.formAccountingPeriod));
                    await showAlert('success', 'Data berhasil disimpan')
                    this.formAccountingPeriod.reset();
                    this.modalAccountingPeriod.hide();
                    await this.init();
                } catch (error) {
                    const respError = error.response.data.errors;
                    Object.keys(respError).map(err => toastr.error(respError[err][0]))
                } finally {
                    this.buttonLoading = false;
                }
            }
        }
    }
</script>
<script>
    const defaultThemeMode = "light";
    let themeMode;

    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }

        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
</script>


@stack('script')
</body>
</html>
