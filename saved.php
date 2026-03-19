<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
  <section class="active">
    <div class="wrap">

      <h1>Your Wishlist ❤️</h1>
      <p style="color: var(--muted); margin-bottom: 20px;">
        These are the destinations you’ve saved for later.
      </p>

      <div class="grid" id="savedGrid"></div>
      <p class="muted" id="noSavedMsg">You haven’t saved any destinations yet.</p>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
let savedGrid = document.getElementById('savedGrid');
let noMsg = document.getElementById('noSavedMsg');

// Fetch saved destinations from localStorage
let saved = JSON.parse(localStorage.getItem('savedDestinations')) || [];

if (saved.length > 0) {
  noMsg.style.display = 'none';
  saved.forEach(dest => {
    let card = document.createElement('div');
    card.className = 'card';
    card.style.cssText = 'border-radius:10px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.1);';
    card.innerHTML = `
      <div class="media" style="background-image: url('${dest.image}'); height:200px; background-size:cover; background-position:center;"></div>
      <div style="padding:15px;">
        <h3>${dest.name}</h3>
        <button class="btn" style="width:100%; margin-bottom:10px;" onclick="bookDestination('${dest.name}')">💺 Book Now</button>
        <button class="ghost" onclick="removeSaved('${dest.name}')">❌ Remove</button>
      </div>
    `;
    savedGrid.appendChild(card);
  });
}

function removeSaved(name) {
  let saved = JSON.parse(localStorage.getItem('savedDestinations')) || [];
  saved = saved.filter(item => item.name !== name);
  localStorage.setItem('savedDestinations', JSON.stringify(saved));
  alert('❌ ' + name + ' removed from wishlist.');
  location.reload();
}

function bookDestination(name) {
  alert('📍 Redirecting to book ' + name + '...');
  // Redirect to places page or search for the destination
  window.location.href = 'search.php?query=' + encodeURIComponent(name);
}
</script>
