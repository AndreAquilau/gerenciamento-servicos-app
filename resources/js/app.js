require('./bootstrap');
//require('dotenv').config();

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import RadioButton from 'primevue/radiobutton';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import DataTable from 'primevue/datatable';
import Toolbar from 'primevue/toolbar';
import FileUpload from 'primevue/fileupload';
import PrimeVue from 'primevue/config';
import Dialog from 'primevue/dialog';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import ToggleButton from 'primevue/togglebutton';
import Checkbox from 'primevue/checkbox';
import Dropdown from 'primevue/dropdown';
import DataView from 'primevue/dataview';
import InputMask from 'primevue/inputmask';
import Password from 'primevue/password';
import MultiSelect from 'primevue/multiselect';
import CascadeSelect from 'primevue/cascadeselect';
import Chips from 'primevue/chips';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Card from 'primevue/card';
import Editor from 'primevue/editor';
import Chart from 'primevue/chart';

import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';


const el = document.getElementById('app');

const app = createApp({
    render: () =>
    h(InertiaApp, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}`).default,
    }),
});

app.mixin({ methods: { route } })
app.use(InertiaPlugin)
app.use(PrimeVue);
app.use(ToastService);
app.component('Dialog', Dialog);
app.component('InputMask', InputMask);
app.component('Button', Button);
app.component('ToggleButton', ToggleButton);
app.component('InputNumber', InputNumber);
app.component('RadioButton', RadioButton);
app.component('InputText', InputText);
app.component('Toast', Toast);
app.component('Textarea', Textarea);
app.component('Column', Column);
app.component('Rating', Rating);
app.component('DataTable', DataTable);
app.component('FileUpload', FileUpload);
app.component('Toolbar', Toolbar);
app.component('CheckBox', Checkbox);
app.component('Dropdown', Dropdown);
app.component('DataView', DataView);
app.component('Password', Password);
app.component('MultiSelect', MultiSelect);
app.component('CascadeSelect', CascadeSelect);
app.component('Chips', Chips);
app.component('Calendar', Calendar);
app.component('Card', Card);
app.component('AutoComplete', AutoComplete);
app.component('Editor', Editor);
app.component('Chart', Chart);

app.mount(el);

InertiaProgress.init({ color: '#4B5563' });
