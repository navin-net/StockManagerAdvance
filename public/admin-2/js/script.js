
  /* ════════════════════════════
     SUBMENU TOGGLE
  ════════════════════════════ */
  function toggleSubmenu(parentEl) {
    const group   = parentEl.closest('.nav-group');
    const submenu = group.querySelector('.nav-submenu');
    const isOpen  = parentEl.classList.contains('open');

    // Close all other open submenus first
    document.querySelectorAll('.nav-parent.open').forEach(p => {
      if (p !== parentEl) {
        p.classList.remove('open');
        p.closest('.nav-group').querySelector('.nav-submenu').classList.remove('open');
      }
    });

    // Toggle this one
    parentEl.classList.toggle('open', !isOpen);
    submenu.classList.toggle('open', !isOpen);
  }
/* ════════════════════════════════════════════
   DATA
════════════════════════════════════════════ */
let brands = [
  { id:1,  emoji:"🍎", name:"Apple",        slug:"apple",        category:"Electronics",    country:"USA",        products:142, status:"Active",   website:"apple.com",         desc:"Consumer electronics and software.", date:"2023-01-15" },
  { id:2,  emoji:"👟", name:"Nike",          slug:"nike",          category:"Fashion",         country:"USA",        products:389, status:"Active",   website:"nike.com",           desc:"Athletic footwear and apparel.", date:"2023-02-08" },
  { id:3,  emoji:"🚗", name:"Toyota",        slug:"toyota",        category:"Automotive",      country:"Japan",      products:67,  status:"Active",   website:"toyota.com",         desc:"Automotive manufacturer.", date:"2023-03-20" },
  { id:4,  emoji:"☕", name:"Nescafé",       slug:"nescafe",       category:"Food & Beverage", country:"Switzerland",products:54,  status:"Active",   website:"nescafe.com",        desc:"Instant coffee and beverages.", date:"2023-04-05" },
  { id:5,  emoji:"💄", name:"L'Oréal",       slug:"loreal",        category:"Health & Beauty", country:"France",     products:210, status:"Active",   website:"loreal.com",         desc:"Cosmetics and beauty products.", date:"2023-04-18" },
  { id:6,  emoji:"🖥",  name:"Samsung",       slug:"samsung",       category:"Electronics",    country:"South Korea",products:297, status:"Active",   website:"samsung.com",        desc:"Consumer electronics giant.", date:"2023-05-02" },
  { id:7,  emoji:"🏠", name:"IKEA",           slug:"ikea",          category:"Home & Living",   country:"Sweden",     products:831, status:"Active",   website:"ikea.com",           desc:"Home furnishings and decor.", date:"2023-05-14" },
  { id:8,  emoji:"⚽", name:"Adidas",         slug:"adidas",        category:"Sports",          country:"Germany",    products:453, status:"Active",   website:"adidas.com",         desc:"Sportswear and athletic gear.", date:"2023-06-01" },
  { id:9,  emoji:"🍫", name:"Nestlé",         slug:"nestle",        category:"Food & Beverage", country:"Switzerland",products:178, status:"Inactive", website:"nestle.com",         desc:"Food and drink conglomerate.", date:"2023-06-22" },
  { id:10, emoji:"💊", name:"Johnson & J",   slug:"jj",            category:"Health & Beauty", country:"USA",        products:95,  status:"Active",   website:"jnj.com",            desc:"Healthcare and consumer goods.", date:"2023-07-09" },
  { id:11, emoji:"📱", name:"Xiaomi",         slug:"xiaomi",        category:"Electronics",    country:"China",      products:312, status:"Active",   website:"mi.com",             desc:"Smart devices and ecosystem.", date:"2023-07-30" },
  { id:12, emoji:"🏎", name:"BMW",             slug:"bmw",           category:"Automotive",      country:"Germany",    products:48,  status:"Active",   website:"bmw.com",            desc:"Luxury automotive brand.", date:"2023-08-14" },
  { id:13, emoji:"👒", name:"Zara",            slug:"zara",          category:"Fashion",         country:"Spain",      products:624, status:"Active",   website:"zara.com",           desc:"Fast fashion retailer.", date:"2023-09-03" },
  { id:14, emoji:"🥤", name:"Coca-Cola",      slug:"coca-cola",     category:"Food & Beverage", country:"USA",        products:38,  status:"Active",   website:"coca-cola.com",      desc:"Iconic beverage brand.", date:"2023-09-17" },
  { id:15, emoji:"🧴", name:"Nivea",          slug:"nivea",         category:"Health & Beauty", country:"Germany",    products:87,  status:"Inactive", website:"nivea.com",          desc:"Skin care products.", date:"2023-10-05" },
];

