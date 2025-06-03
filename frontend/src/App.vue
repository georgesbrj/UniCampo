<template>
  <div id="app">
    <nav>
      <div class="nav-links">
        <router-link to="/">Clientes</router-link>
        <span>|</span>
        <router-link to="/clientes/novo">Novo Cliente</router-link>
      </div>

      <img src="@/assets/images/logo.png" alt="Logo" class="logo">
    </nav>
    <h1>Gerenciamento de clientes</h1>

    <router-view />
  </div>
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  margin: 20px;

}

nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 2rem;
  background-color: #f8f9fa;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logo-container {
  margin-right: 2rem;
}

.logo {
  height: 120px;
  width: auto;

}

.nav-links {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.nav-links a {
  font-weight: bold;
  color: #2c3e50;
  text-decoration: none;
}

.nav-links a.router-link-exact-active {
  color: #42b983;
}

h1 {
  margin: 1rem 0;
  color: #2c3e50;
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
  cursor: pointer;
}

button {
  margin: 5px;
  padding: 5px 10px;
  background: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background: #3aa876;
}
</style>