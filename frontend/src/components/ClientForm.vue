<template>
  <form @submit.prevent="handleSubmit">
    <div v-if="loading" class="loading-overlay">
      <div class="loading-spinner"></div>
    </div>

    <!-- Passo 1: Dados Pessoais -->
    <div v-if="currentStep === 1">
      <h2>Dados Pessoais</h2>

      <div class="form-group">
        <label>Nome:</label>
        <input v-model="form.nome" type="text" maxlength="255" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.nome">{{ errors.nome }}</span>
      </div>

      <div class="form-group">
        <label>Data de Nascimento:</label>
        <input v-model="form.data_nascimento" type="date" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.data_nascimento">{{ errors.data_nascimento }}</span>
      </div>

      <div class="form-group">
        <label>Tipo Pessoa:</label>
        <select v-model="form.tipo_pessoa">
          <option value="fisica">Física</option>
          <option value="juridica">Jurídica</option>
        </select>
      </div>

      <div class="form-group">
        <label>{{ form.tipo_pessoa === 'fisica' ? 'CPF' : 'CNPJ' }}:</label>
        <input v-model="form.cpf_cnpj" @blur="applyCpfCnpjMask" @input="removeCpfCnpjMask" type="text"
          :maxlength="form.tipo_pessoa === 'fisica' ? 14 : 18" />
        <span class="error-message" v-if="errors.cpf_cnpj">{{ errors.cpf_cnpj }}</span>
      </div>

      <div class="form-group">
        <label>Email:</label>
        <input v-model="form.email" type="email" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.email">{{ errors.email }}</span>
      </div>

      <div class="form-group">
        <label>Telefone:</label>
        <input v-model="form.telefone" type="tel" placeholder="(XX) XXXXX-XXXX" @input="formatTelefone"
          maxlength="15" />
        <span class="error-message" v-if="errors.telefone">{{ errors.telefone }}</span>
      </div>

      <button type="button" @click="nextStep">Próximo</button>
    </div>

    <!-- Passo 2: Endereço -->
    <div v-if="currentStep === 2">
      <h2>Endereço</h2>

      <div class="form-group">
        <label>Logradouro:</label>
        <input v-model="form.endereco.endereco" type="text" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.endereco.endereco">{{ errors.endereco.endereco }}</span>
      </div>

      <div class="form-group">
        <label>Número:</label>
        <input v-model="form.endereco.numero" type="text" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.endereco.numero">{{ errors.endereco.numero }}</span>
      </div>

      <div class="form-group">
        <label>Bairro:</label>
        <input v-model="form.endereco.bairro" type="text" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.endereco.bairro">{{ errors.endereco.bairro }}</span>
      </div>

      <div class="form-group">
        <label>Complemento:</label>
        <input v-model="form.endereco.complemento" type="text" />
      </div>

      <div class="form-group">
        <label>Cidade:</label>
        <input v-model="form.endereco.cidade" type="text" @blur="validateRequiredFields" />
        <span class="error-message" v-if="errors.endereco.cidade">{{ errors.endereco.cidade }}</span>
      </div>

      <div class="form-group">
        <label>UF:</label>
        <select v-model="form.endereco.uf" @change="validateRequiredFields">
          <option v-for="uf in ufs" :value="uf" :key="uf">{{ uf }}</option>
        </select>
        <span class="error-message" v-if="errors.endereco.uf">{{ errors.endereco.uf }}</span>
      </div>

      <button type="button" @click="prevStep">Voltar</button>
      <button type="button" @click="nextStep">Próximo</button>
    </div>

    <!-- Passo 3: Profissão -->
    <div v-if="currentStep === 3">
      <h2>Profissão</h2>

      <div class="form-group">
        <label>Profissão:</label>
        <select v-model="form.id_profissao">
          <option value="">Selecione uma profissão</option>
          <option v-for="profession in professions" :value="profession.id" :key="profession.id">
            {{ profession.nome_profissao }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Status:</label>
        <select v-model="form.status">
          <option value="ativo">Ativo</option>
          <option value="inativo">Inativo</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="button" @click="prevStep">Voltar</button>
        <button type="submit" :disabled="loading">
          <span v-if="!loading">Salvar</span>
          <span v-else>Salvando...</span>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import api from '@/services/api'
import { useToast } from 'vue-toastification'

export default {
  setup() {
    const toast = useToast()
    return { toast }
  },

  data() {
    return {
      loading: false,
      currentStep: 1,
      form: {
        nome: '',
        data_nascimento: '',
        tipo_pessoa: 'fisica',
        cpf_cnpj: '',
        email: '',
        telefone: '',
        status: 'ativo',
        id_profissao: null,
        endereco: {
          endereco: '',
          numero: '',
          bairro: '',
          complemento: '',
          cidade: '',
          uf: 'SP'
        }
      },
      errors: {
        nome: '',
        data_nascimento: '',
        cpf_cnpj: '',
        email: '',
        telefone: '',
        endereco: {
          endereco: '',
          numero: '',
          bairro: '',
          cidade: '',
          uf: ''
        }
      },
      professions: [],
      ufs: ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO']
    }
  },

  props: {
    clientId: {
      type: [Number, String],
      default: null
    }
  },

  computed: {
    hasErrors() {
      return Object.values(this.errors).some(error =>
        typeof error === 'string' ? error : Object.values(error).some(e => e)
      )
    }
  },

  methods: {
    removeCpfCnpjMask(event) {
      this.form.cpf_cnpj = event.target.value.replace(/\D/g, '')
    },

    applyCpfCnpjMask() {
      const value = this.form.cpf_cnpj.replace(/\D/g, '')

      if (this.form.tipo_pessoa === 'fisica') {
        // Máscara CPF: 000.000.000-00
        this.form.cpf_cnpj = value
          .replace(/^(\d{3})(\d)/, '$1.$2')
          .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
          .replace(/\.(\d{3})(\d)/, '.$1-$2')
      } else {
        // Máscara CNPJ: 00.000.000/0000-00
        this.form.cpf_cnpj = value
          .replace(/^(\d{2})(\d)/, '$1.$2')
          .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
          .replace(/\.(\d{3})(\d)/, '.$1/$2')
          .replace(/(\d{4})(\d)/, '$1-$2')
      }

      this.validateRequiredFields()
    },

    formatTelefone(event) {
      let value = event.target.value.replace(/\D/g, '')
      if (value.length > 11) value = value.substring(0, 11)
      if (value.length > 0) {
        value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7, 11)}`
      }
      this.form.telefone = value
    },

    validateRequiredFields() {
      this.clearErrors()
      let isValid = true

      if (this.currentStep === 1) {
        if (!this.form.nome.trim()) {
          this.errors.nome = 'Nome é obrigatório'
          isValid = false
        }
        if (!this.form.data_nascimento) {
          this.errors.data_nascimento = 'Data de nascimento é obrigatória'
          isValid = false
        }
        if (!this.form.cpf_cnpj) {
          this.errors.cpf_cnpj = 'CPF/CNPJ é obrigatório'
          isValid = false
        }
        if (!this.form.email) {
          this.errors.email = 'Email é obrigatório'
          isValid = false
        }
        if (!this.form.telefone) {
          this.errors.telefone = 'Telefone é obrigatório'
          isValid = false
        }
      } else if (this.currentStep === 2) {
        if (!this.form.endereco.endereco.trim()) {
          this.errors.endereco.endereco = 'Logradouro é obrigatório'
          isValid = false
        }
        if (!this.form.endereco.numero.trim()) {
          this.errors.endereco.numero = 'Número é obrigatório'
          isValid = false
        }
        if (!this.form.endereco.bairro.trim()) {
          this.errors.endereco.bairro = 'Bairro é obrigatório'
          isValid = false
        }
        if (!this.form.endereco.cidade.trim()) {
          this.errors.endereco.cidade = 'Cidade é obrigatória'
          isValid = false
        }
        if (!this.form.endereco.uf) {
          this.errors.endereco.uf = 'UF é obrigatória'
          isValid = false
        }
      }

      return isValid
    },

    clearErrors() {
      this.errors = {
        nome: '',
        data_nascimento: '',
        cpf_cnpj: '',
        email: '',
        telefone: '',
        endereco: {
          endereco: '',
          numero: '',
          bairro: '',
          cidade: '',
          uf: ''
        }
      }
    },

    async nextStep() {
      if (!this.validateRequiredFields()) {
        this.toast.warning('Preencha todos os campos obrigatórios')
        return
      }

      if (this.currentStep === 3) {
        await this.handleSubmit()
        return
      }

      this.currentStep++
    },

    prevStep() {
      this.currentStep--
    },

    async handleSubmit() {
      this.loading = true

      try {
        const payload = {
          nome: this.form.nome,
          data_nascimento: this.form.data_nascimento,
          tipo_pessoa: this.form.tipo_pessoa === 'fisica' ? 'física' : 'jurídica',
          cpf_cnpj: this.form.cpf_cnpj.replace(/\D/g, ''),
          email: this.form.email,
          telefone: this.form.telefone.replace(/\D/g, ''),
          endereco: this.form.endereco,
          id_profissao: Number(this.form.id_profissao),
          status: this.form.status
        }

        if (this.clientId) {
          await api.updateClient(this.clientId, payload)
          this.toast.success('Cliente atualizado com sucesso!')
        } else {
          await api.createClient(payload)
          this.toast.success('Cliente cadastrado com sucesso!')
          this.$router.push({ name: 'clients' })
        }
      } catch (error) {
        this.handleBackendErrors(error)
      } finally {
        this.loading = false
      }
    },

    handleBackendErrors(error) {
      if (error.response?.status === 422 && error.response.data.erros) {
        const backendErrors = error.response.data.erros

        this.clearErrors()

        for (const [field, messages] of Object.entries(backendErrors)) {
          if (field in this.errors) {
            this.errors[field] = messages[0]
          } else if (field.startsWith('endereco.')) {
            const enderecoField = field.replace('endereco.', '')
            if (enderecoField in this.errors.endereco) {
              this.errors.endereco[enderecoField] = messages[0]
            }
          }
        }

        // Determina para qual passo voltar baseado nos erros
        if (this.errors.nome || this.errors.cpf_cnpj || this.errors.email || this.errors.telefone) {
          this.currentStep = 1
        } else if (Object.values(this.errors.endereco).some(e => e)) {
          this.currentStep = 2
        }

        this.toast.error('Corrija os erros destacados no formulário')
      } else {
        this.toast.error(error.response?.data?.message || 'Erro ao processar a requisição')
      }
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

  async created() {
    this.loading = true
    try {
      this.professions = (await api.getProfessions()).data

      if (this.clientId) {
        const response = await api.getClient(this.clientId)
        const rawValue = response.data.cpf_cnpj.replace(/\D/g, '')
        const type = response.data.tipo_pessoa === 'física' ? 'fisica' : 'juridica'

        this.form = {
          ...response.data,
          tipo_pessoa: type,
          telefone: this.formatPhoneNumber(response.data.telefone),
          cpf_cnpj: type === 'fisica'
            ? rawValue.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
            : rawValue.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
        }
      }
    } catch (error) {
      this.toast.error('Erro ao carregar dados do cliente')
      console.error('Erro ao carregar dados:', error)
    } finally {
      this.loading = false
    }
  }
}

</script>

<style scoped>
h2 {
  font-size: 24px;
  margin-bottom: 20px;
}

form {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;

}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input,
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 20px;

}

input.invalid,
select.invalid {
  border-color: #ff3860;
}

.error-message {
  color: #ff3860;
  font-size: 12px;
  margin-top: 5px;
  display: block;
}

.form-actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}

.form-actions button {
  flex: 1;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  background: #007bff;
  color: white;
}

button:hover:not(:disabled) {
  opacity: 0.9;
}

button:disabled {
  background: #cccccc;
  cursor: not-allowed;
}

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

button:hover:not(:disabled) {
  opacity: 0.9;
}
</style>