let nextId = 16;
let editingId = null;
let deletingId = null;
let dtInstance = null;

const EMOJIS = ["◑","◈","◉","◎","⬛","🍎","👟","🚗","☕","💄","🖥","🏠","⚽","🍫","💊","📱","🏎","👒","🥤","🧴","🌟","🔷","🔶","🎯","⚡","🌀"];

/* ════════════════════════════════════════════
   STATS
════════════════════════════════════════════ */
function updateStats() {
  const total    = brands.length;
  const active   = brands.filter(b => b.status === "Active").length;
  const inactive = total - active;
  const cats     = new Set(brands.map(b => b.category)).size;

  document.getElementById("statTotal").textContent   = total;
  document.getElementById("statActive").textContent  = active;
  document.getElementById("statInactive").textContent = inactive;
  document.getElementById("statCats").textContent    = cats;
}

/* ════════════════════════════════════════════
   FILTER DROPDOWNS
════════════════════════════════════════════ */
function populateFilters() {
  const cats = [...new Set(brands.map(b => b.category))].sort();
  const sel  = document.getElementById("filterCat");
  const curr = sel.value;
  sel.innerHTML = '<option value="">All Categories</option>' +
    cats.map(c => `<option value="${c}" ${c===curr?'selected':''}>${c}</option>`).join('');
}

function filterTable() {
  const status = document.getElementById("filterStatus").value;
  const cat    = document.getElementById("filterCat").value;

  $.fn.dataTable.ext.search.pop();
  $.fn.dataTable.ext.search.push(function(_, data) {
    const rowStatus = data[5];
    const rowCat    = data[2];
    if (status && rowStatus !== status) return false;
    if (cat    && rowCat    !== cat)    return false;
    return true;
  });

  dtInstance.draw();
}

/* ════════════════════════════════════════════
   RENDER TABLE ROWS
════════════════════════════════════════════ */
function renderRows() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = brands.map((b, i) => `
    <tr>
      <td style="color:var(--muted);font-family:var(--font-mono);font-size:11px">${String(i+1).padStart(2,"0")}</td>
      <td>
        <div class="brand-logo-cell">
          <div class="brand-avatar">${b.emoji}</div>
          <div>
            <div class="brand-name">${b.name}</div>
            <div class="brand-slug">${b.slug}</div>
          </div>
        </div>
      </td>
      <td style="font-size:12px">${b.category}</td>
      <td style="font-size:12px;color:var(--muted)">${b.country}</td>
      <td style="font-family:var(--font-mono);font-size:12px;font-weight:500">${b.products}</td>
      <td>
        <span class="status-pill status-${b.status.toLowerCase()}">
          <span class="status-dot"></span>${b.status}
        </span>
      </td>
      <td style="font-family:var(--font-mono);font-size:11px;color:var(--muted)">${b.date}</td>
      <td>
        <div class="action-btns">
          <button class="act-btn" onclick="openEditModal(${b.id})" title="Edit">✎</button>
          <button class="act-btn" onclick="viewBrand(${b.id})"     title="View">◎</button>
          <button class="act-btn delete" onclick="openDeleteModal(${b.id})" title="Delete">✕</button>
        </div>
      </td>
    </tr>
  `).join('');
}

