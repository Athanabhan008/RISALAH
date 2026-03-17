<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Risalah Menu</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


<style>

:root{
--orange:#FF833E;
--black:#111;
--gray:#777;
--bg:#f7f7f7;
}

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:'Poppins',sans-serif;
background:var(--bg);
color:#222;
}

/* HEADER */

header{
background:#fff;
padding:12px 20px;
display:flex;
align-items:center;
justify-content:space-between;
box-shadow:0 2px 6px rgba(0,0,0,0.08);
position:sticky;
top:0;
z-index:100;
}

.logo img{
height:40px;
}

/* SEARCH */

.search{
padding:15px 20px;
background:#fff;
}

.search input{
width:100%;
padding:12px 15px;
border-radius:10px;
border:1px solid #eee;
background:#fafafa;
font-size:14px;
}

/* BANNER */

.banner img{
width:100%;
height:100%;
object-fit:cover;
}

/* CATEGORY */

.categories{
display:flex;
gap:10px;
overflow-x:auto;
padding:15px 15px 15px;
background:#fff;
position:sticky;
top:60px;

z-index:90;
}

.categories button{
border:none;
background:#f0f0f0;
padding:8px 14px;
border-radius:20px;
font-size:13px;
cursor:pointer;
white-space:nowrap;
}

.categories button.active{
background:var(--orange);
color:#fff;
}

/* MENU GRID */

.menu-container{
padding:20px;
display:grid;
grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
gap:18px;
}

/* MENU CARD */

.menu-item{
background:#fff;
border-radius:12px;
overflow:hidden;
box-shadow:0 4px 10px rgba(0,0,0,0.06);
transition:.2s;
}

.menu-item:hover{
transform:translateY(-4px);
}

.menu-item img{
width:100%;
height:160px;
object-fit:cover;
}

.menu-info{
padding:12px;
}

.menu-info h3{
font-size:15px;
font-weight:600;
margin-bottom:4px;
}

.menu-info p{
font-size:12px;
color:var(--gray);
margin-bottom:8px;
}

.price{
font-weight:600;
color:var(--orange);
font-size:14px;
}

.billiard-fab{
position:fixed;
bottom:24px;
right:20px;
z-index:150;
display:flex;
align-items:center;
gap:10px;
background:#111;
border:none;
border-radius:50px;
padding:8px 8px;
cursor:pointer;
box-shadow:0 4px 16px rgba(0,0,0,0.35);
transition:all .25s ease;
overflow:hidden;
max-width:48px;
}

.billiard-fab:hover{
max-width:200px;
padding:8px 16px 8px 8px;
background:#1a1a1a;
}

.billiard-fab img{
width:32px;
height:32px;
border-radius:50%;
object-fit:contain;
flex-shrink:0;
}

.billiard-fab span{
color:#fff;
font-size:13px;
font-weight:500;
white-space:nowrap;
opacity:0;
transition:opacity .2s ease .05s;
font-family:'Poppins',sans-serif;
}

.billiard-fab:hover span{
opacity:1;
}

/* BILLIARD SECTION */

.billiard{
margin-top:40px;
background:#111;
color:#fff;
padding:40px 20px;
}

.billiard img{
height:100px;
margin-bottom:15px;
}

.billiard h2{
margin-bottom:20px;
font-weight:500;
}

.billiard-card{
background:#1d1d1d;
border-radius:10px;
padding:14px;
margin-bottom:10px;
display:flex;
justify-content:space-between;
}

.billiard span{
color:var(--orange);
font-weight:600;
}

/* FOOTER */

footer{
margin-top:40px;
background:#fff;
text-align:center;
padding:25px;
font-size:13px;
color:#777;
}

.theme-toggle{
background:none;
border:none;
font-size:20px;
cursor:pointer;
padding:4px 8px;
border-radius:8px;
line-height:1;
transition:.2s;
}

.theme-toggle:hover{background:rgba(0,0,0,0.06);}

/* DARK MODE */

[data-theme="dark"]{
--bg:#121212;
--black:#e8e8e8;
--gray:#999;
}

