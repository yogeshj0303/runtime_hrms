# Shift Roster - Testing & Comparison Report

## 44. Shift Roster UI & Planning
- **Expected (Runtime):** A page `Attendance > Shift Roster` where admins can assign future shifts to employees. Features include a date range picker, employee selector, shift dropdown (only pre‑configured shifts), and a bulk upload option. The UI must display a table of existing assignments with columns: Employee, Date, Assigned Shift, and Actions (Edit/Delete).
- **Current (SomyaHRMS):** No Shift Roster page or UI exists.
- **Missing/Issue:** Create a view `resources/views/admin/attendance/shift_roster.blade.php` and a controller `ShiftRosterController@index`. Implement the table, date picker, and shift selection logic.

## 45. Bulk Shift Roster Upload (Excel)
- **Expected (Runtime):** An **Options** button with **Download Template**, **Upload**, and **Export** actions. The template contains columns: Employee Code, Date, Shift Code. Uploading the filled file should bulk create or update `shift_roster` records.
- **Current (SomyaHRMS):** No bulk upload feature.
- **Missing/Issue:** Add controller methods `downloadTemplate`, `uploadExcel`, and `exportRoster` in `ShiftRosterController`. Use `Maatwebsite\Excel` for handling Excel files.

## 46. Shift Roster Validation & Permissions
- **Expected (Runtime):** Only admin users can create/edit rosters. Validation must ensure the shift exists, dates are in the future, and employees are active.
- **Current (SomyaHRMS):** No validation or permission checks.
- **Missing/Issue:** Apply middleware `admin` to `ShiftRosterController` routes and add request validation rules.
