import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import 'admin-lte/dist/js/adminlte.min';
import 'alpinejs/dist/cdn.min';
import Swal from 'sweetalert2/dist/sweetalert2.min';
import 'select2/dist/js/select2.full.min';
import 'datatables.net/js/dataTables.min';
import 'datatables.net-bs5/js/dataTables.bootstrap5.min';
import 'datatables.net-responsive-bs5/js/responsive.bootstrap5.min';
import '@fortawesome/fontawesome-free/js/fontawesome.min';
import 'moment/min/moment.min';
import './jsvalidation.min';

window.$ = $;
window.jQuery = $;
window.bootstrap = bootstrap;
window.Swal = Swal;
window.moment = moment;
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
