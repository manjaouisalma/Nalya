(() => {
  const read = key => { try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch { return []; } };
  const write = (key, value) => localStorage.setItem(key, JSON.stringify(value));
  const cartKey = 'nalya_cart';
  const favKey = 'nalya_favorites';
  const money = value => Number(value).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' DH';
  const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
  const updateCounts = () => {
    const cartCount = read(cartKey).reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    document.querySelectorAll('[data-cart-count]').forEach(el => el.textContent = cartCount);
    document.querySelectorAll('[data-favorite-count]').forEach(el => el.textContent = read(favKey).length);
    document.querySelectorAll('[data-favorite]').forEach(btn => {
      const active = read(favKey).includes(Number(btn.dataset.favorite));
      btn.classList.toggle('is-favorite', active);
      if (btn.dataset.favorite) btn.textContent = active ? '♥' : '♡';
    });
  };
  const toast = message => {
    let el = document.querySelector('.toast');
    if (!el) { el = document.createElement('div'); el.className = 'toast'; document.body.appendChild(el); }
    el.textContent = message; el.classList.add('show');
    window.setTimeout(() => el.classList.remove('show'), 2300);
  };
  const addCart = (id, name, price, stock, image, quantity = 1) => {
    const cart = read(cartKey);
    const item = cart.find(p => Number(p.id) === Number(id));
    const qty = Math.max(1, Number(quantity) || 1);
    if (qty > Number(stock)) return toast('Quantité supérieure au stock disponible.');
    if (item) {
      if (Number(item.quantity) + qty > Number(stock)) return toast('Stock insuffisant pour cette quantité.');
      item.quantity = Number(item.quantity) + qty;
    } else cart.push({id:Number(id), name, price:Number(price), stock:Number(stock), image, quantity:qty});
    write(cartKey, cart); updateCounts(); toast('Article ajouté au panier.');
  };
  document.addEventListener('click', event => {
    const menu = event.target.closest('.menu-toggle');
    if (menu) {
      const nav = document.querySelector('.main-nav');
      const open = nav.classList.toggle('is-open');
      menu.setAttribute('aria-expanded', String(open));
    }
    const fav = event.target.closest('[data-favorite]');
    if (fav) {
      const id = Number(fav.dataset.favorite);
      let list = read(favKey);
      list = list.includes(id) ? list.filter(value => value !== id) : [...list, id];
      write(favKey, list); updateCounts();
      if (document.getElementById('favorites-page')) renderFavorites();
      else toast(list.includes(id) ? 'Ajouté aux favoris.' : 'Retiré des favoris.');
    }
    const add = event.target.closest('[data-add-cart]');
    if (add) {
      const quantityInput = add.dataset.useQuantity ? document.getElementById(add.dataset.useQuantity) : null;
      addCart(add.dataset.addCart, add.dataset.name, add.dataset.price, add.dataset.stock, add.dataset.image, quantityInput ? quantityInput.value : 1);
    }
    const remove = event.target.closest('[data-remove-cart]');
    if (remove) { write(cartKey, read(cartKey).filter(item => Number(item.id) !== Number(remove.dataset.removeCart))); renderCart(); updateCounts(); }
    const removeFav = event.target.closest('[data-remove-favorite]');
    if (removeFav) { write(favKey, read(favKey).filter(id => Number(id) !== Number(removeFav.dataset.removeFavorite))); renderFavorites(); updateCounts(); }
    const favAdd = event.target.closest('[data-fav-add-cart]');
    if (favAdd) {
      const item = favAdd.dataset;
      addCart(item.favAddCart, item.name, item.price, item.stock, item.image, 1);
    }
  });
  document.addEventListener('change', event => {
    const input = event.target.closest('[data-cart-quantity]');
    if (!input) return;
    const id = Number(input.dataset.cartQuantity), qty = Math.max(1, Number(input.value) || 1);
    const cart = read(cartKey), item = cart.find(p => Number(p.id) === id);
    if (item) item.quantity = Math.min(qty, Number(item.stock));
    write(cartKey, cart); renderCart(); updateCounts();
  });
  async function fetchProducts(ids) {
    const endpoint = document.querySelector('[data-products-endpoint]')?.dataset.productsEndpoint || 'api/products.php';
    const url = ids && ids.length ? endpoint + '?ids=' + encodeURIComponent(ids.join(',')) : endpoint;
    const response = await fetch(url);
    if (!response.ok) throw new Error('Impossible de charger les produits.');
    return response.json();
  }
  function card(product, favoriteMode = false) {
    return `<article class="product-card"><button class="favorite-button is-favorite" data-favorite="${product.id}" aria-label="Retirer des favoris">♥</button><a class="product-photo" href="product-details.php?id=${product.id}"><img src="${escapeHtml(product.image || 'assets/images/product-placeholder.svg')}" alt="${escapeHtml(product.name)}"></a><p class="product-category">${escapeHtml(product.category_name || 'Nalya')}</p><h3><a href="product-details.php?id=${product.id}">${escapeHtml(product.name)}</a></h3><div class="product-card-bottom"><strong>${money(product.price)}</strong>${favoriteMode ? `<button class="add-mini" data-fav-add-cart="${product.id}" data-name="${escapeHtml(product.name)}" data-price="${product.price}" data-stock="${product.stock}" data-image="${escapeHtml(product.image)}">＋</button>` : `<button class="add-mini" data-add-cart="${product.id}" data-name="${escapeHtml(product.name)}" data-price="${product.price}" data-stock="${product.stock}" data-image="${escapeHtml(product.image)}">＋</button>`}</div>${favoriteMode ? `<button class="remove-favorite text-link" data-remove-favorite="${product.id}">Retirer des favoris</button>` : ''}</article>`;
  }
  async function renderCart() {
    const root = document.getElementById('cart-page'); if (!root) return;
    const cart = read(cartKey);
    if (!cart.length) { root.innerHTML = '<div class="empty-state"><h2>Votre panier est vide</h2><p>Quelques bijoux vous attendent.</p><a class="btn btn-dark" href="products.php">Découvrir la collection</a></div>'; return; }
    try {
      const products = await fetchProducts(cart.map(item => item.id));
      const validCart = cart.filter(item => products.some(p => Number(p.id) === Number(item.id)));
      write(cartKey, validCart);
      const total = validCart.reduce((sum,item) => sum + Number(item.price) * Number(item.quantity), 0);
      root.innerHTML = `<div class="cart-layout"><div class="cart-lines">${validCart.map(item => `<article class="cart-line"><img src="${escapeHtml(item.image)}" alt=""><div class="cart-line-info"><h3>${escapeHtml(item.name)}</h3><p>${money(item.price)}</p><label>Quantité <input type="number" min="1" max="${item.stock}" value="${item.quantity}" data-cart-quantity="${item.id}"></label></div><div class="cart-line-total"><strong>${money(item.price * item.quantity)}</strong><button class="text-link" data-remove-cart="${item.id}">Supprimer</button></div></article>`).join('')}</div><aside class="cart-summary"><p class="eyebrow">RÉCAPITULATIF</p><h2>Votre commande</h2><div class="summary-row"><span>Sous-total</span><strong>${money(total)}</strong></div><div class="summary-row"><span>Livraison</span><span>À confirmer</span></div><div class="summary-row summary-total"><span>Total estimé</span><strong>${money(total)}</strong></div><p class="small-note">Le prix et le stock seront revérifiés par le serveur au moment de la commande.</p><a class="btn btn-dark btn-full" href="checkout.php">Continuer la commande</a></aside></div>`;
    } catch { root.innerHTML = '<p class="empty-state">Impossible de charger le panier. Vérifiez la connexion à la base de données.</p>'; }
  }
  async function renderFavorites() {
    const root = document.getElementById('favorites-page'); if (!root) return;
    const ids = read(favKey);
    if (!ids.length) { root.innerHTML = '<div class="empty-state"><h2>Pas encore de favoris</h2><p>Gardez ici les bijoux qui vous plaisent.</p><a class="btn btn-dark" href="products.php">Explorer la collection</a></div>'; return; }
    try {
      const products = await fetchProducts(ids);
      root.innerHTML = products.length ? `<div class="product-grid">${products.map(p => card(p, true)).join('')}</div>` : '<div class="empty-state"><h2>Vos favoris ne sont plus disponibles</h2><a href="products.php">Voir la collection</a></div>';
    } catch { root.innerHTML = '<p class="empty-state">Impossible de charger les favoris.</p>'; }
  }
  updateCounts();
  renderCart();
  renderFavorites();
})();
