<script setup>
import { ref, onMounted } from 'vue' 
import axios from 'axios'

// Usaremos 127.0.0.1 para asegurar la conexión local en el navegador
const API_URL = 'http://localhost:8000/api/books' 

const books = ref([])
const loading = ref(true)
const error = ref(null)

const fetchBooks = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get(API_URL)
    books.value = response.data
  } catch (err) {
    console.error("Error conectando a Symfony:", err)
    // Mostramos un mensaje más informativo para el usuario
    error.value = "Error al conectar con Symfony. Revisa la consola y que Symfony esté corriendo."
  } finally {
    loading.value = false
  }
}

onMounted(fetchBooks)
</script>

<template>
  <div class="app-container">
    <header>
      <h1>Catálogo de Libros</h1>
      <button @click="fetchBooks" class="refresh-btn"> Refrescar</button>
    </header>

    <div v-if="loading" class="state-msg">Cargando libros...</div>
    <div v-else-if="error" class="state-msg error">{{ error }}</div>

    <div v-else class="list-container">
      <div v-for="book in books" :key="book.id" class="book-card">
        <div class="info">
          <h3>{{ book.title }}</h3>
          <p class="author">Autor: {{ book.author }} ({{ book.published_year }})</p> 
        </div>
        <div class="rating">
           {{ book.average_rating ? Number(book.average_rating).toFixed(1) : 'N/A' }}
        </div>
      </div>
      <p v-if="books.length === 0" class="empty-list">No hay libros registrados.</p>
    </div>
  </div>
</template>

<style scoped>
.app-container { max-width: 800px; margin: auto; padding: 2rem; font-family: sans-serif; }
header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; margin-bottom: 1rem; }
h1 { font-size: 1.8rem; }
.refresh-btn { padding: 0.5rem 1rem; cursor: pointer; background: #4f46e5; color: white; border: none; border-radius: 4px; transition: background 0.3s; }
.book-card { display: flex; justify-content: space-between; padding: 1rem; border: 1px solid #eee; margin-bottom: 0.75rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); background-color: white; }
.info h3 { margin-top: 0; font-size: 1.1rem; }
.author { color: #666; font-size: 0.95rem; margin-top: 5px; }
.rating { font-weight: bold; color: #d97706; background: #fef3c7; padding: 0.5rem; border-radius: 4px; align-self: center; }
.error { color: #e74c3c; font-weight: bold; }
.state-msg { text-align: center; margin-top: 2rem; }
.empty-list { text-align: center; color: #999; margin-top: 2rem; }
</style>