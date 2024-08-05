import { ref } from 'vue';
import { defineStore } from 'pinia';
import { useRouter } from 'vue-router';
import customApi from '@/customApi';

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter();
  const tokenUser = ref(
    localStorage.getItem('token')
      ? JSON.parse(localStorage.getItem('token'))
      : null
  );
  const currentUser = ref(
    localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user'))
      : null
  );

  const isError = ref(false);
  const errMsg = ref('');

  const regisUser = async (inputData) => {
    try {
      isError.value = false;
      errMsg.value = '';
      const { name, email, password, password_confirmation } = inputData;

      const { data } = await customApi.post('/auth/register', {
        name: name,
        email: email,
        password: password,
        password_confirmation: password_confirmation,
      });

      const { token, user } = data;

      //store pinia
      tokenUser.value = token;
      currentUser.value = user;

      //localstorage
      localStorage.setItem('token', JSON.stringify(token));
      localStorage.setItem('user', JSON.stringify(user));
      // console.log(token, user);

      router.push({ name: 'Verification' });
      console.log('Berhasil Register');
    } catch (error) {
      isError.value = true;
      errMsg.value = error.response.data;
    }
  };

  const loginUser = async (inputData) => {
    try {
      // console.log(inputData);
      isError.value = false;
      errMsg.value = '';

      const { email, password } = inputData;
      const { data } = await customApi.post('/auth/login', {
        email: email,
        password: password,
      });

      const { token, user } = data;

      //store pinia
      tokenUser.value = token;
      currentUser.value = user;

      //localstorage
      localStorage.setItem('token', JSON.stringify(token));
      localStorage.setItem('user', JSON.stringify(user));
      // console.log(token, user);
      // console.log(user.role.name);
      if (user.role.name === 'User') {
        router.push({ name: 'Home' });
      } else {
        router.push({ name: 'HomeAdmin' });
      }
    } catch (error) {
      isError.value = true;
      errMsg.value = error.response.data.message;
      // console.log(error.response);
    }
  };

  const getMe = async () => {
    try {
      const { data } = await customApi.get('/me', {
        headers: { Authorization: `Bearer ${tokenUser.value}` },
      });

      const { user } = data;

      localStorage.setItem('user', JSON.stringify(user));
      currentUser.value = user;
    } catch (error) {
      console.log(error);
    }
  };

  const logoutUser = async () => {
    try {
      const response = await customApi.post('/auth/logout', null, {
        headers: { Authorization: `Bearer ${tokenUser.value}` },
      });

      //localstorage
      localStorage.removeItem('token');
      localStorage.removeItem('user');

      //store pinia
      tokenUser.value = '';
      currentUser.value = '';

      router.push({ name: 'Login' });
      console.log('Berhasil Logout');
    } catch (error) {
      console.log(error);
    }
  };

  const generateOtp = async () => {
    const { email } = currentUser.value;
    try {
      const { data } = await customApi.post(
        '/auth/generate-otp-code',
        {
          email,
        },
        {
          headers: { Authorization: `Bearer ${tokenUser.value}` },
        }
      );
      isError.value = true;
      errMsg.value = 'Generate OTP Code Berhasil Silahkan Check Email Anda';
      console.log(data);
    } catch (error) {
      console.log(error);
    }
  };
  const verifikasiAkun = async (otpCode) => {
    isError.value = false;
    errMsg.value = '';

    const { otp } = otpCode;
    try {
      const { data } = await customApi.post(
        '/auth/validate',
        {
          otp: otp,
        },
        {
          headers: { Authorization: `Bearer ${tokenUser.value}` },
        }
      );

      await getMe();

      isError.value = true;
      errMsg.value = 'Email Berhasil Di Verifikasi';
      router.push({ name: 'Profile' });
      console.log(data);
    } catch (error) {
      console.log(error);
    }
  };

  return {
    regisUser,
    tokenUser,
    isError,
    errMsg,
    loginUser,
    currentUser,
    getMe,
    logoutUser,
    generateOtp,
    verifikasiAkun,
  };
});
