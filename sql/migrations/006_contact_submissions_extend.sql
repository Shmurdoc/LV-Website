-- 006: Extend contact_submissions for guesthouse inquiry fields
-- P1-2: Contact form → API submission (replaces mailto)

ALTER TABLE contact_submissions
  ADD COLUMN phone VARCHAR(30)  NULL AFTER email,
  ADD COLUMN arrival_date DATE  NULL AFTER phone,
  ADD COLUMN departure_date DATE NULL AFTER arrival_date,
  ADD COLUMN guests TINYINT UNSIGNED NULL AFTER departure_date;