/* ════════════════════════════════════════════
   DATATABLE INIT
════════════════════════════════════════════ */
function initTable() {
  renderRows();

  dtInstance = $('#brandsTable').DataTable({
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    responsive: {
      details: {
        type: 'inline',
        target: 'td.dtr-control'
      }
    },
    columnDefs: [
      { orderable: false,  targets: [0, 7] },
      { searchable: false, targets: [0, 4, 7] },
      /* Responsive priority — lower = stays visible longer */
      { responsivePriority: 1, targets: 1 },   /* Brand name — always visible */
      { responsivePriority: 2, targets: 7 },   /* Actions — always visible */
      { responsivePriority: 3, targets: 5 },   /* Status */
      { responsivePriority: 4, targets: 4 },   /* Products */
      { responsivePriority: 5, targets: 2 },   /* Category */
      { responsivePriority: 6, targets: 3 },   /* Country */
      { responsivePriority: 7, targets: 6 },   /* Date */
      { responsivePriority: 8, targets: 0 },   /* # — hide first */
    ],
    language: {
      search: "",
      searchPlaceholder: "Search brands…",
      lengthMenu: "Show _MENU_",
      info: "_START_–_END_ of _TOTAL_ brands",
      paginate: { previous: "‹", next: "›" },
    },
  });
}

function refreshTable() {
  if (dtInstance) {
    dtInstance.destroy();
    dtInstance = null;
  }
  initTable();
  updateStats();
  populateFilters();
}

/* ════════════════════════════════════════════
   EMOJI PICKER
════════════════════════════════════════════ */
function buildEmojiPicker(selected) {
  document.getElementById("emojiPicker").innerHTML = EMOJIS.map(e => `
    <div class="emoji-opt ${e===selected?'picked':''}" onclick="pickEmoji('${e}',this)">${e}</div>
  `).join('');
  document.getElementById("fEmoji").value = selected;
}

function pickEmoji(e, el) {
  document.querySelectorAll(".emoji-opt").forEach(o => o.classList.remove("picked"));
  el.classList.add("picked");
  document.getElementById("fEmoji").value = e;
}

/* ════════════════════════════════════════════
   ADD MODAL
════════════════════════════════════════════ */
function openAddModal() {
  editingId = null;
  document.getElementById("formModalTitle").textContent = "Add New Brand";
  document.getElementById("formModalSub").textContent   = "Fill in the brand details below";
  document.getElementById("fName").value     = '';
  document.getElementById("fSlug").value     = '';
  document.getElementById("fCategory").value = '';
  document.getElementById("fCountry").value  = '';
  document.getElementById("fWebsite").value  = '';
  document.getElementById("fStatus").value   = 'Active';
  document.getElementById("fDesc").value     = '';
  buildEmojiPicker("◑");
  document.getElementById("formModal").classList.add("show");
}

/* Auto-slug from name */
document.addEventListener("input", function(e) {
  if (e.target && e.target.id === "fName") {
    document.getElementById("fSlug").value = e.target.value
      .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g,'');
  }
});

/* ════════════════════════════════════════════
   EDIT MODAL
════════════════════════════════════════════ */
function openEditModal(id) {
  const b = brands.find(x => x.id === id);
  if (!b) return;
  editingId = id;

  document.getElementById("formModalTitle").textContent = "Edit Brand";
  document.getElementById("formModalSub").textContent   = `Editing: ${b.name}`;
  document.getElementById("fName").value     = b.name;
  document.getElementById("fSlug").value     = b.slug;
  document.getElementById("fCategory").value = b.category;
  document.getElementById("fCountry").value  = b.country;
  document.getElementById("fWebsite").value  = b.website;
  document.getElementById("fStatus").value   = b.status;
  document.getElementById("fDesc").value     = b.desc;
  buildEmojiPicker(b.emoji);
  document.getElementById("formModal").classList.add("show");
}

