<?php
/**
 * Contact — Viata Luxe Guesthouse
 * Full DB-driven template following the home.php pattern.
 * Bypasses section renderer, renders HTML directly.
 */

$page = get_page('contact');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav       = get_navigation();
$settings  = settings_group('branding');
$contact   = settings_group('contact');

$phone     = setting('phone', '+27 15 781 0518');
$phone_mob = setting('phone_mobile', '+27 79 418 2077');
$email     = setting('email', 'info@viataluxe.com');
$address   = setting('address', '86 Nollie Bosman Street, Phalaborwa 1390');
$hours     = setting('hours', 'Mon – Sun: 07:00 – 21:00');
$map_query = setting('map_query', '86+Nollie+Bosman+Street+Phalaborwa');
$whatsapp  = setting('whatsapp', '+27794182077');

require __DIR__ . '/../templates/header.php';
?>

<style>
/* ——— Contact page specific ——— */
.info-cards{display:grid; grid-template-columns:repeat(3,1fr); gap:14px}
@media (max-width:760px){ .info-cards{grid-template-columns:1fr} }
.maps-facade{position:relative; min-height:360px; display:grid; place-items:center; text-align:center; padding:24px}
.maps-facade img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.22}
</style>

<!-- PAGE HERO -->
<section class="page-hero page-hero--center">
  <div class="page-hero__media">
    <img src="<?= url($page['hero_image'] ?: '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg') ?>" alt="" width="1920" height="800" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker reveal"><?= e($page['hero_kicker'] ?: 'Contact — Reach Us Anytime') ?></p>
    <h1 class="page-hero__title reveal">Contact <em>Us</em></h1>
    <p class="page-hero__lead reveal"><?= e($page['hero_lead'] ?: 'We would love to hear from you. Reach us by phone, email, or visit us in person.') ?></p>
  </div>
</section>

<main id="main-content" class="container" style="padding-bottom: var(--section-pad)">

  <!-- TOP INFO CARDS — Phone / Email / Address -->
  <section class="info-cards reveal" style="margin-top:28px">
    <div class="info-card">
      <div class="info-card__label">Phone</div>
      <p class="info-card__value">
        <a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a>
        <span style="color:var(--ink-55)"> | </span>
        <a href="tel:<?= e(preg_replace('/\s+/', '', $phone_mob)) ?>"><?= e($phone_mob) ?></a>
      </p>
      <a class="link" href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>" style="margin-top:4px; display:inline-block">Call Tel</a> · <a class="link" href="tel:<?= e(preg_replace('/\s+/', '', $phone_mob)) ?>">Call Mobile</a>
    </div>
    <div class="info-card">
      <div class="info-card__label">Email</div>
      <p class="info-card__value"><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
      <a class="link" href="mailto:<?= e($email) ?>" style="margin-top:4px; display:inline-block">Send Email</a>
    </div>
    <div class="info-card">
      <div class="info-card__label">Address</div>
      <p class="info-card__detail"><?= nl2br(e($address)) ?></p>
      <a class="link" href="https://www.google.com/maps/search/<?= e($map_query) ?>" target="_blank" rel="noopener" style="margin-top:4px; display:inline-block">Open in Maps →</a>
    </div>
  </section>

  <!-- CONTACT GRID — Form + Map/Booking -->
  <section class="contact-grid" style="margin-top:18px">
    <div class="reveal">
      <h2 style="font-family:var(--font-display); font-weight:300; font-size:22px">Send us a message</h2>
      <p class="caption" style="margin-top:6px; max-width:52ch">Please enable JavaScript in your browser to complete this form.</p>
      <form id="connectForm" class="connect-form" style="margin-top:14px" novalidate>
        <div class="honey" aria-hidden="true"><label for="website">Website (leave blank)</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
        <div class="field"><label class="field__label" for="fName">Name *</label><input class="field__input" id="fName" name="name" required placeholder="Your name"></div>
        <div class="field"><label class="field__label" for="fEmail">Email *</label><input class="field__input" id="fEmail" name="email" type="email" required placeholder="you@example.com"></div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
          <div class="field"><label class="field__label" for="fArrival">Arrival *</label><input class="field__input" id="fArrival" name="arrival" type="date" required></div>
          <div class="field"><label class="field__label" for="fDeparture">Departure *</label><input class="field__input" id="fDeparture" name="departure" type="date" required></div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
          <div class="field"><label class="field__label" for="fPhone">Phone</label><input class="field__input" id="fPhone" name="phone" placeholder="+27 ..."></div>
          <div class="field"><label class="field__label" for="fGuests">Guests</label><select class="field__input" id="fGuests" name="guests"><option value="2 guests">2 guests</option><option value="3 guests">3 guests</option><option value="4 guests">4 guests</option><option value="5 guests">5 guests</option><option value="6 guests">6 guests</option></select></div>
        </div>
        <div class="field"><label class="field__label" for="fNotes">Comment or Message</label><textarea class="field__input" id="fNotes" name="notes" rows="4" placeholder="Comment or Message"></textarea></div>
        <button type="submit" class="btn btn--navy" style="width:100%; justify-content:center">Submit <span style="font-weight:400; opacity:0.7">(mailto fallback)</span></button>
        <div id="formMsg" class="connect-form__msg" hidden></div>
      </form>
    </div>

    <div class="reveal reveal--delay-1" style="display:grid; gap:14px; align-content:start">
      <!-- Map facade -->
      <div class="maps-facade" id="mapsFacade" tabindex="0" role="button" aria-label="Load Google Maps">
        <img src="<?= url($page['hero_image'] ?: '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg') ?>" alt="Map poster — exterior cottages" loading="lazy" decoding="async">
        <div style="position:relative; z-index:2; display:grid; gap:10px; place-items:center; padding:18px">
          <div style="font-family:var(--font-display); font-weight:300; font-size:20px; color:var(--navy)"><?= e($address) ?></div>
          <div style="font-size:12px; color:var(--ink-55)">Phalaborwa 1390 — Corner 13 Prinsloo &amp; Nollie Bosman</div>
          <button class="maps-facade__btn" id="mapsBtn">Load Map — Google Maps</button>
        </div>
      </div>
      <div id="mapsFrame" hidden style="border:1px solid var(--line); border-radius:var(--radius-lg); overflow:hidden; height:360px; background:var(--ivory)">
        <iframe src="https://www.google.com/maps?q=<?= e($map_query) ?>&z=16&output=embed" width="100%" height="100%" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Google Maps — <?= e($address) ?>"></iframe>
      </div>

      <!-- NightsBridge card -->
      <div class="nb-card">
        <div class="kicker">NightsBridge — Instant book</div>
        <h3 class="nb-card__title">Book direct — <em>38331</em></h3>
        <p style="color:rgba(248,246,241,0.7); font-size:13px; margin-top:4px">Book direct via NightsBridge — instant confirmation.</p>
        <div style="margin-top:12px; border:1px solid rgba(248,246,241,0.16); border-radius:12px; overflow:hidden; height:320px; background:var(--cream)">
          <iframe src="https://book.nightsbridge.com/38331" title="NightsBridge — Viata Luxe 38331" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" style="width:100%; height:100%; border:0"></iframe>
        </div>
        <a href="https://book.nightsbridge.com/38331" target="_blank" rel="noopener" class="btn btn--gold" style="width:100%; justify-content:center; margin-top:12px">Open NightsBridge — 38331</a>
      </div>
    </div>
  </section>

  <!-- EMERGENCY / HOURS -->
  <section class="reveal" style="margin-top:18px">
    <div class="card card__pad">
      <div class="micro">Business — via Home context</div>
      <h3 style="font-family:var(--font-display); font-weight:300; margin-top:6px">Minutes to Kruger</h3>
      <p style="color:var(--ink-70); margin-top:6px; font-size:14px">We Would Love To Hear From You — <?= e($phone) ?> Tel | <?= e($phone_mob) ?> Mobile. Host on arrival, self-catering, secure parking.</p>
    </div>
  </section>

