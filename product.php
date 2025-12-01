<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JW T-shirt Black - Premium streetwear van JW Shop">
    <title>JW T-shirt Black | JW Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  
    <header class="navbar">
        <div class="nav-logo">
            <a href="index.html">JW™</a>
        </div>
        <nav class="nav-links">
            <a href="index.html#new">NEW IN</a>
            <a href="index.html#men">MEN</a>
            <a href="index.html#women">WOMEN</a>
            <a href="index.html#eyewear">EYEWEAR</a>
            <a href="index.html#sneakers">SNEAKERS</a>
            <a href="index.html#kids">KIDS</a>
            <a href="index.html#brand">BRAND</a>
            <a href="index.html#sale" class="nav-sale">BLACK FRIDAY</a>
        </nav>
        <div class="nav-icons">
            <span class="icon" role="button" aria-label="Zoeken">🔍</span>
            <span class="icon" role="button" aria-label="Favorieten">🤍</span>
            <span class="icon" role="button" aria-label="Account">👤</span>
            <span class="icon" role="button" aria-label="Winkelwagen">🛒</span>
        </div>
    </header>


    <div class="breadcrumb">
        <a href="index.html">Home</a> / 
        <a href="index.html#men">Men</a> / 
        <span>JW T-shirt Black</span>
    </div>

  
    <div class="product-page">
        <div class="product-gallery">
            <img src="img/prod1.jpg" alt="JW T-shirt Black" class="product-big" id="mainImage">
            <div class="product-thumbnails">
                <img src="img/prod1.jpg" alt="View 1" class="thumbnail active" onclick="changeImage(this)">
                <img src="img/prod1.jpg" alt="View 2" class="thumbnail" onclick="changeImage(this)">
                <img src="img/prod1.jpg" alt="View 3" class="thumbnail" onclick="changeImage(this)">
                <img src="img/prod1.jpg" alt="View 4" class="thumbnail" onclick="changeImage(this)">
            </div>
        </div>

        <div class="product-info">
            <span class="product-badge">SALE -50%</span>
            <h1>JW T-shirt Black</h1>
            <div class="product-rating">
                ⭐⭐⭐⭐⭐ <span>(124 reviews)</span>
            </div>
            <p class="price-big">€120 <span class="old-price">€240</span></p>
            
            <p class="product-desc">
                High quality JW™ shirt. 100% premium cotton. Minimal streetwear style. 
                Perfect voor casual wear en een stijlvolle look.
            </p>

       
            <div class="size-selector">
                <label>Maat:</label>
                <div class="size-options">
                    <button class="size-btn">XS</button>
                    <button class="size-btn">S</button>
                    <button class="size-btn active">M</button>
                    <button class="size-btn">L</button>
                    <button class="size-btn">XL</button>
                    <button class="size-btn">XXL</button>
                </div>
            </div>

     
            <div class="quantity-selector">
                <label>Aantal:</label>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick="decreaseQty()">−</button>
                    <input type="number" id="quantity" value="1" min="1" max="10" readonly>
                    <button class="qty-btn" onclick="increaseQty()">+</button>
                </div>
            </div>

        
            <div class="product-actions">
                <button class="buy-btn">TOEVOEGEN AAN WINKELWAGEN</button>
                <button class="wishlist-btn">🤍 BEWAREN</button>
            </div>

          
            <div class="product-details">
                <details open>
                    <summary>Product Details</summary>
                    <ul>
                        <li>100% premium katoen</li>
                        <li>Oversized fit</li>
                        <li>Gewicht: 220g/m²</li>
                        <li>Machine wasbaar op 30°C</li>
                        <li>Gemaakt in Portugal</li>
                    </ul>
                </details>
                <details>
                    <summary>Verzending & Retour</summary>
                    <ul>
                        <li>Gratis verzending vanaf €50</li>
                        <li>Levering binnen 2-5 werkdagen</li>
                        <li>30 dagen gratis retour</li>
                        <li>Veilige betaling</li>
                    </ul>
                </details>
            </div>
        </div>
    </div>

  
    <section class="related-products">
        <h2 class="section-title">GERELATEERDE PRODUCTEN</h2>
        <div class="products-grid">
            <article class="product-card">
                <span class="heart" role="button" aria-label="Toevoegen aan favorieten">🤍</span>
                <a href="product.php?id=2">
                    <img src="img/prod2.jpg" alt="JW Hoodie White">
                    <h3>JW Hoodie White</h3>
                    <p class="price">€90 <span class="old-price">€160</span></p>
                </a>
            </article>
            <article class="product-card">
                <span class="heart" role="button" aria-label="Toevoegen aan favorieten">🤍</span>
                <a href="product.php?id=3">
                    <img src="img/prod3.jpg" alt="JW Bag Black">
                    <h3>JW Bag Black</h3>
                    <p class="price">€150 <span class="old-price">€230</span></p>
                </a>
            </article>
            <article class="product-card">
                <span class="heart" role="button" aria-label="Toevoegen aan favorieten">🤍</span>
                <a href="product.php?id=4">
                    <img src="img/prod4.jpg" alt="JW Oversized Tee">
                    <h3>JW Oversized Tee</h3>
                    <p class="price">€60 <span class="old-price">€120</span></p>
                </a>
            </article>
        </div>
    </section>

    <script>
      
        function changeImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = thumbnail.src;
            
           
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }

      
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

      
        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            if (qtyInput.value < 10) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
            }
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            if (qtyInput.value > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }
    </script>
</body>
</html>