/* ════════════════════════════════════════════
   SAVE BRAND
════════════════════════════════════════════ */
function saveBrand() {
  const name     = document.getElementById("fName").value.trim();
  const category = document.getElementById("fCategory").value;

  if (!name)     { showToast("Brand name is required.", true); return; }
  if (!category) { showToast("Please select a category.", true); return; }

  const brand = {
    emoji:    document.getElementById("fEmoji").value,
    name,
    slug:     document.getElementById("fSlug").value || name.toLowerCase().replace(/\s+/g,'-'),
    category,
    country:  document.getElementById("fCountry").value.trim() || "—",
    website:  document.getElementById("fWebsite").value.trim() || "—",
    status:   document.getElementById("fStatus").value,
    desc:     document.getElementById("fDesc").value.trim(),
    products: 0,
    date:     new Date().toISOString().slice(0,10),
  };

  if (editingId) {
    const idx = brands.findIndex(b => b.id === editingId);
    brands[idx] = { ...brands[idx], ...brand };
    showToast(`✓ "${name}" updated successfully.`);
  } else {
    brand.id = nextId++;
    brands.push(brand);
    showToast(`✓ "${name}" added successfully.`);
  }

  closeFormModal();
  refreshTable();
}

function closeFormModal() {
  document.getElementById("formModal").classList.remove("show");
}

function handleFormBackdrop(e) {
  if (e.target === document.getElementById("formModal")) closeFormModal();
}

/* ════════════════════════════════════════════
   DELETE
════════════════════════════════════════════ */
function openDeleteModal(id) {
  deletingId = id;
  const b = brands.find(x => x.id === id);
  document.getElementById("deleteBrandName").textContent = `"${b.name}"`;
  document.getElementById("deleteModal").classList.add("show");
}

function confirmDelete() {
  const b = brands.find(x => x.id === deletingId);
  brands = brands.filter(x => x.id !== deletingId);
  showToast(`✓ "${b.name}" has been deleted.`);
  closeDeleteModal();
  refreshTable();
}

function closeDeleteModal() {
  document.getElementById("deleteModal").classList.remove("show");
  deletingId = null;
}

function handleDeleteBackdrop(e) {
  if (e.target === document.getElementById("deleteModal")) closeDeleteModal();
}

/* ════════════════════════════════════════════
   VIEW (simple toast for now)
════════════════════════════════════════════ */
function viewBrand(id) {
  const b = brands.find(x => x.id === id);
  showToast(`${b.emoji} ${b.name} — ${b.desc || 'No description.'}`);
}

/* ════════════════════════════════════════════
   TOAST
════════════════════════════════════════════ */
let toastTimer;
function showToast(msg, isError=false) {
  const t = document.getElementById("toast");
  t.textContent = msg;
  t.className   = 'show' + (isError ? ' error' : '');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.className = '', 3200);
}

/* ════════════════════════════════════════════
   THEME
════════════════════════════════════════════ */
function toggleTheme() {
  const html = document.documentElement;
  const dark = html.getAttribute("data-bs-theme") === "dark";
  html.setAttribute("data-bs-theme", dark ? "light" : "dark");
  document.getElementById("themeBtn").textContent = dark ? "◑ Dark" : "☀ Light";
}

/* ════════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════════ */
let desktopCollapsed = false, mobileOpen = false;

function toggleSidebar() {
  const isDesktop = window.innerWidth >= 992;
  const sidebar   = document.getElementById("sidebar");
  const main      = document.getElementById("main");
  const btn       = document.getElementById("sidebarToggleBtn");

  if (isDesktop) {
    desktopCollapsed = !desktopCollapsed;
    sidebar.classList.toggle("collapsed", desktopCollapsed);
    main.classList.toggle("collapsed", desktopCollapsed);
    btn.textContent = desktopCollapsed ? "☰" : "✕";
  } else {
    mobileOpen = !mobileOpen;
    sidebar.classList.toggle("open", mobileOpen);
    document.getElementById("overlay").classList.toggle("show", mobileOpen);
    btn.textContent = mobileOpen ? "✕" : "☰";
  }
}

function closeSidebar() {
  mobileOpen = false;
  document.getElementById("sidebar").classList.remove("open");
  document.getElementById("overlay").classList.remove("show");
  document.getElementById("sidebarToggleBtn").textContent = "☰";
}

