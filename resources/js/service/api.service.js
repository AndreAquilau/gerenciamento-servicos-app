import axios from "axios";

const api = axios.create({
    //baseURL: `http://app-service-management.herokuapp.com/api/`,
    baseURL: 'http://localhost:8000/api/'
});

export default api;