[data-theme="dark"] body{color:#e8e8e8;}

[data-theme="dark"] header,
[data-theme="dark"] .search,
[data-theme="dark"] .categories,
[data-theme="dark"] footer{background:#1e1e1e;}

[data-theme="dark"] .search input{
background:#2a2a2a;
border-color:#333;
color:#e8e8e8;
}

[data-theme="dark"] .search input::placeholder{color:#555;}

[data-theme="dark"] .categories button{background:#2a2a2a;color:#e8e8e8;}
[data-theme="dark"] .categories button.active{background:var(--orange);color:#fff;}

[data-theme="dark"] .menu-item{
background:#1e1e1e;
box-shadow:0 4px 10px rgba(0,0,0,0.4);
}

[data-theme="dark"] .menu-item-no-img{background:#2a2a2a;color:#444;}
[data-theme="dark"] .menu-info h3{color:#e8e8e8;}
[data-theme="dark"] .theme-toggle:hover{background:rgba(255,255,255,0.08);}

/* RESPONSIVE */

@media(max-width:600px){

.banner img{
height:170px;
}

.menu-container{
grid-template-columns:repeat(2,1fr);
}

}

</style>
</head>


<body>

<header>

<div class="logo">
<img src="client/kafe/logo/risalahlogo.png">
</div>

<button class="theme-toggle" id="themeToggle" title="Toggle dark mode"></button>

</header>


<div class="search">
<input type="text" id="searchInput" placeholder="Cari menu favoritmu...">
</div>


<div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="4000">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="client/kafe/banner/risalahbanner1.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="client/kafe/banner/risalahbanner2.png" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="client/kafe/banner/risalahbanner3.png" class="d-block w-100" alt="...">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<div class="categories">

<button class="active" onclick="filterMenu('all',this)">All</button>
<button onclick="filterMenu('house',this)">House Favorites</button>
<button onclick="filterMenu('espresso',this)">Espresso Bar</button>
<button onclick="filterMenu('daily',this)">Daily Brew</button>
<button onclick="filterMenu('sweet',this)">Sweet Creations</button>
<button onclick="filterMenu('bites',this)">Comfort Bites</button>
<button onclick="filterMenu('iftar',this)">Iftar Meal</button>
<button onclick="filterMenu('billiard',this)">Billiard</button>
<button onclick="filterMenu('add',this)">Addon</button>

</div>


<div class="menu-container" id="menuList">


<!-- ADD ON -->
@foreach ($menu as $item)

<div class="menu-item add">
    <img src="{{ Storage::url($item->gambar) }}">
    {{ $item->nama_menu }}
    <div class="menu-info">
        <h3></h3>
        <p>Add on everyday</p>
        <div class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
    </div>
</div>

@endforeach

</div>

<button class="billiard-fab" onclick="scrollToBilliard()" title="Billiard Corner">
    <img src="client/kafe/logo/lupinuslogo.png" alt="Billiard Corner">
    <span>Billiard Corner</span>
  </button>


<!-- BILLIARD -->

<div class="billiard" id="billiard-section">

<img src="client/kafe/logo/lupinuslogo.png">

<h2>Billiard Corner</h2>

<div class="billiard-card">Siang - 1 Jam <span>Rp. 20.000</span></div>
<div class="billiard-card">Siang - 2 Jam+ <span>Rp. 30.000</span></div>
<div class="billiard-card">Malam - 1 Jam <span>Rp. 25.000</span></div>
<div class="billiard-card">Malam - 2 Jam <span>Rp. 50.000 (Free Ice Tea)</span></div>
<div class="billiard-card">Malam - 3 Jam <span>Rp. 75.000 (2 Free Ice Tea)</span></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>


<footer>
© 2026 Risalah Corporation
</footer>

<script>
    $('.carousel').carousel({
  interval: 2000
});
</script>

<script>

// ── Theme ─────────────────────────────────────────────────────────────────────
function setThemeIcon() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  document.getElementById('themeToggle').textContent = isDark ? '☀️' : '🌙';
}

function toggleTheme() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const next = isDark ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  setThemeIcon();
}

document.getElementById('themeToggle').addEventListener('click', toggleTheme);
setThemeIcon();

function filterMenu(category,btn){

document.querySelectorAll(".categories button").forEach(b=>b.classList.remove("active"))
btn.classList.add("active")

let items=document.querySelectorAll(".menu-item")

items.forEach(item=>{
item.style.display="none"
})

if(category==="all"){
items.forEach(item=>{
item.style.display="block"
})
}else{
document.querySelectorAll("."+category).forEach(el=>{
el.style.display="block"
})
}

}

document.getElementById("searchInput").addEventListener("keyup",function(){

let value=this.value.toLowerCase()

document.querySelectorAll(".menu-item").forEach(item=>{

let text=item.innerText.toLowerCase()

item.style.display=text.includes(value)?"block":"none"

})

})

function scrollToBilliard() {

const section = document.getElementById("billiard-section");

if (!section) return;

section.scrollIntoView({
  behavior: "smooth",
  block: "start"
});

section.classList.add("highlight");

setTimeout(() => {
  section.classList.remove("highlight");
}, 1200);

}

function filterMenu(category, btn) {

if (category === "billiard") {
  scrollToBilliard();
  return;
}

activeCategory = category;

updateButtons(btn);

renderMenu();

}

</script>


</body>
</html>
