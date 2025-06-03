<template>
  <div>

    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner"></div>
    </div>

    <div class="filters">

      <select v-model="filters.status" @change="fetchClients">
        <option value="">Todos</option>
        <option value="ativo">Ativos</option>
        <option value="inativo">Inativos</option>
      </select>

      <div class="search-container">
        <input v-model="searchTerm" placeholder="Buscar por nome, email ou CPF/CNPJ" @keyup.enter="handleSearch"
          required>
        <button id="buttonSearch" @click.prevent="handleSearch">Buscar</button>
        <span v-if="showEmptyError" class="error-message">
          Por favor, digite um termo para buscar !
        </span>
      </div>

    </div>

    <table>
      <thead>
        <tr>
          <th @click="sortBy('nome')">Nome</th>
          <th>CPF/CNPJ</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Status </th>
          <th @click="toggleSort"> Ações
            <span v-if="filters.sort === 'created_at'">
              {{ filters.order === 'asc' ? '⬇️' : '⬆️' }}
            </span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in clients" :key="client.id">
          <td>{{ client.nome }}</td>
          <td>{{ client.tipo_pessoa === 'física' ? formatCPF(client.cpf_cnpj) : formatCNPJ(client.cpf_cnpj) }}</td>
          <td>{{ client.email }}</td>
          <td>{{ formatPhone(client.telefone) }}</td>
          <td>{{ client.status }}</td>
          <td>
            <button @click="editClient(client.id)">Editar</button>
            <button @click="deleteClient(client.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button :disabled="pagination.current_page === 1" @click="fetchClients(pagination.current_page - 1)">
        Anterior
      </button>

      <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>

      <button :disabled="pagination.current_page === pagination.last_page"
        @click="fetchClients(pagination.current_page + 1)">
        Próxima
      </button>
    </div>

  </div>
</template>

<script>
import api from '@/services/api'
import { debounce } from 'lodash'
import { useToast } from 'vue-toastification'

export default {

  setup() {
    const toast = useToast()
    return { toast }
  },

  data() {
    return {
      loading: false,
      showEmptyError: false,
      clients: [],
      searchTerm: '',
      pagination: {
        current_page: 1,
        last_page: 1,
        total: 0
      },
      filters: {
        status: '',
        search: '',
        sort: 'created_at',
        order: 'desc'
      }
    }
  },

  mounted() {
    this.fetchClients()
  },

  methods: {

    toggleSort() {
      this.filters.order = this.filters.order === 'asc' ? 'desc' : 'asc';
      this.fetchClients();
    },

    handleSearch: debounce(function () {
      const term = this.searchTerm.trim();

      if (!term) {

        this.showEmptyError = true;
        setTimeout(() => this.showEmptyError = false, 2000);
        return;
      }

      const searchData = {};

      // 1. Verifica se é email (contém @)
      if (term.includes('@')) {
        searchData.email = term;
      }
      // 2. Verifica se é CPF/CNPJ (11 ou 14 dígitos)
      else if (/^\d{11}$/.test(term.replace(/\D/g, ''))) {
        searchData.cpf_cnpj = term.replace(/\D/g, '');
      }
      else if (/^\d{14}$/.test(term.replace(/\D/g, ''))) {
        searchData.cpf_cnpj = term.replace(/\D/g, '');
      }
      // 3. Assume que é nome por padrão
      else {
        searchData.nome = term;
      }

      console.log('Critério de busca:', searchData);
      this.executeSearch(searchData);
    }, 500),

    async executeSearch(searchData) {
      this.loading = true;
      try {
        const response = await api.searchClients(searchData);
        this.clients = response.data;
      } catch (error) {
        console.error('Erro na busca:', error);
      } finally {
        this.loading = false;
      }
    },

    async fetchClients(page = 1) {
      this.loading = true;
      try {

        const params = {
          status: this.filters.status || undefined,
          order: this.filters.order || 'desc',
          sort: this.filters.sort || 'created_at',
          page
        };

        const response = await api.getClients(params);
        console.log('Dados recebidos:', response.data);

        this.clients = response.data.data;
        this.pagination = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          total: response.data.total
        };
        
      } catch (error) {
        console.error('Erro ao buscar clientes:', error);

        this.$toast.error('Falha ao carregar clientes');
      } finally {
        this.loading = false;
      }
    },

    sortBy(column) {
      this.filters.sort = column
      this.filters.order = this.filters.order === 'asc' ? 'desc' : 'asc'
      this.fetchClients()
    },

    editClient(id) {
      this.$router.push({ name: 'client-edit', params: { id } })
    },

    async deleteClient(id) {
      if (confirm('Tem certeza que deseja excluir este cliente?')) {
        try {
          await api.deleteClient(id)
          this.toast.success('Cliente excluído com sucesso')
          this.fetchClients()
        } catch (error) {
          console.error('Erro ao excluir cliente:', error)
        }
      }
    },

    formatCPF(cpf) {
      if (!cpf) return 'Não informado';
      // Remove qualquer formatação existente
      const cleaned = cpf.toString().replace(/\D/g, '');
      // Aplica a formatação
      return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    },

    formatCNPJ(cnpj) {
      if (!cnpj) return 'Não informado';
      // Remove qualquer formatação existente
      const cleaned = cnpj.toString().replace(/\D/g, '');
      // Aplica a formatação
      return cleaned.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    },

    formatPhone(phone) {
      if (!phone) return 'Não informado'
      return phone.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3')
    },

    formatPhoneNumber(phone) {
      if (!phone) return ''
      const cleaned = phone.replace(/\D/g, '')
      if (cleaned.length === 11) {
        return `(${cleaned.substring(0, 2)}) ${cleaned.substring(2, 7)}-${cleaned.substring(7)}`
      }
      return phone
    }
   
  },

  
}
</script>

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.loading-spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin .5s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

button {
  padding: 0.5rem 1rem;
  margin: 2 0.25rem;
  border: none;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  min-width: 80px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button:first-of-type {
  background-color: #2563eb;

  color: white;
}

button:last-of-type {
  background-color: #dc2626;
  color: white;
}

.filters {
  display: flex;
  gap: 1rem;
  width: 700px;
  margin-bottom: 1.5rem;
  align-items: center;
}

.filters input {
  padding: 0.625rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.875rem;
  width: 100%;
  transition: all 0.3s ease;
  background-color: #ffffff;

}

.filters input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

.filters input::placeholder {
  color: #94a3b8;
}

.filters select {
  padding: 0.625rem 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.875rem;
  background-color: #ffffff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.filters select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
}

.search-container {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  margin-top: 20px;
  width: 700px;
}

#buttonSearch {
  background-color: #2563eb;
}

input {
  flex: 1;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

.error-border {
  border-color: #ff4444 !important;
  box-shadow: 0 0 0 2px rgba(255, 68, 68, 0.2) !important;
}

.error-message {
  color: #ff4444;
  font-size: 0.8rem;
  margin-top: 0.8rem;
  display: block;
}

#buttonSearch:disabled {
  background-color: #cccccc !important;
  cursor: not-allowed;
  opacity: 0.7;
}

.pagination {
  display: flex;
  gap: 1rem;
  justify-content: center;
  align-items: center;
  margin-top: 1.5rem;
}
</style>