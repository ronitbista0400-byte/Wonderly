// ================================
// Wanderly — Main JS
// ================================

// ---- Section Navigation (for index.php SPA-style sections) ----
function switchView(id) {
  document.querySelectorAll('main section').forEach(s => s.classList.remove('active'));
  const target = document.getElementById(id);
  if (target) {
    target.classList.add('active');
    target.scrollIntoView({ behavior: 'smooth' });
  }
}

// ---- Dark Mode Toggle ----
function toggleDark() {
  document.documentElement.classList.toggle('dark-mode');
  localStorage.setItem('darkMode', document.documentElement.classList.contains('dark-mode'));
}

// Apply saved dark mode on load
(function () {
  if (localStorage.getItem('darkMode') === 'true') {
    document.documentElement.classList.add('dark-mode');
  }
})();

// ---- Flatpickr Date Pickers ----
document.addEventListener('DOMContentLoaded', function () {
  if (typeof flatpickr !== 'undefined') {
    flatpickr('#bookDate', {
      minDate: 'today',
      dateFormat: 'Y-m-d',
    });
  }
});

// ---- Destination Card Count Update ----
function updateCount() {
  const grid = document.getElementById('grid');
  const count = document.getElementById('count');
  if (grid && count) {
    count.textContent = grid.querySelectorAll('.card').length;
  }
}

// ---- Filter Destinations (index.php) ----
function filterDestinations() {
  const query = (document.getElementById('searchInput')?.value || '').toLowerCase();
  const priceLimit = document.getElementById('priceFilter')?.value;
  const cards = document.querySelectorAll('#grid .card');

  let visible = 0;
  cards.forEach(card => {
    const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
    const priceText = card.querySelector('strong')?.textContent.replace(/[^0-9]/g, '') || '0';
    const price = parseInt(priceText);

    const matchName = name.includes(query);
    const matchPrice = priceLimit === 'all' || price < parseInt(priceLimit);

    if (matchName && matchPrice) {
      card.style.display = '';
      visible++;
    } else {
      card.style.display = 'none';
    }
  });

  const count = document.getElementById('count');
  if (count) count.textContent = visible;
}

// ---- Booking Modal (index.php) ----
let currentDestId = null;
let currentBasePrice = 0;

function openModal(id, price, title, desc, imgSrc) {
  currentDestId = id;
  currentBasePrice = price;

  const el = id => document.getElementById(id);
  if (el('modalTitle')) el('modalTitle').textContent = title;
  if (el('modalImg')) el('modalImg').src = imgSrc;
  if (el('modalDesc')) el('modalDesc').innerHTML = `<p style="color:var(--muted)">Price per person: Rs. ${Number(price).toLocaleString()}</p>`;

  const backdrop = document.getElementById('backdrop');
  if (backdrop) backdrop.style.display = 'flex';

  recalculate();
}

function closeModal() {
  const backdrop = document.getElementById('backdrop');
  if (backdrop) backdrop.style.display = 'none';
}

function recalculate() {
  const passengers = parseInt(document.getElementById('passengers')?.value) || 1;
  const classType = document.getElementById('class')?.value || 'economy';
  const mode = document.querySelector('input[name="method"]:checked')?.value || 'flight';

  let multiplier = 1;
  if (classType === 'first') multiplier = 1.5;
  if (classType === 'vip') multiplier = 2;
  if (mode === 'bus') multiplier *= 0.6;

  const total = Math.round(currentBasePrice * passengers * multiplier);
  const el = document.getElementById('totalPrice');
  if (el) el.textContent = 'Rs. ' + total.toLocaleString();
}

// ---- Confirm Booking & Generate PDF (index.php) ----
function confirmAndGenerate() {
  const date = document.getElementById('bookDate')?.value;
  const passengers = document.getElementById('passengers')?.value;
  const classType = document.getElementById('class')?.value;
  const mode = document.querySelector('input[name="method"]:checked')?.value || 'flight';
  const title = document.getElementById('modalTitle')?.textContent || 'Destination';

  if (!date) {
    alert('Please select a travel date.');
    return;
  }

  // Save booking via fetch (POST to places.php)
  const formData = new FormData();
  formData.append('book_now', '1');
  formData.append('destination_id', currentDestId);
  formData.append('passengers', passengers);
  formData.append('travel_date', date);
  formData.append('mode', mode);
  formData.append('class', classType);

  fetch('places.php', { method: 'POST', body: formData })
    .then(() => {
      generatePDF(title, date, passengers, classType, mode);
      closeModal();
      alert('Booking confirmed! PDF downloaded.');
    })
    .catch(() => {
      // Still generate PDF even if fetch fails
      generatePDF(title, date, passengers, classType, mode);
      closeModal();
    });
}

function generatePDF(dest, date, passengers, classType, mode) {
  if (typeof window.jspdf === 'undefined') return;
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  doc.setFontSize(22);
  doc.text('Wanderly Ticket', 105, 20, null, null, 'center');
  doc.setFontSize(16);
  doc.text(`Destination: ${dest}`, 20, 40);
  doc.text(`Date: ${date}`, 20, 50);
  doc.text(`Passengers: ${passengers}`, 20, 60);
  doc.text(`Class: ${classType}`, 20, 70);
  doc.text(`Mode: ${mode}`, 20, 80);
  doc.save(`${dest}_ticket.pdf`);
}

// Close modal on backdrop click
document.addEventListener('click', function (e) {
  if (e.target && e.target.id === 'backdrop') {
    closeModal();
  }
});
