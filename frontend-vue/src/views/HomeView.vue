<template>
  <div class="hero-container">
    <div class="hero-card">
      <h1>🛍️ Bienvenido a nuestra Tienda Virtual SPA</h1>
      <p>Disfruta de una navegación ultra rápida sin recargas de página. Explora los productos disponibles en tiempo real o gestiona el inventario desde el panel de control.</p>
      <div class="acciones">
        <router-link to="/catalogo" class="btn btn-primary">Ir al Catálogo público</router-link>
        <router-link to="/admin" class="btn btn-secondary">Administrar CRUD</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.hero-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
  padding: 20px;
}
.hero-card {
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  max-width: 650px;
  text-align: center;
}
h1 {
  color: #2c3e50;
  font-size: 28px;
  margin-bottom: 15px;
}
p {
  color: #666;
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 30px;
}
.acciones {
  display: flex;
  justify-content: center;
  gap: 15px;
}
.btn {
  padding: 12px 24px;
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  transition: opacity 0.2s;
}
.btn:hover {
  opacity: 0.9;
}
.btn-primary {
  background-color: #007bff;
  color: white;
}
.btn-secondary {
  background-color: #6c757d;
  color: white;
}
</style>