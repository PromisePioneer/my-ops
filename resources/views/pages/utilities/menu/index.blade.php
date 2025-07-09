@extends('layouts.template')
@section('page-title', 'Menu Management')
@section('breadcrumbs', 'Utilitas - Menu Management')
@section('content')
    <div x-data="menuData()">
        @include('pages.utilities.menu.form')
        <div class="row">
            <template x-for="menu in menus" :key="menu.id">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title" x-text="menu.name"></div>
                        </div>
                        <div class="card-body">
                            <ul :id="'sortable-' + menu.id" class="sortable-list" data-group="children">
                                <template x-for="childMenu in menu.children" :key="childMenu.id">
                                    <li class="sortable-item" :data-id="childMenu.id"
                                        :data-parent="menu.id">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div
                                                x-text="childMenu.name"></div>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-light-primary btn-sm me-2"
                                                        data-bs-target="#modal-menu" data-bs-toggle="modal"
                                                        @click="edit(childMenu.id)">
                                                    <x-icons.edit/>
                                                </button>
                                                <button class="btn btn-light-danger btn-sm">
                                                    <x-icons.trash/>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    @include('components.select2.script')
    @include('components.toast')
@endsection

@push('script')
    <!-- Include SortableJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        function menuData() {
            return {
                buttonLoading: false,
                menus: [],
                search: '',
                sortableInstances: [],
                permissions: [],
                editVal: '',
                form: document.getElementById('form-menu'),
                modal: new bootstrap.Modal(document.getElementById('modal-menu')),
                async init() {
                    await this.getMenuData();
                    await select2('.permissions-select2', 'Pilih Hak Akses', '/select2/permissions-data', false, false, null, true);
                    this.$nextTick(() => {
                        this.initializeSortable();
                    });
                },
                async getMenuData() {
                    try {
                        const resp = await axios.get('/utility/menus/data');
                        this.menus = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                initializeSortable() {
                    // Clear existing instances
                    this.sortableInstances.forEach(instance => instance.destroy());
                    this.sortableInstances = [];

                    // Initialize sortable for each menu
                    this.menus.forEach(menu => {
                        const container = document.getElementById('sortable-' + menu.id);
                        if (container) {
                            const sortable = new Sortable(container, {
                                group: 'children', // Same group allows cross-container dragging
                                animation: 150,
                                ghostClass: 'sortable-ghost',
                                chosenClass: 'sortable-chosen',
                                dragClass: 'sortable-drag',
                                onEnd: (evt) => {
                                    this.handleSortEnd(evt);
                                }
                            });
                            this.sortableInstances.push(sortable);
                        }
                    });
                },
                handleSortEnd(evt) {
                    const itemId = evt.item.dataset.id;
                    const oldParentId = evt.item.dataset.parent;
                    const newParentId = evt.to.dataset.group === 'children' ?
                        evt.to.id.replace('sortable-', '') : null;

                    if (newParentId && oldParentId !== newParentId) {
                        // Move item between parents
                        this.moveChildMenu(itemId, oldParentId, newParentId, evt.newIndex);
                    } else if (oldParentId === newParentId) {
                        // Reorder within same parent
                        this.reorderChildMenu(oldParentId, evt.oldIndex, evt.newIndex);
                    }
                },
                async moveChildMenu(childId, oldParentId, newParentId, newIndex) {
                    // Find the child menu item
                    const oldParent = this.menus.find(m => m.id == oldParentId);
                    const newParent = this.menus.find(m => m.id == newParentId);

                    if (oldParent && newParent) {
                        const childIndex = oldParent.children.findIndex(c => c.id == childId);
                        if (childIndex > -1) {
                            const [childMenu] = oldParent.children.splice(childIndex, 1);
                            childMenu.parent_id = newParentId; // Update parent reference
                            newParent.children.splice(newIndex, 0, childMenu);

                            // Update the data-parent attribute
                            document.querySelector(`[data-id="${childId}"]`).dataset.parent = newParentId;
                            await axios.post(`/utility/menus/move/${childId}/${oldParentId}/${newParentId}`);
                            window.location.reload();
                        }
                    }
                },
                async reorderChildMenu(parentId, oldIndex, newIndex) {
                    const parent = this.menus.find(m => m.id == parentId);
                    if (parent && parent.children) {
                        const [movedItem] = parent.children.splice(oldIndex, 1);
                        parent.children.splice(newIndex, 0, movedItem);
                        await axios.get(`/utility/menus/reorder/`, {
                            params: {
                                children: parent.children,
                            }
                        });
                        window.location.reload();
                        console.log(`Reordered child in parent ${parentId} from ${oldIndex} to ${newIndex}`);
                    }
                },
                async searchData() {
                    // Implementation for search functionality
                },
                async edit(id) {
                    try {
                        const resp = await axios.get(`/utility/menus/edit/${id}`);
                        this.editVal = resp.data;
                        await this.selectedPermissions(resp.data.id);
                    } catch (error) {
                        console.log(error);
                    }
                },
                async save(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/menus/store/${id}`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedPermissions(id) {
                    const selectedEl = $(`#selected-permission`);
                    const response = await axios.get(`/select2/selected-menu-permissions/${id}`);


                    response.data.forEach((permission) => {
                        const option = new Option(permission.name, permission.id, true, true);
                        selectedEl.append(option).trigger('change').trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    })
                }
            }
        }
    </script>
    <style>
        .sortable-list {
            min-height: 50px;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            list-style: none;
        }

        .sortable-list:empty::before {
            content: "Drop items here";
            color: #999;
            font-style: italic;
        }

        .sortable-item {
            background: #f8f9fa;
            padding: 8px 12px;
            margin: 4px 0;
            border-radius: 4px;
            cursor: move;
            border: 1px solid #dee2e6;
            transition: all 0.2s;
        }

        .sortable-item:hover {
            background: #e9ecef;
            border-color: #007bff;
        }

        .sortable-ghost {
            opacity: 0.5;
            background: #e9ecef;
        }

        .sortable-chosen {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .sortable-drag {
            opacity: 0.8;
            transform: rotate(5deg);
        }
    </style>
@endpush
