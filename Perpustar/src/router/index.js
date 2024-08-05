import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import CategoryView from '@/views/CategoryView.vue';
import App from '@/App.vue';
import BookView from '@/views/BookView.vue';
import ProfileView from '@/views/ProfileView.vue';
import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';
import VerficationView from '@/views/auth/VerficationView.vue';
import { useAuthStore } from '@/stores/auth';
import DetailBookView from '@/views/DetailBookView.vue';
import DetailCategory from '@/views/DetailCategory.vue';
import HomeAdminView from '@/views/admin/HomeAdminView.vue';
import ProfileAdminView from '@/views/admin/ProfileAdminView.vue';
import CategoryAdminView from '@/views/admin/category/CategoryAdminView.vue';
import EditCategoryAdminView from '@/views/admin/category/EditCategoryAdminView.vue';
import BookAdminView from '@/views/admin/book/BookAdminView.vue';
import EditBookAdmin from '@/views/admin/book/EditBookAdmin.vue';
import BorrowAdminView from '@/views/admin/borrow/BorrowAdminView.vue';
import EditBorrowAdminView from '@/views/admin/borrow/EditBorrowAdminView.vue';
import RoleAdminView from '@/views/admin/role/RoleAdminView.vue';
import EditRoleAdminView from '@/views/admin/role/EditRoleAdminView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: App,
      children: [
        {
          path: '/',
          name: 'Home',
          component: HomeView,
        },
        {
          path: '/category',
          name: 'Category',
          component: CategoryView,
        },
        {
          path: '/category/:id',
          name: 'DetailCategory',
          component: DetailCategory,
        },
        {
          path: '/book',
          name: 'Book',
          component: BookView,
        },
        {
          path: '/book/:id',
          name: 'DetailBook',
          component: DetailBookView,
        },
        {
          path: '/profile',
          name: 'Profile',
          component: ProfileView,
          meta: { isAuth: true, isVerifikasi: true },
        },
        {
          path: '/register',
          name: 'Register',
          component: RegisterView,
          meta: { isAuthTrue: true },
        },
        {
          path: '/login',
          name: 'Login',
          component: LoginView,
          meta: { isAuthTrue: true },
        },
      ],
    },
    {
      path: '/verification',
      name: 'Verification',
      component: VerficationView,
      meta: { isAuth: true, isVerifikasiTrue: true },
    },
    {
      path: '/homeAdmin',
      name: 'HomeAdmin',
      component: HomeAdminView,
      meta: {
        isAuth: true,
        isVerifikasi: true,
        isAdminTrue: true,
      },
    },
    {
      path: '/profile-admin',
      name: 'ProfileAdmin',
      component: ProfileAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/category-admin',
      name: 'CategoryAdmin',
      component: CategoryAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/category-admin/:id/edit',
      name: 'EditCategoryAdmin',
      component: EditCategoryAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/book-admin',
      name: 'BookAdmin',
      component: BookAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/book-admin/:id/edit',
      name: 'EditBookAdmin',
      component: EditBookAdmin,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/borrow-admin',
      name: 'BorrowAdmin',
      component: BorrowAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/borrow-admin/:id/edit',
      name: 'EditBorrowAdmin',
      component: EditBorrowAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/role-admin',
      name: 'RoleAdmin',
      component: RoleAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
    {
      path: '/role-admin/:id/edit',
      name: 'EditRoleAdmin',
      component: EditRoleAdminView,
      meta: { isAuth: true, isVerifikasi: true, isAdminTrue: true },
    },
  ],
});

router.beforeEach(async (to, from) => {
  const authStore = await useAuthStore();
  if (to.meta.isAuthTrue && authStore.tokenUser) {
    alert('Anda Sudah Login');
    return {
      path: '/',
    };
  }
  if (to.meta.isVerifikasi && !authStore.currentUser.email_verified_at) {
    alert('Anda Belum Verifikasi');
    return {
      path: '/',
    };
  }
  if (to.meta.isVerifikasiTrue && authStore.currentUser.email_verified_at) {
    alert('Anda Sudah Verifikasi');
    return {
      path: '/',
    };
  }
  if (to.meta.isAuth && !authStore.tokenUser) {
    alert('Anda Belum Login');
    return {
      path: '/',
    };
  }
  if (
    to.meta.isAdminTrue &&
    authStore.tokenUser &&
    authStore.currentUser.role.name === 'User'
  ) {
    alert('Anda Bukan Admin');
    return {
      path: '/',
    };
  }
});

export default router;
