import { test, expect } from '@playwright/test';

// =============================================================
// ADMIN BACKEND CRUD ROUND-TRIP (v2)
// Proves admin/api/crud.php: auth enforced, reads DB correctly,
// and CREATE → persists → shown in list → UPDATE → DELETE all
// land on the correct rows.
// =============================================================

const marker = `QA_CRUD_${Date.now()}`;

test.describe('Admin backend — CRUD round-trip against real DB', () => {

  test('requires auth (rejects without session)', async ({ request }) => {
    const r = await request.post('/admin/api/crud.php', {
      form: { action: 'save', entity: 'testimonial', reviewer_name: marker, review_text: 'x' }
    });
    // No session → require_admin() redirects to login; fetch follows to JSON 200? Assert NOT a success save.
    const body = await r.text();
    expect(body).not.toContain('"success":true');
  });

  test('logs in and performs create → list → update → delete on testimonials', async ({ page }) => {
    // Login
    await page.goto('/admin/login');
    await page.fill('#username', 'admin');
    await page.fill('#password', 'ViataLuxe2025!');
    await page.getByRole('button', { name: /sign in/i }).click();
    await page.waitForURL(/\/admin\/dashboard/, { timeout: 8000 });

    // Create via API (share browser session cookies via context.request)
    const ctx = page.context();
    // Grab CSRF from an admin page that renders csrf_field()
    await page.goto('/admin/pages');
    const csrf = await page.evaluate(() => document.querySelector('input[name="csrf_token"]')?.value || '');

    const create = await ctx.request.post('/admin/api/crud.php', {
      form: { action: 'save', entity: 'testimonial', reviewer_name: marker, review_text: 'Fresh record QA', rating: '5', source: 'qa', is_featured: '', is_published: '1', sort_order: '99', apartment_id: '', csrf_token: csrf }
    });
    expect(create.status()).toBe(200);
    const created = await create.json();
    expect(created.success).toBe(true);

    // Verify it shows on the public testimonials list (frontend reads DB)
    await page.goto('/');
    await expect(page.getByText(marker).first()).toBeVisible({ timeout: 5000 });

    // Read back via admin list page (backend reads DB) — SPA loads async, wait for the table body
    await page.goto('/admin/testimonials');
    await page.waitForSelector('table, [class*="admin-page"]', { timeout: 8000 }).catch(() => {});
    await page.waitForSelector(`text=${marker}`, { timeout: 8000 }).catch(() => {});
    await expect(page.getByText(marker).first()).toBeVisible({ timeout: 8000 });

    // Update it
    const upd = await ctx.request.post('/admin/api/crud.php', {
      form: { action: 'save', entity: 'testimonial', id: String(created.id ?? ''), reviewer_name: marker + '_UPD', review_text: 'Updated record QA', rating: '4', source: 'qa', is_featured: '', is_published: '1', sort_order: '99', apartment_id: '', csrf_token: csrf }
    });
    expect(upd.ok()).toBe(true);
    await page.goto('/');
    await expect(page.getByText(marker + '_UPD').first()).toBeVisible({ timeout: 5000 });

    // Delete
    const del = await ctx.request.post('/admin/api/crud.php', {
      form: { action: 'delete', entity: 'testimonial', id: String(created.id ?? ''), csrf_token: csrf }
    });
    expect(del.ok()).toBe(true);
    await page.goto('/');
    await expect(page.getByText(marker + '_UPD')).toHaveCount(0, { timeout: 5000 });
  });
});