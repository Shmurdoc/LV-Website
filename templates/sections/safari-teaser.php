<?php
/**
 * Section: Safari Teaser — Viata Luxe Guesthouse
 * Safari promotional block with text + YouTube video facades.
 * Variables: $section
 */

$activities = get_safari_activities();
$videos = array_filter($activities, fn($a) => !empty($a['video_urls']));
$videos = array_slice(array_values($videos), 0, 2);

$defaultThumb = '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg';
?>

<style>
.yt-facade{position:relative;width:100%;aspect-ratio:16/9;border-radius:10px;overflow:hidden;background:#000;cursor:pointer;display:block}
.yt-facade img{width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.78);transition:filter .3s}
.yt-facade:hover img{filter:brightness(.55)}
.yt-facade__play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;z-index:2;width:56px;height:56px;background:rgba(0,0,0,.6);border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,.5);transition:background .2s,transform .2s}
.yt-facade:hover .yt-facade__play{background:rgba(0,0,0,.85);transform:translate(-50%,-50%) scale(1.06)}
.yt-facade__play::after{content:"";display:block;width:0;height:0;border-top:10px solid transparent;border-bottom:10px solid transparent;border-left:16px solid #fff;margin-left:3px}
.yt-facade__cap{position:absolute;bottom:0;left:0;right:0;padding:14px 16px;background:linear-gradient(transparent,rgba(0,0,0,.78));color:#fff;font-family:var(--font-display);font-size:16px;font-weight:300;z-index:2}
@media(max-width:720px){.yt-facade__play{width:44px;height:44px}.yt-facade__play::after{border-top:8px solid transparent;border-bottom:8px solid transparent;border-left:13px solid #fff}}
</style>

<div class="layout-2col-wide reveal">
    <div>
        <?php if (!empty($section['subtitle'])): ?>
        <div class="kicker"><?= e($section['subtitle']) ?></div>
        <?php endif; ?>
        <?php if (!empty($section['title'])): ?>
        <?php
        $safariRaw = rtrim($section['title'], '.');
        $safariWords = explode(' ', $safariRaw);
        $safariLastWord = array_pop($safariWords);
        $safariFirstPart = implode(' ', $safariWords);
        ?>
        <h2 class="section-heading"><?php if ($safariFirstPart): ?><?= e($safariFirstPart) ?> <?php endif; ?><em class="gold"><?= e($safariLastWord) ?>.</em></h2>
        <?php endif; ?>
        <?php if (!empty($section['content'])): ?>
        <p class="subhead mt-10"><?= e($section['content']) ?></p>
        <?php endif; ?>
        <?php if (!empty($section['link_url'])): ?>
        <div class="safari-teaser__links mt-16">
            <a class="btn btn--navy" href="<?= e(preg_match('#^https?://#i', $section['link_url']) ? $section['link_url'] : url(ltrim($section['link_url'],'/'))) ?>"><?= e($section['link_text'] ?? 'Explore Safari') ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($videos)): ?>
    <div class="safari-tease reveal reveal--delay-1">
        <div style="display:grid;grid-template-columns:1fr;gap:16px">
            <?php foreach ($videos as $vid):
                $videoUrls = json_decode($vid['video_urls'] ?? '[]', true);
                $videoUrl  = $videoUrls[0] ?? '';
                $ytId      = preg_match('/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]+)/', $videoUrl, $m) ? $m[1] : '';
                $thumb     = url($vid['image'] ?? $defaultThumb);
            ?>
            <div class="yt-facade" data-src="https://www.youtube.com/embed/<?= e($ytId) ?>?autoplay=1&rel=0&modestbranding=1&playsinline=1">
                <img src="<?= e($thumb) ?>" alt="<?= e($vid['title']) ?>" width="720" height="405" loading="lazy" decoding="async">
                <div class="yt-facade__play" aria-hidden="true"></div>
                <div class="yt-facade__cap"><?= e($vid['title']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php elseif (!empty($section['image'])): ?>
    <div class="safari-tease reveal reveal--delay-1">
        <div class="safari-tease__media">
            <img src="<?= e(image_url($section['image'])) ?>" alt="<?= e($section['title'] ?? 'Safari') ?>" loading="lazy" decoding="async">
            <div class="safari-tease__veil"></div>
        </div>
        <div class="safari-tease__body">
            <div class="safari-teaser__body-title">Safari Videos</div>
            <div class="safari-teaser__body-sub">Click to play — Kruger wildlife footage</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
(function(){
  document.querySelectorAll('.yt-facade').forEach(function(facade){
    facade.addEventListener('click', function(){
      var src = facade.getAttribute('data-src');
      if(!src) return;
      var iframe = document.createElement('iframe');
      iframe.src = src;
      iframe.setAttribute('allow','accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
      iframe.setAttribute('allowfullscreen','');
      iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0';
      facade.replaceWith(iframe);
    });
  });
})();
</script>
