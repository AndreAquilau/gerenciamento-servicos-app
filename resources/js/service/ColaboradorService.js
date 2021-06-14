import axios from 'axios';
import api from '../service/api.service';


export default class ColaboradorService {

    getProductsSmall() {
		return api.get('colaboradores').then(res => res.data.data);
	}

	getColaboradores() {
		return axios.get('http://localhost:8000/api/colaboradores').then(res => res.data.data);
    }

    getProductsWithOrdersSmall() {
        return axios.get('http://localhost:8000/api/colaboradores').then(res => res.data.data);
	}
}
