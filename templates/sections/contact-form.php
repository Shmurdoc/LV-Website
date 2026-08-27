<?php
/**
 * Section: Contact Form — Viata Luxe Guesthouse
 * POSTs to /api/contact.php (inserts into contact_submissions, read by /admin/contact)
 * Variables: $section
 */
?>
<?php if (!empty($section['title'])): ?>
<h2 class="section-heading reveal"><?= e($section['title']) ?></h2>
<?php endif; ?>
<?php if (!empty($section['subtitle'])): ?>
<p class="subhead reveal"><?= e($section['subtitle']) ?></p>
<?php endif; ?>

<form id="contactForm" method="POST" action="<?= url('/api/contact.php') ?>" class="reveal" style="display:grid;gap:16px;max-width:640px;margin-top:18px">
    <?= csrf_field() ?>
    <!-- Honeypot (security) -->
    <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off" aria-hidden="true">
    <div class="field">
        <label class="field__label" for="cName">Name *</label>
        <input class="field__input" id="cName" name="name" required autocomplete="name" maxlength="255">
    </div>
    <div class="field">
        <label class="field__label" for="cEmail">Email *</label>
        <input class="field__input" id="cEmail" name="email" type="email" required autocomplete="email" maxlength="255">
    </div>
    <div class="field">
        <label class="field__label" for="cMsg">Message *</label>
        <textarea class="field__input" id="cMsg" name="message" rows="5" required maxlength="5000" placeholder="How can we help?"></textarea>
    </div>
    <div id="contactMsg" class="field__hint" role="status" aria-live="polite" hidden></div>
    <button type="submit" class="btn btn--navy" style="justify-self:start">Send Message</button>
</form>
<script>
document.getElementById('contactForm')?.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const form=e.target, msg=document.getElementById('contactMsg'), btn=form.querySelector('button');
  const fd=new FormData(form);
  btn.disabled=true; btn.textContent='Sending…';
  try{
    const r=await fetch(form.action,{method:'POST',body:fd,headers:{'X-CSRF-TOKEN': fd.get('csrf_token')}});
    const j=await r.json();
    msg.hidden=false;
    if(j.ok){ msg.textContent=j.message; msg.style.color='var(--sage-600)'; form.reset(); }
    else{ msg.textContent=j.error + (j.details? ' — '+Object.values(j.details).join(' '):''); msg.style.color='#b00020'; }
  }catch(err){ msg.hidden=false; msg.textContent='Network error — email info@viataluxe.com'; }
  finally{ btn.disabled=false; btn.textContent='Send Message'; }
});
</script>
