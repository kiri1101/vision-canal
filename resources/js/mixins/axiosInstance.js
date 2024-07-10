import axios from 'axios';
import NProgress from 'nprogress';

const axiosInstance = axios.create({});

axiosInstance.interceptors.request.use((config) => {
    NProgress.inc();
    return config;
});

axiosInstance.interceptors.response.use((response) => {
    NProgress.done();
    return response;
}, (error) => {
    NProgress.done();
    return Promise.reject(error);
});

export { axiosInstance }