window.addEventListener("resize", () => {
  if (window.innerWidth >= 992) {
    document.getElementById("sidebar").classList.remove("open");
    document.getElementById("overlay").classList.remove("show");
    mobileOpen = false;
  } else {
    document.getElementById("sidebar").classList.remove("collapsed");
    document.getElementById("main").classList.remove("collapsed");
    desktopCollapsed = false;
  }
  document.getElementById("sidebarToggleBtn").textContent = "☰";
});

/* ════════════════════════════════════════════
   INIT
════════════════════════════════════════════ */
$(document).ready(function() {
  updateStats();
  populateFilters();
  initTable();
});

/* ════════════════════════════════════════════
   THEME PILL
════════════════════════════════════════════ */
function setTheme(mode) {
  document.documentElement.setAttribute('data-bs-theme', mode);
  document.getElementById('btnDark').classList.toggle('active',  mode === 'dark');
  document.getElementById('btnLight').classList.toggle('active', mode === 'light');
}

/* ════════════════════════════════════════════
   PROFILE DROPDOWN
════════════════════════════════════════════ */
function toggleProfileDropdown() {
  const btn = document.getElementById('profileBtn');
  const dd  = document.getElementById('profileDropdown');
  const isOpen = dd.classList.contains('open');
  closeAllDropdowns();
  if (!isOpen) {
    btn.classList.add('open');
    dd.classList.add('open');
  }
}

function closeAllDropdowns() {
  const btn = document.getElementById('profileBtn');
  const dd  = document.getElementById('profileDropdown');
  if (btn) btn.classList.remove('open');
  if (dd)  dd.classList.remove('open');
}

// Close when clicking outside
document.addEventListener('click', function(e) {
  const wrap = document.getElementById('profileWrap');
  if (wrap && !wrap.contains(e.target)) closeAllDropdowns();
});

/* ════════════════════════════════════════════
   PROFILE MODAL
════════════════════════════════════════════ */
function openProfileModal() {
  closeAllDropdowns();
  document.getElementById('profileModal').classList.add('show');
}
function closeProfileModal() {
  document.getElementById('profileModal').classList.remove('show');
}

/* ════════════════════════════════════════════
   PASSWORD MODAL
════════════════════════════════════════════ */
function openPasswordModal() {
  closeAllDropdowns();
  ['pwCurrent','pwNew','pwConfirm'].forEach(id => document.getElementById(id).value = '');
  checkPwStrength();
  document.getElementById('passwordModal').classList.add('show');
}
function closePasswordModal() {
  document.getElementById('passwordModal').classList.remove('show');
}

function checkPwStrength() {
  const val   = document.getElementById('pwNew').value;
  const bars  = [1,2,3,4].map(i => document.getElementById('pwS' + i));
  const label = document.getElementById('pwStrengthLabel');
  let score = 0;
  if (val.length >= 8)           score++;
  if (val.length >= 12)          score++;
  if (/[A-Z]/.test(val))         score++;
  if (/[0-9]/.test(val))         score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const level  = score === 0 ? 0 : score <= 2 ? 1 : score === 3 ? 2 : score === 4 ? 3 : 4;
  const colors = ['var(--border)', '#cc4444', '#998800', '#447744', '#4488cc'];
  const texts  = ['', 'Weak', 'Fair', 'Good', 'Strong ✓'];
  bars.forEach((b, i) => b.style.background = i < level ? colors[level] : 'var(--border)');
  label.textContent = val.length ? texts[level] : '';
  label.style.color = colors[level];
}

function savePassword() {
  const cur  = document.getElementById('pwCurrent').value;
  const nw   = document.getElementById('pwNew').value;
  const conf = document.getElementById('pwConfirm').value;
  if (!cur)          { showToast('Enter your current password.', true); return; }
  if (nw.length < 8) { showToast('New password must be at least 8 characters.', true); return; }
  if (nw !== conf)   { showToast('Passwords do not match.', true); return; }
  showToast('✓ Password updated successfully.');
  closePasswordModal();
}

/* ════════════════════════════════════════════
   SIGN OUT
════════════════════════════════════════════ */
function signOut() {
  closeAllDropdowns();
  document.getElementById('signoutModal').classList.add('show');
}
function closeSignoutModal() {
  document.getElementById('signoutModal').classList.remove('show');
}
