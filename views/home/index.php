<?php require_once 'views/templates/header.php'; ?>

<header class="hero">
    <div class="hero-content">
        <h1>Bienvenido a <span>Guau</span></h1>
        <p>La tienda favorita de tu mejor amigo 🐶🐱</p>
        <a href="<?= BASE_URL ?>/index.php?page=products" class="cta-btn">Explora Productos</a>
    </div>
    <img src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=500&q=80" alt="Perro feliz" class="hero-img">
</header>

<main class="inicio-main">
    <section class="features">
        <h2>¿Por qué elegirnos?</h2>
        <div class="features-list">
            <div class="feature">
                <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f436.png" alt="Perro" width="56" height="56">
                <h3>Cuidado Especializado</h3>
                <p>Productos seleccionados para la salud y felicidad de tus mascotas.</p>
            </div>
            <div class="feature">
                <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f431.png" alt="Gato" width="56" height="56">
                <h3>Atención personalizada</h3>
                <p>Asesoría profesional para elegir lo mejor para tu compañero peludo.</p>
            </div>
            <div class="feature">
                <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f4e6.png" alt="Caja regalo" width="56" height="56">
                <h3>Envíos rápidos</h3>
                <p>Recibe tu pedido en la puerta de tu casa en tiempo récord.</p>
            </div>
        </div>
    </section>

    <section class="destacados">
        <h2>Productos Destacados</h2>
        <div class="producto-lista">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="producto">
                <?php if (!empty($product['imagen'])): ?>
                    <img src="<?= BASE_URL ?>/assets/img/products/<?= htmlspecialchars($product['imagen']) ?>" 
                         alt="<?= htmlspecialchars($product['nombre']) ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/300x200.png?text=No+Image" 
                         alt="Sin imagen">
                <?php endif; ?>
                <div>
                    <h4><?= htmlspecialchars($product['nombre']) ?></h4>
                    <p><?= htmlspecialchars($product['descripcion']) ?></p>
                    <span class="precio">$<?= number_format($product['precio'], 2) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'views/templates/footer.php'; ?> 