</main>

<div id="lightbox" class="lightbox" aria-hidden="true"><button class="lightbox__close" aria-label="Close">✕</button><img alt=""><span class="lightbox__counter"></span><button class="lightbox__nav lightbox__nav--prev" aria-label="Previous">‹</button><button class="lightbox__nav lightbox__nav--next" aria-label="Next">›</button></div>

<script>
var mf=document.getElementById('mapsFacade'), mframe=document.getElementById('mapsFrame');
function loadMaps(){ if(!mf||!mframe) return; mf.style.display='none'; mframe.hidden=false; if(window.ScrollTrigger) ScrollTrigger.refresh(); }
if(mf){ mf.addEventListener('click', loadMaps); mf.addEventListener('keydown', function(e){ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); loadMaps(); } }); var mb=document.getElementById('mapsBtn'); if(mb) mb.addEventListener('click', function(e){ e.stopPropagation(); loadMaps(); }); }
var form=document.getElementById('connectForm');
if(form){
  var hp=document.getElementById('website'), msg=document.getElementById('formMsg');
  function showMsg(t,ok){ if(!msg) return; msg.hidden=false; msg.textContent=t; msg.className='connect-form__msg '+(ok?'connect-form__msg--ok':'connect-form__msg--err'); }
  var today=new Date().toISOString().slice(0,10);
  var arr=document.getElementById('fArrival'), dep=document.getElementById('fDeparture');
  if(arr) arr.min=today; if(dep) dep.min=today;
  if(arr&&dep) arr.addEventListener('change', function(){ dep.min=arr.value||today; if(dep.value && dep.value <= arr.value){ dep.value=''; showMsg('Departure must be after arrival.', false); }});
  form.addEventListener('submit', function(e){
    if(hp && hp.value.trim()!==''){ e.preventDefault(); showMsg('Spam detected — not sent.', false); return; }
    if(!form.checkValidity()){ showMsg('Please fill required: Name, Email, Arrival, Departure.', false); return; }
    var a=arr.value, d=dep.value;
    if(a && d && d <= a){ e.preventDefault(); showMsg('Departure must be after arrival.', false); return; }
    e.preventDefault();
    var name=(document.getElementById('fName')||{}).value||'', email=(document.getElementById('fEmail')||{}).value||'', phone=(document.getElementById('fPhone')||{}).value||'', guests=(document.getElementById('fGuests')||{}).value||'', notes=(document.getElementById('fNotes')||{}).value||'';
    var subject=encodeURIComponent('Viata Luxe enquiry — '+name+' · '+a+' → '+d);
    var body=encodeURIComponent('Name: '+name+'\nEmail: '+email+'\nPhone: '+phone+'\nGuests: '+guests+'\nArrival: '+a+'\nDeparture: '+d+'\nNotes: '+notes+'\n\n— via viata luxe contact form (mailto fallback)');
    var mailto='mailto:<?= e($email) ?>?subject='+subject+'&body='+body;
    showMsg('Opening mail app… If nothing opens, email <?= e($email) ?>. NightsBridge above is instant.', true);
    setTimeout(function(){ window.location.href=mailto; }, 400);
  });
}
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>
