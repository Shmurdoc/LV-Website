/**
 * Category Filter — filters elements by data-category attribute.
 * Used on accommodation and other listing pages.
 */
document.addEventListener('DOMContentLoaded', () => {
  const filterContainers = document.querySelectorAll('[data-category-filter]');
  filterContainers.forEach(container => {
    const type = container.dataset.categoryFilter;
    const items = document.querySelectorAll(`[data-category]`);
    const buttons = container.querySelectorAll('[data-cat]');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const slug = btn.dataset.cat;

        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        items.forEach(item => {
          if (!slug || slug === 'all') {
            item.style.display = '';
          } else {
            const cats = item.dataset.category.split(',');
            item.style.display = cats.includes(slug) ? '' : 'none';
          }
        });

        const url = new URL(window.location);
        if (slug && slug !== 'all') {
          url.searchParams.set('category', slug);
        } else {
          url.searchParams.delete('category');
        }
        history.replaceState(null, '', url);
        sessionStorage.setItem(`filter_${type}`, slug || 'all');
      });
    });

    const urlSlug = new URLSearchParams(window.location.search).get('category');
    const savedSlug = sessionStorage.getItem(`filter_${type}`);
    const initialSlug = urlSlug || savedSlug || 'all';
    const initialBtn = container.querySelector(`[data-cat="${initialSlug}"]`)
                    || container.querySelector('[data-cat="all"]');
    if (initialBtn) initialBtn.click();
  });
});
