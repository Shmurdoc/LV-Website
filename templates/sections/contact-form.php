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

<form id="contactForm" method="POST" action="<?= url('/api/contact.php') ?>" class="contact-form reveal">
    <?= csrf_field() ?>
    <!-- Honeypot (security) -->
    <input type="text" name="website" value="" class="honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">
    <div class="field">
        <label class="field__label" for="cName"><?= e(setting('contact_form_label_name', 'Name *')) ?></label>
        <input class="field__input" id="cName" name="name" required autocomplete="name" maxlength="255">
    </div>
    <div class="field">
        <label class="field__label" for="cEmail"><?= e(setting('contact_form_label_email', 'Email *')) ?></label>
        <input class="field__input" id="cEmail" name="email" type="email" required autocomplete="email" maxlength="255">
    </div>
    <div class="field">
        <label class="field__label" for="cMsg"><?= e(setting('contact_form_label_message', 'Message *')) ?></label>
        <textarea class="field__input" id="cMsg" name="message" rows="5" required maxlength="5000" placeholder="<?= e(setting('contact_form_placeholder_msg', 'How can we help?')) ?>"></textarea>
    </div>
    <div id="contactMsg" class="field__hint" role="status" aria-live="polite" hidden></div>
    <button type="submit" class="btn btn--navy"><?= e(setting('contact_form_btn_text', 'Send Message')) ?></button>
</form>
<script>
document.getElementById('contactForm')?.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const form=e.target, msg=document.getElementById('contactMsg'), btn=form.querySelector('button');
  const fd=new FormData(form);
  btn.disabled=true; btn.textContent='<?= e(setting('contact_form_sending', 'Sending…')) ?>';
  try{
    const r=await fetch(form.action,{method:'POST',body:fd,headers:{'X-CSRF-TOKEN': fd.get('csrf_token')}});
    const j=await r.json();
    msg.hidden=false;
    if(j.ok){ msg.textContent=j.message; msg.style.color='var(--sage-600)'; form.reset(); }
    else{ msg.textContent=j.error + (j.details? ' — '+Object.values(j.details).join(' '):''); msg.style.color='#b00020'; }
  }catch(err){ msg.hidden=false; msg.textContent='Network error — email info@viataluxe.com'; }
  finally{ btn.disabled=false; btn.textContent='<?= e(setting('contact_form_btn_text', 'Send Message')) ?>'; }
});
</script>
