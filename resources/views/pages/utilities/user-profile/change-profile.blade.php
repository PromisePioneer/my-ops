@extends('layouts.template')
@section('page-title', 'Ubah Profile')
@section('content')

    @include('pages.utilities.user-profile.partials.header')
    <div x-data="updateProfile()">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 data-bs-target="#kt_account_profile_details" aria-expanded="true"
                 aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Profile Details</h3>
                </div>
            </div>
            <div id="kt_account_profile_details" class="collapse show">
                <div class="card-body border-top p-9">
                    <form id="updateProfilePicForm" @submit.prevent="updateProfilePic()">

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true"
                                     style="background-image: url({{ asset('assets/media/dummy/dummy-picture.png')}})">
                                    @if(isset(Auth::user()->profile_pic) && Auth::user()->profile_pic)
                                        <div class="image-input-wrapper w-125px h-125px"
                                             style="background-image: url({{ Storage::url(Auth::user()->profile_pic) }})"></div>
                                    @else
                                        <div class="image-input-wrapper w-125px h-125px"
                                             style="background-image: url({{ asset('assets/media/dummy/dummy-picture.png')}})"></div>
                                    @endif
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="profile_pic" accept=".png, .jpg, .jpeg"/>
                                        <input type="hidden" name="avatar_remove"/>
                                    </label>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        title="Remove avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama</label>

                            <div class="col-lg-8 fv-row">
                                <input type="text" class="form-control form-control-lg form-control-solid"
                                       value="{{ Auth::user()->name }}" disabled/>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span class="required">Cabang</span>
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                   title="Phone number must be active"></i>
                            </label>

                            <div class="col-lg-8 fv-row">
                                <input type="tel" class="form-control form-control-lg form-control-solid"
                                       value="{{ Auth::user()->branch->name }}" disabled/>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                    x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                 data-bs-target="#kt_account_signin_method">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Sign-in Method</h3>
                </div>
            </div>
            <div id="kt_account_signin_method" class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex flex-wrap align-items-center mb-10">
                        <div :class="open ? 'd-none' : ''">
                            <div class="fs-6 fw-bolder mb-1">Password</div>
                            <div class="fw-bold text-gray-600">************</div>
                        </div>
                        <div class="flex-row-fluid" x-show="open" x-transition>
                            <form id="formPassword" class="form" novalidate="novalidate"
                                  @submit.prevent="updatePassword()">
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="currentpassword" class="form-label fs-6 fw-bolder mb-3">
                                                Password Sekarang
                                            </label>
                                            <input type="password"
                                                   class="form-control form-control-lg form-control-solid"
                                                   name="current_password"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="newpassword" class="form-label fs-6 fw-bolder mb-3">
                                                Password Baru
                                            </label>
                                            <input type="password"
                                                   class="form-control form-control-lg form-control-solid"
                                                   name="password" id="newpassword"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label class="form-label fs-6 fw-bolder mb-3">Konfirmasi Password
                                                Baru</label>
                                            <input type="password"
                                                   class="form-control form-control-lg form-control-solid"
                                                   name="confirm_password"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text mb-5">Password must be at least 8 character and contain symbols
                                </div>
                                <div class="d-flex">
                                    <button type="submit"
                                            class="btn btn-primary me-2 px-6 btn-sm" :disabled="buttonLoading"
                                            x-text="buttonLoading ? 'Loading...' : 'Update Password'">
                                    </button>
                                    <button @click="openTabPassword()" type="button"
                                            class="btn btn-color-gray-400 btn-active-light-primary btn-sm px-6">Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div :class="open ? 'ms-auto d-none' : 'ms-auto'">
                            <button @click="openTabPassword()" class="btn btn-light btn-active-light-primary btn-sm">
                                Reset
                                Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection

@push('script')
    <script>
        function updateProfile() {
            return {
                buttonLoading: false,
                userId: "{{ Auth::id() }}",
                open: false,
                formPassword: document.getElementById('formPassword'),
                formProfilePic: document.getElementById('updateProfilePicForm'),
                openTabPassword() {
                    this.open = !this.open
                },
                async updatePassword() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/user-profile/update-password/${this.userId}`, new FormData(this.formPassword))
                        await showAlert('success', 'Data berhasil disimpan')
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async updateProfilePic() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/user-profile/update-profile/${this.userId}`, new FormData(this.formProfilePic))
                        await showAlert('success', 'Data berhasil di ubah').then(() => {
                            location.reload();
                        });
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
@endpush
