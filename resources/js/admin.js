import { createApp } from 'vue';
import AdminDashboard from './Pages/AdminDashboard.vue';

const element = document.getElementById('admin-app');

if (element) {
    const props = JSON.parse(element.dataset.props ?? '{}');

    createApp(AdminDashboard, props).mount(element);
}
