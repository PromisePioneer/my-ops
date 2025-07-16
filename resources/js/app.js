import './bootstrap.js'
import Alpine from 'alpinejs'
import Swal from 'sweetalert2'
import sort from '@alpinejs/sort'


window.Alpine = Alpine;
window.Swal = Swal;

Alpine.plugin(sort)
Alpine.start()
