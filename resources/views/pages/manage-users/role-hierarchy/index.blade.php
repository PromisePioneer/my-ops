@extends('layouts.template')
@section('page-title', 'Hirarki Jabatan')
@section('breadcrumbs', 'Manajemen Karyawan - Hirarki Jabatan')
@section('content')
    <div x-data="roleHierarchyData()">
        <div id="tree"></div>
    </div>

@endsection
@push('script')
    <script src="{{ url('assets/js/custom/orgchart/orgchart.js') }}"></script>
    <script>
        function roleHierarchyData() {
            return {
                isLoading: false,
                roleHierarchy: [],
                async init() {
                    await this.getRoleHierarchyData();
                    const chart = new OrgChart(document.getElementById('tree'), {
                        template: "ana",
                        mode: 'light',
                        layout: OrgChart.tree,
                        mouseScrool: OrgChart.none,
                        nodeMouseClick: OrgChart.action.edit,
                        nodeMenu: {
                            edit: {text: "Edit"},
                            add: {text: "Add"},
                            remove: {text: "Remove"}
                        },
                        menu: {
                            pdf: {text: "Export PDF"},
                        },
                        toolbar: {
                            layout: true,
                            fit: true,
                            expandAll: false
                        },
                        nodeBinding: {
                            field_0: "role_name",
                        },
                    });


                    chart.on('init', function (sender) {
                        sender.config.editForm.addMore = null;
                    });


                    chart.load([
                        ...this.roleHierarchy
                    ])

                    OrgChart.templates.myTemplate = Object.assign({}, OrgChart.templates.polina);
                    OrgChart.templates.myTemplate.size = [100, 100];
                    chart.on('init', function (sender) {
                        sender.toolbarUI.showLayout();
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
