import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',  
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

api.interceptors.request.use(config => {   
  return config
})

api.interceptors.response.use(response => {  
  return response
}, error => {
  console.error('Erro na requisição:', error.response?.data || error.message)
  return Promise.reject(error)
})

export default {
  getClients(params) {
    return api.get('/clientes', { params })  
  },
  getClient(id) {
    return api.get(`/clientes/${id}`)
  },
  createClient(data) {
    console.log('Dados do cliente:', data) 
    return api.post('/clientes', data)
  },
  updateClient(id, data) {
    return api.put(`/clientes/${id}`, data)
  },
  deleteClient(id) {
    return api.delete(`/clientes/${id}`)
  },
  getProfessions() {
    return api.get('/professions')
  },
  searchClients(data) {  
    return api.post('/clientes-search', data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
  }
}