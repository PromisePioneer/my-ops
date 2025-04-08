@extends('layouts.template')
@section('page-title', 'Hirarki Jabatan')
@section('breadcrumbs', 'Manajemen Karyawan - Hirarki Jabatan')
@section('content')
    {{--    <div x-data="roleHierarchyData()">--}}
    {{--        <div>--}}
    {{--            <template x-for="role in roleHierarchy" :key="role.id">--}}
    {{--                <div>--}}

    {{--                    <div class="d-flex flex-center justify-content-center mb-10">--}}
    {{--                        <div class="card card-sm shadow-sm w-200px">--}}
    {{--                            <div class="card-body">--}}
    {{--                                <div class="d-flex flex-column">--}}
    {{--                                    <a href="#"--}}
    {{--                                       class="text-gray-800 text-center fw-bolder fs-4 text-hover-primary mb-1">--}}
    {{--                                        <span x-text="role.role_name"></span>--}}
    {{--                                    </a>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="d-flex  justify-content-around align-items-center">--}}
    {{--                        <template x-for="(role, index) in role.children" :key="index">--}}
    {{--                            <div class="card card-sm shadow-sm w-300px">--}}
    {{--                                <div class="card-body">--}}
    {{--                                    <div class="d-flex flex-column">--}}
    {{--                                        <a href="#"--}}
    {{--                                           class="text-gray-800 text-center fw-bolder fs-4 text-hover-primary mb-1">--}}
    {{--                                            <span x-text="role.role_name"></span>--}}
    {{--                                        </a>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </template>--}}
    {{--                    </div>--}}

    {{--                </div>--}}
    {{--            </template>--}}
    {{--        </div>--}}
    {{--    </div>--}}


    <div x-data="roleHierarchyData()">

        <div id="tree"></div>
    </div>

@endsection
@push('script')
    <script src="https://cdn.balkan.app/orgchart.js"></script>
    <script>
        function roleHierarchyData() {
            return {
                isLoading: false,
                roleHierarchy: [],
                async init() {
                    await this.getRoleHierarchyData();
                    new OrgChart("#tree", {
                        template: "olivia",
                        mode: 'light',
                        nodeBinding: {
                            field_0: "user_name",
                            field_1: "role_name",
                            img_0: "img" ?? "{{ asset('') }}" + 'assets/media/avatars/blank.png',
                        },
                        nodes: [
                            ...this.roleHierarchy
                        ]
                    });
                },
                async getRoleHierarchyData() {
                    try {
                        const resp = await axios.get('/manage-users/role-hierarchy/data');
                        this.roleHierarchy = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
